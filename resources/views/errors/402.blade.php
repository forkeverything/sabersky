@extends('layouts.error')

@section('code')
    402
@endsection

@section('title')
    {{ $exception->getMessage() }}
@endsection

@section('body')
    <p>
        You are seeing this message because your company's subscription is inactive. Please ask your accounts administrator
        to reactivate your subscription to continue using Sabersky.
    </p>
@endsection
