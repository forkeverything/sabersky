@extends('layouts.app')

@section('content')
        <div class="container" id="company-registration">
            <div class="page-header">
                <h1 class="page-title">Register your company</h1>
            </div>
            <div class="page-body">
                <h2>Company Registrations</h2>
                <p>Congratulations, just a few more steps until a full-package solution to streamline your company's
                    purchasing. We're here to help you eliminate overlap, reduce redundancy and increase the
                    efficiency of your purchasing system. You will no longer have problems with accountability,
                    reliability or timeliness.</p>
                @include('errors.list')
                <form id="form-register-company" action="{{ route('saveCompany') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="field-company-name">Company Name</label>
                        <input type="text" id="field-company-name" name="name" class="form-control"
                               value="{{ old('name') }}"/>
                    </div>
                    <div class="form-group">
                        <label for="field-company-description">Description</label>
                            <textarea id="field-company-description" class="form-control" rows="15"
                                      name="description">{{ old('description') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="field-company-currency">Currency Symbol</label>
                        <input type="text" id="field-company-currency" name="currency" class="form-control"
                               value="$" placeholder="$">
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4 col-md-offset-8">
                                <button type="submit" class="btn btn-solid-blue form-control">Register</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
@endsection
