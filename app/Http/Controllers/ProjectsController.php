<?php

namespace App\Http\Controllers;

use App\Http\Requests\StartProjectRequest;
use App\Project;
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
        if ($user = Auth::user()) {
            $this->company = $user->company;
        }

    }

    public function showAll()
    {
        $company = Auth::user()->company;
        return view('projects.all')->with('company', $this->company);
    }

    public function getProjectForm()
    {
        if (Gate::denies('project_manage')) {
            return redirect('/projects');
        }

        return view('projects.start');
    }

    public function startProject(StartProjectRequest $request)
    {
        $project = $this->company->projects()->create($request->all());
        Auth::user()->projects()->save($project);
        return redirect('/projects');
    }

    public function single(Project $project)
    {
        return view('projects.single', compact('project'));
    }

}
