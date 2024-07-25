@extends('diy.layouts.master')
@section('content')
<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="container custom-container h-100">
        <div class="row justify-content-center align-items-center h-100">
            <div class="col-xxl-5 col-xl-5 col-lg-6 col-md-8 col-sm-11">
                <div>
                    <div class="login-wrapper">
                        <div class="login-wrapper__body">
                            <form action="{{ route('diy.login') }}" method="POST"
                                class="cmn-form mt-30 verify-gcaptcha login-form">
                                @csrf
                                <div class="form-group">
                                    <label>@lang('Username')</label>
                                    <input type="text" class="form-control" value="{{ old('username') }}" name="username" required>
                                </div>
                                <div class="form-group">
                                    <label>@lang('Password')</label>
                                    <input type="password" class="form-control" name="password" required>
                                </div>
                                <button type="submit" class="btn cmn-btn w-100">@lang('LOGIN')</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('style')
<style>
    .login-wrapper {
        background-color: #0d1e23 !important;
    }
    .cmn-btn {
        color: black !important;
        background-color: white !important;
    }
</style>
@endpush
