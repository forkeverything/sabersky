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
        if($user = Auth::user()) {
            $this->company = Auth::user()->company->load('projects');
        }
    }

    /**
     * Shows a list of all of the company's
     * projects
     *
     * @return mixed
     */
    public function showAll()
    {
        return view('projects.all')->with('company', $this->company);
    }

    /**
     * Get form to start a project.
     *
     * @return mixed
     */
    public function getProjectForm()
    {
        if (Gate::allows('project_manage')) {
            return view('projects.start');
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
        if(Gate::allows('view', $project)) return view('projects.single', compact('project'));
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
        if(Gate::allows('team_manage') && Gate::allows('view', $project)) return view('projects.team.add', compact('project'));
        return redirect('/projects');
    }

    public function saveTeamMember(Project $project, SaveTeamMemberRequest $request, UserMailer $userMailer)
    {
        if ($existingUserId = $request->input('existing_user_id')) {
            // Adding existing user
            $user = User::find($existingUserId);
            $project->teamMembers()->save($user);
            flash()->success('Succesfully added an existing Team Member');
            return redirect(route('singleProject', [$project->id]));
        } else {
            $inviteKey = str_random(13);
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'role_id' => $request->input('role_id'),
                'invite_key' => $inviteKey
            ]);
            $this->company->employees()->save($user);
            $project->teamMembers()->save($user);
            $userMailer->sendNewUserInvitation($user);
            // Flash some success notification
            flash()->success('Succesfully invited a new Team Member');
            return redirect(route('singleProject', [$project->id]));
        }
    }

}
