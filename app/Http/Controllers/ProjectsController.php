<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveTeamMemberRequest;
use App\Http\Requests\StartProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
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
            ['<i class="fa fa-flash"></i> Projects', '#']
        ];
        return view('projects.all', compact('breadcrumbs'))->with('company', $this->company);
    }


    /**
     * Returns all the Projects that the
     * Users's company is performing.
     *
     * @return mixed
     */
    public function apiGetAll()
    {
        return $this->company->projects->load('teamMembers');
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
                ['<i class="fa fa-flash"></i> Projects', '/projects'],
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
            ['<i class="fa fa-flash"></i> Projects', '/projects'],
            [$project->name, '#']
        ];
        if (Gate::allows('view', $project)) return view('projects.single', compact('project', 'breadcrumbs'));
        return redirect('/projects');
    }

    /**
     * returns all Team Members (users) that belong
     * to a Project
     *
     * @param Project $project
     * @return \App\User[]|\Illuminate\Database\Eloquent\Collection
     */
    public function apiGetTeamMembers(Project $project)
    {
        $this->checkProjectAuthorization($project);
//        if (! Gate::allows('view', $project))  return response("You are not authorized to edit that project", 403);
        return $project->teamMembers->load('role');
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
        $breadcrumbs = [
            ['<i class="fa fa-flash"></i> Projects', '/projects'],
            [$project->name, '/projects/' . $project->id],
            ['Add Team Member', '#'],
        ];

        $roles = $this->company->roles;
        if (Gate::allows('team_manage') && Gate::allows('view', $project)) return view('projects.team.add', compact('project', 'roles', 'breadcrumbs'));
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

    /**
     * Show client the form to edit
     * a Project
     *
     * @param Project $project
     * @return mixed
     */
    public function getEditForm(Project $project)
    {
        if (!Gate::allows('view', $project)) return response("You are not authorized to edit that project", 403);
        $breadcrumbs = [
            ['<i class="fa fa-flash"></i> Projects', '/projects'],
            [$project->name, '/projects/' . $project->id],
            ['Edit', '#'],
        ];
        return view('projects.edit', compact('project', 'breadcrumbs'));
    }

    /**
     * POST request to update a Project
     *
     * @param UpdateProjectRequest $request
     * @param $projectId
     * @return mixed
     */
    public function postUpdateProject(UpdateProjectRequest $request, $projectId)
    {
        Project::find($projectId)->update($request->all());
        flash()->success('Updated Project details');
        return redirect()->back();
    }

    /**
     * Delete request to remove Project
     *
     * @param Project $project
     * @return string
     * @throws \Exception
     */
    public function apiDelete(Project $project)
    {
        $this->checkProjectAuthorization($project);
        if($project->delete()) return 'Successfully deleted Project';
        return response("Error: Could not delete Project", 500);
    }

    /**
     * Check is user is authorized to view a Project.
     *
     * @param $project
     * @return bool
     */
    protected function checkProjectAuthorization($project)
    {
        if (! Gate::allows('view', $project))  abort(403, "You are not authorized to edit that project");
        return true;
    }
    
    
    

}
