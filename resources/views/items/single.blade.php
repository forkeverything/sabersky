@extends('layouts.app')
@section('content')
    <item-single inline-template :item-id="'{{ $item->id }}'" :user="user">
        <div class="container" id="item-single">
            <div class="page-body">
                <div class="row">
                    <div class="col-sm-3 photos">
                        <div class="main-image">
                            <a v-if="photos[0]" :href="photos[0].path" rel="group" class="fancybox"><img
                                        :src="photos[0].path" alt="Item Main Photo"></a>
                            <div class="placeholder" v-else>
                                <i class="fa fa-image"></i>
                            </div>
                        </div>
                        <ul class="image-gallery list-unstyled" v-if="photos.length > 0">
                            <li v-for="photo in photos">
                                <a :href="photo.path" rel="group" class="fancybox">
                                    <img :src="photo.thumbnail_path" alt="Item Photo">
                                </a>
                                <button type="button" class="btn button-delete-photo" @click.stop="deletePhoto(photo)">
                                    <i class="fa fa-close"></i></button>
                            </li>
                        </ul>
                        <div class="dropzone-errors" v-show="fileErrors.length > 0">
                            <span class="error-heading">Following errors occurred:</span>
                            <span class="button-clear" @click="clearErrors">clear</span>
                            <ul class="file-upload-errors">
                                <li v-for="error in fileErrors" track-by="$index">@{{ error }}</li>
                                </ul>
                            </div>
                        <form id="item-photo-uploader" class="dropzone" action="{{ route('addItemPhoto', $item->id) }}">
                            {{ csrf_field() }}
                            <div class="dz-message"><i class="fa fa-image"></i>
                                Click or drop images to upload
                            </div>
                        </form>
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
                                                <li><a href="{{ route('singleProject', $project->id) }}" alt="Link to project" class="dotted">{{ $project->name }}</a></li>
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

                <div class="item-notes">
                    <h5>Notes</h5>
                    <notes subject="item" subject_id="{{ $item->id }}" :user="user"></notes>
                </div>

                @include('layouts.partials.activities_log', ['activities' => $item->activities])
            </div>

        </div>
    </item-single>
@endsection