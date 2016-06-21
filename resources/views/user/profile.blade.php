@extends('layouts.app')
@section('content')
    <user-profile :user="{{ $user }}" inline-template>
        <div id="user-profile" class="container">
            <h1>Your Profile</h1>
            <div class="content">
                <div class="left">
                    <div class="popover-container clickable profile-popup" @click.stop="togglePhotoMenu">
                        <profile-photo :user="user"></profile-photo>
                        <div class="popover-content animated center" v-show="showProfilePhotoMenu"
                             transition="fade-slide">
                            <ul class="list list-unstyled">
                                <li><a @click="showFileSelecter">Upload</a></li>
                                @if($user->photo)
                                    <li><a @click="removePhoto">Remove</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <form v-el:profile-photo-form enctype="multipart/form-data" action="/user/profile/photo"
                          method="POST">
                        {{ csrf_field() }}
                        <input v-el:file-input class="hidden" name="image" type="file" @change="uploadProfilePhoto">
                    </form>
                </div>
                <div class="right">
                    <form-errors></form-errors>
                    <section class="contact">
                        <form @submit.prevent="updateProfile('Contact')" v-show="editingContact">
                            <div class="form-group">
                                <label for="profile-name">Name</label>
                                <input id="profile-name" type="text" class="form-control" v-model="user.name">
                            </div>
                            <div class="form-group">
                                <label for="profile-email">Email</label>
                                <input type="text" id="profile-email" class="form-control" v-model="user.email">
                            </div>
                            <div class="form-group">
                                <label for="profile-phone">Phone</label>
                                <input type="text" id="profile-phone" class="form-control" v-model="user.phone">
                            </div>
                        </form>
                        <div class="info" v-else>
                            <h4>Name</h4>
                            <p>@{{ user.name }}</p>
                            <h4>Email</h4>
                            <p>@{{ user.email }}</p>
                            <h4>Phone</h4>
                            <p v-if="user.phone">@{{ user.phone }}</p>
                            <em v-else>none</em>
                        </div>
                        <div class="edit-button-wrap">
                            <div class="start-edit" v-show="! editingContact">
                                <button type="button"
                                        class="btn btn-outline-blue"
                                @click="toggleEditMode('Contact')"
                                >
                                Edit Contact Details
                                </button>
                            </div>
                            <div class="submit-edit" v-else>
                                <button type="button" class="btn btn-outline-grey" @click="toggleEditMode('Contact')">
                                Cancel</button>
                                <button type="submit" class="btn btn-solid-blue" @click="updateProfile('Contact')">
                                Save</button>
                            </div>
                        </div>
                    </section>
                    <section>
                        <form @submit.prevent="updateProfile('Bio')" v-show="editingBio">
                            <div class="form-group">
                                <label for="profile-bio">Bio</label>
                                <textarea name="bio" id="profile-bio" class="form-control autosize"
                                          v-model="user.bio"></textarea>
                            </div>
                        </form>
                        <div class="info" v-else>
                            <h4>Bio</h4>
                            <p v-if="user.bio">@{{ user.bio }}</p>
                            <em v-else>none</em>
                        </div>
                        <div class="edit-button-wrap">
                            <div class="start-edit" v-show="! editingBio">
                                <button type="button"
                                        class="btn btn-outline-blue"
                                @click="toggleEditMode('Bio')"
                                >
                                Edit Bio
                                </button>
                            </div>
                            <div class="submit-edit" v-else>
                                <button type="button" class="btn btn-outline-grey" @click="toggleEditMode('Bio')">
                                Cancel</button>
                                <button type="submit" class="btn btn-solid-blue" @click="updateProfile('Bio')">
                                Save</button>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </user-profile>
@endsection