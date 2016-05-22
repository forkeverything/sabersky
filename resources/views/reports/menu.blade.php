@extends('layouts.app')
@section('content')
<div id="reports-menu" class="container">
    <div class="page-body">
        <ul class="list-unstyled list-types">
            <li class="single-type spendings">
                <h3>Spendings</h3>
                <ul class="list-unstyled list-categories">
                    <li class="single-category">
                        <a href="/reports/spendings/projects" alt="Projects Spendings Report">Projects</a>
                    </li>
                    <li class="single-category">
                        <a href="/reports/spendings/vendors" alt="Vendors Spendings Report">Vendors</a>
                    </li>
                    <li class="single-category">
                        <a href="/reports/spendings/employees" alt="Employees Spendings Report">Employees</a>
                    </li>
                    <li class="single-category">
                        <a href="/reports/spendings/items" alt="Items Spendings Report">Items</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
@endsection