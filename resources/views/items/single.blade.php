@extends('layouts.app')
@section('content')

    <div class="container" id="item-single">
        <div class="page-body">
            <div class="row">
                <div class="col-sm-3 photos">
                    <div class="main-image">
                        @if($item->photos->first())
                            <img src="{{ $item->photos->first()->path }}" alt="">
                        @else
                            <div class="placeholder">
                                <i class="fa fa-image"></i>
                            </div>
                        @endif
                    </div>
                    @if(count($item->photos) > 1)
                        <ul class="image-gallery list-unstyled">
                            @foreach($item->photos as $photo)
                                <li>
                                    <a href="{{ $photo->path }}" rel="group" class="fancybox">
                                        <img src="{{ $photo->thumbnail_path }}" alt="Item Photo">
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div class="col-sm-9 details">
                    <h5>Item Details</h5>
                    <!-- Item Details Table -->
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>SKU</th>
                            <td>{{ $item->sku }}</td>
                        </tr>
                        <tr>
                            <th>Brand</th>
                            <td>{{ $item->brand }}</td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td>{{ $item->name }}</td>
                        </tr>
                        <tr>
                            <th>Specification</th>
                            <td>{{ $item->specification }}</td>
                        </tr>
                        <tr>
                            <th>Projects</th>
                            <td>
                                @if($item->projects()->first())
                                    <ul class="projects-list list-unstyled">
                                        @foreach($item->projects() as $project)
                                            <li>{{ $project->name }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    none
                                @endif
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection