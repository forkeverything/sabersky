@extends('layouts.app')
@section('content')
    <div id="items-all" class="container">
        <a href="{{ route('dashboard') }}" class="back-link no-print"><i class="fa  fa-arrow-left fa-btn"></i>Dashboard</a>
        <div class="page-header">
            <h1 class="page-title">Items</h1>
        </div>
        @if($itemNames->first())
            <div class="page-body">
                <div class="item-gallery">
                    @foreach($itemNames->chunk(4) as $chunk)
                        <div class="row">
                            @foreach($chunk as $itemName)
                                <div class="col-xs-3">
                                    <a href="#" class="item-link">
                                        {{--@if($photo = $item->photos()->first())--}}
                                        {{--<img src="{{ $item->photos()->first()->thumbnail_path }}" alt="item thumbnail">--}}
                                            {{--@else--}}
                                        {{--<div class="item-placeholder">--}}
                                            {{--<i class="fa fa-paperclip"></i>--}}
                                        {{--</div>--}}
                                        {{--@endif--}}
                                        <h5 class="item-name">{{ $itemName }}</h5>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <span class="page-error">No items have been created. Make your first purchase request to create an item!</span>
        @endif
    </div>
@endsection
