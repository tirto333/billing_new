@extends('layouts.auth')
@php
$logo=asset(Storage::url('uploads/logo/'));
$company_logo=\App\Models\Utility::getValByName('company_logo');
$setting=\App\Models\Utility::settings();
@endphp
@section('page-title')
{{__('Login')}}
@endsection
@section('content')
<div class="">
    <h2 class="mb-3 f-w-600">{{__('Login')}}</h2>
</div>
{{-- @if ($errors->any())
@foreach ($errors->all() as $error)
<span class="text-danger">{{$error}}</span>
@endforeach
@endif --}}
{{Form::open(array('route'=>'login','method'=>'post','id'=>'loginForm' ))}}
@csrf
<div class="">
    <div class="form-group mb-3">
        <label for="email" class="form-label">{{__('Email')}}</label>
        <input class="form-control @error('email') is-invalid @enderror" id="email" type="email" name="email"
            value="{{ old('email') }}" required autocomplete="email" autofocus>
        @error('email')
        <div class="invalid-feedback" role="alert">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group mb-3">
        <label for="password" class="form-label">{{__('Password')}}</label>
        <input class="form-control @error('password') is-invalid @enderror" id="password" type="password"
            name="password" required autocomplete="current-password">
        @error('password')
        <div class="invalid-feedback" role="alert">{{ $message }}</div>
        @enderror

    </div>

    @if(env('RECAPTCHA_MODULE') == 'yes')
    <div class="form-group mb-3">
        {!! NoCaptcha::display() !!}
        @error('g-recaptcha-response')
        <span class="small text-danger" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    @endif
    <div class="form-group mb-4">
        @if (Route::has('password.request'))
        <a href="{{ route('password.request') }}" class="text-xs">{{ __('Forgot Your Password?') }}</a>
        @endif
    </div>
    <div class="d-grid">
        <button type="submit" class="btn btn-primary btn-block mt-2" id="login_button">{{__('Login')}}</button>

    </div>
    {{-- @if($settings['enable_signup'] == 'on')
    <p class="my-4 text-center">{{__("Don't have an account?")}} <a href="{{ route('register') }}"
            class="text-primary">{{__('Register')}}</a></p>
    @endif --}}

    <div class="row">
        <div class="col-sm-6">
            <a href="{{ route('customer.login') }}"
                class="btn-login btn btn-primary btn-block mt-2 text-white">{{__('Customer Login')}}</a>
        </div>
        <div class="col-sm-6 text-end">
            <a href="" class="btn-login btn btn-primary btn-block mt-2 text-white">{{__('Vendor
                Login')}}</a>
        </div>
    </div>

</div>
{{Form::close()}}
@endsection