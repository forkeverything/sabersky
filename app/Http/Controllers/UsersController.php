<?php

namespace App\Http\Controllers;

use App\Events\InvitedStaffMember;
use App\Http\Requests\AcceptInvitationRequest;
use App\Http\Requests\AddStaffRequest;
use App\Http\Requests\ChangeUserRoleRequest;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Http\Requests\UploadImageRequest;
use App\LineItem;
use App\Mailers\UserMailer;
use App\Role;
use App\User;
use App\Utilities\BuildPhoto;
use App\Utilities\CalendarEventsPlanner;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'billing'], [
            'except' => [
                'getAcceptView',
                'postAcceptInvitation',
                'getCheckEmailAvailability',
                'getLoggedUser'
            ]
        ]);

        $this->middleware('api.only', [
            'only' => ['apiGetStaff',
                'apiGetSearchTeamMembers',
                'apiGetSearchStaff',
                'apiGetAllProjects',
                'getLoggedUser'
            ]
        ]);
    }

    /**
     * Receives an invitation key and show the
     * Accept Invitation view if the key
     * successfully matches a user.
     *
     * @param $inviteKey
     * @return mixed
     */
    public function getAcceptView($inviteKey)
    {
        Auth::logout();
        if ($user = User::fetchFromInviteKey($inviteKey)) {
            return view('auth.accept', compact('user'));
        };
        flash()->error('Invite Key was incorrect or invalid');
        return redirect('/');
    }

    public function postAcceptInvitation(AcceptInvitationRequest $request, $inviteKey)
    {
        if ($user = User::fetchFromInviteKey($inviteKey)) {

            $user->setPassword($request->input('password'))
                 ->clearInviteKey();

            flash()->success('Accepted invitation, welcome aboard!');
            Auth::login($user);
            return redirect('/');
        }
        flash()->error('Could join Team. Please request a new invitation key');
        return redirect('/');
    }

    /**
     * Accepts an email (string) and checks to see
     * if it is available.
     *
     * @param $email
     * @return mixed
     */
    public function getCheckEmailAvailability($email)
    {
        if (User::where('email', $email)->first()) return response("Email already taken", 409);
        return response("OK! Email available", 200);
    }

    /**
     * Returns the currently authenticated
     * user.
     *
     * @return mixed
     */
    public function getLoggedUser()
    {
        $user = Auth::user()->load('company', 'company.address', 'company.settings', 'role');
        return $user;
    }

    /**
     * Get view for logged in User's profile
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getOwnProfile()
    {
        $user = Auth::user()->load('company', 'company.address', 'company.settings', 'role');
        return view('user.profile', compact('user'));
    }

    /**
     * Updates a User's profile (direct attributes) and returns the new User
     *
     * @param UpdateUserProfileRequest $request
     * @return mixed
     */
    public function putUpdateProfile(UpdateUserProfileRequest $request)
    {
        if (Auth::user()->update($request->all())) return response("Updated user profile", 200);
        return response("Could not update profile", 500);
    }

    /**
     * Handle request to upload a Profile Photo
     *
     * @param UploadImageRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postProfilePhoto(UploadImageRequest $request)
    {
        $photo = BuildPhoto::profile($request->image, Auth::user());
        if ($existingPhoto = Auth::user()->photo) $existingPhoto->remove();
        Auth::user()->photo()->save($photo) ? flash()->success('Changed profile photo') : flash()->error('Could not change profile photo');
        return redirect()->back();
    }

    /**
     * Removes the profile photo for logged-in user
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteProfilePhoto()
    {
        if ($existingPhoto = Auth::user()->photo) $existingPhoto->remove();
        return response("Removed profile photo", 200);
    }

    /**
     * Show View that contains all of the User's
     * Company's employees.
     *
     * @return mixed
     */
    public function getStaff()
    {
        return view('staff.all');
    }

    /**
     * Fetches the Authorized Users's
     * Team Memembers
     *
     * @return mixed
     */
    public function apiGetStaff()
    {
        return Auth::user()->company->employees->load('role');
    }

    /**
     * Search for Users who are from the same Company as the logged-user. In other
     * words we're looking for other Employees from the Client's Company.
     *
     * @param $term
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Database\Eloquent\Collection|\Symfony\Component\HttpFoundation\Response|static[]
     */
    public function apiGetSearchStaff($term)
    {
        if (!$term) return response("No search term given", 404);
        return User::where('company_id', Auth::user()->company_id)
                   ->where(function ($query) use ($term) {
                       $query->where('name', 'LIKE', '%' . $term . '%')
                             ->orWhere('email', 'LIKE', '%' . $term . '%');
                   })
                   ->with('role')
                   ->get();
    }

    /**
     * Search for Team Members (Users from same Project)
     * by their name.
     *
     * @return mixed
     * @internal param $query
     */
    public function apiGetSearchTeamMembers($term)
    {
        if ($term) {
            $projectIDs = Auth::user()->projects->pluck('id');
            $users = User::where('company_id', Auth::user()->company_id)
                         ->whereExists(function ($query) use ($projectIDs) {
                             $query->select(DB::raw(1))
                                   ->from('project_user')
                                   ->whereIn('project_id', $projectIDs)
                                   ->whereRaw('users.id = user_id');
                         })
                         ->where(function ($query) use ($term) {
                             $query->where('name', 'LIKE', '%' . $term . '%')
                                   ->orWhere('email', 'LIKE', '%' . $term . '%');
                         })
                         ->with('role')
                         ->get();
            return $users;
        }
        return response("No search term given", 500);
    }


    /**
     * Show Form to add a new User to
     * Company.
     *
     * @return mixed
     */
    public function getAddStaffForm()
    {
        if (!Gate::allows('team_manage')) abort(403, "Not authorized to add Staff");
        $roles = Auth::user()->company->getRolesNotAdmin();
        return view('staff.add', compact('roles', 'breadcrumbs'));
    }

    /**
     * Handle Form POST req. to add a new
     * Staff (User) to the Company
     *
     * @param AddStaffRequest $request
     * @param UserMailer $userMailer
     * @return mixed
     */
    public function postSaveStaff(AddStaffRequest $request, UserMailer $userMailer)
    {
        $role = Role::find($request->input('role_id'));
        if (!Gate::allows('attaching', $role)) abort(403, "Selected Role is not allowed: does not belong to Company or is Admin");
        $user = User::make($request->input('name'), $request->input('email'), null, $request->input('role_id'), true);
        Auth::user()->company->addEmployee($user);
        Event::fire(new InvitedStaffMember($user, Auth::user()));
        flash()->success('Sent invitation to join ' . ucwords(Auth::user()->company->name) . ' on Sabersky');
        return redirect('/staff');
    }

    /**
     * Shows View for a single User that
     * is from the same Company
     *
     * @param User $user
     * @return mixed
     */
    public function getSingleUser(User $user)
    {
        if (!Gate::allows('edit', $user)) abort(403, "Not authorized to view that User");
        $roles = Auth::user()->company->getRolesNotAdmin()->sortBy('position');
        return view('staff.single', compact('user', 'roles'));
    }

    public function putChangeRole(ChangeUserRoleRequest $request, $userId)
    {
        User::find($userId)->setRole($role = Role::find($request->input('role_id')));
        flash()->success('Changed User Role to ' . ucwords($role->position));
        return redirect()->back();
    }

    /**
     * Returns all the Projects that the currently
     * Authenticated user is a part of
     *
     * @return mixed
     */
    public function apiGetAllProjects()
    {
        return Auth::user()->projects;
    }

    /**
     * Retrieves all calendar events for logged-in User
     *
     * @return array
     */
    public function getCalendarEvents()
    {
        $events = [];

        $planner = CalendarEventsPlanner::forUser(Auth::user());

        if (Gate::allows('po_payments')) {
            $payableEvents = $planner->getPayables();
            $events = array_merge($events, $payableEvents);
        }

        if (Gate::allows('po_warehousing')) {
            $deliveryEvents = $planner->getDeliveries();
            $events = array_merge($events, $deliveryEvents);
        }

        return $planner->filterUnique($events);
    }

    /**
     * Toggles whether user is active / deactive
     *
     * @param User $user
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function toggleActive(User $user)
    {
        if (!Auth::user()->hasRole('admin') && !Gate::allows('edit', $user) && $user->role->position !== 'admin') abort(403, "Not allowed to deactivate that user");
        if ($user->toggleActive()) {
            if($user->active) {
                flash()->success('Activated user');
            } else {
                flash()->info('Deactivated user');
            }
            return redirect()->back();
        }
        return response("Something went sideways. Could not delete User", 500);
    }

    /**
     * Remove an Admin account which also deletes the Company, all staff users and all associated DB records as
     * well as physical files. PERMANENT WIPE.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAdmin()
    {
        $user = Auth::user();
        if (!$user->hasRole('admin')) {
            flash()->error('Account to be deleted was not an admin');
            return redirect()->back();
        }

        $user->company->delete();

        Auth::logout();
        return redirect()->back();
    }

}
