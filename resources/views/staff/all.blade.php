@extends('layouts.app')
@section('content')
    <staff-all inline-template :user="user">
        <div class="container" id="staff-all">
            <div class="title-with-buttons">
                <h1 class="capitalize">@{{ user.company.name }} Staff</h1>
                @can('team_manage')
                    <div class="buttons">
                        <a href="/staff/add" class="link-add-team button-right">
                            <button class="btn btn-solid-green"><i class="fa fa-user-plus fa-btn"></i>Invite Staff
                            </button>
                        </a>
                    </div>
                @endcan
            </div>
            <div class="table-employees">
                <power-table :headers="tableHeaders" :data="employees" :sort="true" :hover="true"></power-table>
            </div>
        </div>
    </staff-all>
@stop