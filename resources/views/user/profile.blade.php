@extends('layouts.admin')
@php
$profile=asset(Storage::url('uploads/avatar/'));
@endphp
@section('page-title')
{{__('Profile Account')}}
@endsection
@push('script-page')
<script>
    var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300,
        })
        $(".list-group-item").click(function(){
            $('.list-group-item').filter(function(){
                return this.href == id;
            }).parent().removeClass('text-primary');
        });
</script>
@endpush
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
<li class="breadcrumb-item">{{__('Profile')}}</li>
@endsection
@section('content')
<div class="row">
    <div class="col-xl-3">
        <div class="card sticky-top" style="top:30px">
            <div class="list-group list-group-flush" id="useradd-sidenav">
                <a href="#personal_info" class="list-group-item list-group-item-action">{{__('Personal Info')}} <div
                        class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                <a href="#change_password" class="list-group-item list-group-item-action">{{__('Change Password')}}<div
                        class="float-end"><i class="ti ti-chevron-right"></i></div></a>
            </div>
        </div>
    </div>
    <div class="col-xl-9">
        <div id="personal_info" class="card">
            <div class="card-header">
                <h5>{{('Personal Info')}}</h5>
            </div>
            <div class="card-body">
                {{Form::model($userDetail,array('route' => array('update.account'), 'method' => 'post', 'enctype' =>
                "multipart/form-data"))}}
                @csrf
                <div class="row">
                    <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label text-dark">{{__('Name')}}</label>
                            <input class="form-control @error('name') is-invalid @enderror" name="name" type="text"
                                id="name" placeholder="{{ __('Enter Your Name') }}" value="{{ $userDetail->name }}"
                                required autocomplete="name">
                            @error('name')
                            <span class="invalid-feedback text-danger text-xs" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                            <label for="email" class="col-form-label text-dark">{{__('Email')}}</label>
                            <input class="form-control @error('email') is-invalid @enderror" name="email" type="text"
                                id="email" placeholder="{{ __('Enter Your Email Address') }}"
                                value="{{ $userDetail->email }}" required autocomplete="email">
                            @error('email')
                            <span class="invalid-feedback text-danger text-xs" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="form-group">
                            <label for="email" class="col-form-label text-dark">{{__('Avtar')}}</label>
                            <div class="choose-files">
                                <label for="avatar">
                                    <div class=" bg-primary profile_update"> <i
                                            class="ti ti-upload px-1"></i>{{__('Choose file here')}}</div>
                                    <input type="file" name="profile" id="avatar" class="form-control file "
                                        onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])"
                                        data-multiple-caption="{count} files selected" multiple />
                                    <img id="blah" width="25%" />

                                    <!-- <input type="file" class="form-control file" name="profile" id="avatar" data-filename="profile_update"> -->
                                </label>
                            </div>
                            <span class="text-xs text-muted">{{ __('Please upload a valid image file. Size of image
                                should not be more than 2MB.')}}</span>

                            @error('avatar')
                            <span class="invalid-feedback text-danger text-xs" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-12 text-end">
                        <input type="submit" value="{{__('Save Changes')}}"
                            class="btn btn-print-invoice  btn-primary m-r-10">
                    </div>
                </div>
                </form>
            </div>
        </div>
        <div id="change_password" class="card">
            <div class="card-header">
                <h5>{{('Change Password')}}</h5>
            </div>
            <div class="card-body">
                <form method="post" action="{{route('update.password')}}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 form-group">
                            <label for="current_password" class="col-form-label text-dark">{{ __('Old Password')
                                }}</label>
                            <input class="form-control @error('current_password') is-invalid @enderror"
                                name="current_password" type="password" id="current_password" required
                                autocomplete="current_password">
                            @error('current_password')
                            <span class="invalid-feedback text-danger text-xs" role="alert">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-lg-6 col-sm-6 form-group">
                            <label for="new_password" class="col-form-label text-dark">{{ __('New Password') }}</label>
                            <input class="form-control @error('password') is-invalid @enderror" name="new_password"
                                type="password" required autocomplete="new-password" id="new_password">
                            @error('new_password')
                            <span class="invalid-feedback text-danger text-xs" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-lg-6 col-sm-6 form-group">
                            <label for="confirm_password" class="col-form-label text-dark">{{ __('Confirm New Password')
                                }}</label>
                            <input class="form-control @error('confirm_password') is-invalid @enderror"
                                name="confirm_password" type="password" required autocomplete="new-password"
                                id="confirm_password">
                        </div>
                        <div class="col-lg-12 text-end">
                            <input type="submit" value="{{__('Save Changes')}}"
                                class="btn btn-print-invoice  btn-primary m-r-10">
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
    @endsection