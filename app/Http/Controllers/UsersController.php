<?php

namespace App\Http\Controllers;

use App\Http\Requests\AcceptInvitationRequest;
use App\Http\Requests\AddStaffRequest;
use App\Mailers\UserMailer;
use App\Role;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', [
            'except' => [
                'getAcceptView',
                'postAcceptInvitation',
                'getCheckEmailAvailability'
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
            return redirect(route('singleProject', $user->projects()->first()->id));
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
    public function apiGetLoggedUser()
    {
        $user = Auth::user()->load('company', 'role');

        return $user;
    }

    /**
     * Show View that contains all of the User's
     * Company's employees.
     *
     * @return mixed
     */
    public function getTeam()
    {
        $breadcrumbs = [
            ['<i class="fa fa-users"></i> Team', '/team']
        ];
        return view('team.all', compact('employees', 'breadcrumbs'));
    }

    /**
     * Fetches the Authorized Users's
     * Team Memembers
     *
     * @return mixed
     */
    public function apiGetTeam()
    {
        return Auth::user()->company->employees->load('role');
    }

    /**
     * Show Form to add a new User to
     * Company.
     *
     * @return mixed
     */
    public function getAddStaffForm()
    {
        if(! Gate::allows('team_manage')) abort(403, "Not authorized to add Staff");
        $roles = Auth::user()->company->getRolesNotAdmin();
        return view('team.add', compact('roles'));
    }

    public function postSaveStaff(AddStaffRequest $request, UserMailer $userMailer)
    {
        $role = Role::find($request->input('role_id'));
        if(! Gate::allows('attaching', $role)) abort(403, "Selected Role is not allowed: does not belong to Company or is Admin");
        $user = User::make($request->input('name'), $request->input('email'), null, $request->input('role_id'), true);
        Auth::user()->company->addEmployee($user);
        $userMailer->sendNewUserInvitation($user);
        flash()->success('Sent invitation to join ' . ucwords(Auth::user()->company->name));
        return redirect('/team');
    }

}
