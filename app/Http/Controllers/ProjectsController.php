<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveTeamMemberRequest;
use App\Http\Requests\StartProjectRequest;
use App\Mailers\UserMailer;
use App\Project;
use App\Role;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProjectsController extends Controller
{

    protected $company;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('company');
        if ($user = Auth::user()) {
            $this->company = Auth::user()->company->load('projects');
        }
    }

    /**
     * Shows a list of all of the company's
     * projects
     *
     * @return mixed
     */
    public function getAll()
    {
        $breadcrumbs = [
            ['<i class="fa fa-flash"></i> Project', '#']
        ];
        return view('projects.all', compact('breadcrumbs'))->with('company', $this->company);
    }

    /**
     * GET form to start a
     * new Project
     *
     * @return mixed
     */
    public function getNewProjectForm()
    {
        if (Gate::allows('project_manage')) {
            $breadcrumbs = [
                ['<i class="fa fa-flash"></i> Project', '/projects'],
                ['Start New', '#']
            ];
            return view('projects.start', compact('breadcrumbs'));
        }
        return redirect('/projects');
    }

    /**
     * Handles POST req. to start
     * a project
     *
     * @param StartProjectRequest $request
     * @return mixed
     */
    public function postStartProject(StartProjectRequest $request)
    {
        $this->company->startProject($request, Auth::user());
        return redirect('/projects');
    }

    /**
     * Gets a single project using route-wildcard
     * binding and returns it to user.
     *
     * @param Project $project
     * @return mixed
     */
    public function getSingle(Project $project)
    {
        $breadcrumbs = [
            ['<i class="fa fa-flash"></i> Project', '/projects'],
            [$project->name, '#']
        ];
        if (Gate::allows('view', $project)) return view('projects.single', compact('project', 'breadcrumbs'));
        return redirect('/projects');
    }

    /**
     * Shows the Add Team Member page to
     * authorized Users.
     *
     * @param Project $project
     * @return mixed
     */
    public function getAddTeamMember(Project $project)
    {
        if (Gate::allows('team_manage') && Gate::allows('view', $project)) return view('projects.team.add', compact('project'));
        return redirect('/projects');
    }


    /**
     * Handles POST request to either add an
     * existing user to a project or make
     * and send invitation to new user
     * 
     * @param Project $project
     * @param SaveTeamMemberRequest $request
     * @param UserMailer $userMailer
     * @return mixed
     */
    public function postSaveTeamMember(Project $project, SaveTeamMemberRequest $request, UserMailer $userMailer)
    {
        // Are we selecting a new user?
        if ($existingUserId = $request->input('existing_user_id')) {
            // Adding existing user
            $user = User::find($existingUserId);     // fetch user

            // Whenever we are changing a User Model - lets make sure the acting user
            // is authorized to do it.

            if (Gate::allows('edit', $user)) {
                $project->addTeamMember($user);
                flash()->success('Added a new Team Member to the project');
                return redirect(route('singleProject', [$project->id]));
            }

            abort(403, 'You are unauthorized to change that user');
        } else {
            // Inviting new user
            $inviteKey = str_random(13);    // create random key
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'role_id' => $request->input('role_id'),
                'invite_key' => $inviteKey
            ]);

            $this->company->addEmployee($user);
            $project->addTeamMember($user);
            $userMailer->sendNewUserInvitation($user);

            // Flash some success notification
            flash()->success('Sent sign-up invitation to new member');
            return redirect(route('singleProject', [$project->id]));
        }
    }

}
