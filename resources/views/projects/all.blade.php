@extends('layouts.app')

@section('content')
    <projects-all inline-template>
        <div class="container" id="projects-all">
            <div class="title-with-buttons">
                <h1>Projects</h1>
                @can('project_manage')
                    <div class="buttons">
                        <a class="link-new-project" href="/projects/start">
                            <button class="btn btn-solid-green button-start-project">New Project</button>
                        </a>
                    </div>
                @endcan
            </div>
                <div class="project-list" v-if="projects">
                        <template v-for="project in projects">
                            <div class="project-single standard-box">
                                <div class="left">
                                    <div class="project-thumbnail">
                                            <img src="#" v-if="project.thumbnail">
                                            <i class="project-placeholder fa fa-building" v-else></i>
                                    </div>
                                </div>
                                <div class="right">
                                    <div class="header">
                                        <a href="/projects/@{{ project.id }}"
                                           class="project-single-link">
                                            <h2 class="project-name">
                                                @{{ project.name }}
                                            </h2>
                                        </a>
                                        <div class="project-actions">
                                            <span class="button-project-dropdown clickable"><i
                                                        class="fa fa-chevron-right"></i></span>
                                            <div class="project-popup">
                                                <div class="caret-up"></div>
                                                <ul class="list-unstyled">
                                                    <li><a href="/projects/@{{ project.id }}/edit"><i
                                                                    class="fa fa-pencil"></i>Edit</a></li>
                                                    <li><a class="danger" href="#" @click="deleteProject(project)
                                                        ">Delete</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <table class="project-details">
                                        <tbody>
                                        <tr>
                                            <th>Created</th>
                                            <td>@{{ project.created_at | easyDate }}</td>
                                        </tr>
                                        <tr>
                                            <th>Location</th>
                                            <td>@{{ project.location }}</td>
                                        </tr>
                                        <tr>
                                            <th>Team Members</th>
                                            <td>@{{ project.team_members.length }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </template>
                </div>
                <span class="page-error" v-else>There are currently no projects.</span>
            <modal></modal>
        </div>
    </projects-all>
@endsection