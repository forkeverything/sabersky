@extends('layouts.app')
@section('content')
<team-all inline-template :user="user">
    <div class="container" id="team-all">
        @can('team_manage')
        <section class="align-end">
            <a href="/team/add" class="link-add-team">
                <button class="btn btn-solid-green"><i class="fa fa-user-plus fa-btn"></i>Invite User
                </button>
            </a>
        </section>
        @endcan
        <div class="page-body">
            <h5><span class="capitalize">@{{ user.company.name }}</span> Staff</h5>
            <div class="table-employees">
                <power-table :headers="tableHeaders" :data="employees" :sort="true" :hover="true"></power-table>
            </div>
        </div>
    </div>
</team-all>
@stop