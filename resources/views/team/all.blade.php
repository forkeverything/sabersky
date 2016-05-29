@extends('layouts.app')
@section('content')
    <team-all inline-template :user="user">
        <div class="container" id="team-all">
            <div class="title-with-buttons">
                <h1>Team</h1>
                @can('team_manage')
                    <div class="buttons">
                        <a href="/team/add" class="link-add-team button-right">
                            <button class="btn btn-solid-green"><i class="fa fa-user-plus fa-btn"></i>Invite User
                            </button>
                        </a>
                    </div>
                @endcan
            </div>
            <h5><span class="capitalize">@{{ user.company.name }}</span> Staff</h5>
            <div class="table-employees">
                <power-table :headers="tableHeaders" :data="employees" :sort="true" :hover="true"></power-table>
            </div>
        </div>
    </team-all>
@stop