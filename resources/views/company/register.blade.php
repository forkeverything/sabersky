@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Register your company</h1>
        </div>
        <div class="page-body">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <h3>Company Registrations</h3>
                    <p>Congratulations, just a few more steps until a full-package solution to streamline your company's purchasing. We're here to help you eliminate overlap, reduce redundancy and increase the efficiency of your purchasing system. You will no longer have problems with accountability, reliability or timeliness.</p>

                    @include('errors.list')


                    <form id="form-register-company" action="{{ route('saveCompany') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="field-company-name">Company Name</label>
                            <input type="text" id="field-company-name" name="name" class="form-control" value="{{ old('name') }}"/>
                        </div>
                        <div class="form-group">
                            <label for="field-company-description">Description</label>
                        <textarea id="field-company-description" class="form-control" rows="15" name="description">
                            {{ old('description') }}
                        </textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary form-control">Register</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
