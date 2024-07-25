@extends($activeTemplate.'layouts.app')
@section('main-content')

@php
$content     = getContent('login.content',true);
$policyPages = getContent('policy_pages.element',false,null,true);
$langDetails = $languages->where('code', config('app.locale'))->first();
$credentials = $general->socialite_credentials;
@endphp

<section class="account">
    <div class="account-inner">
        <div class="account-left">
            <a href="{{ route('home') }}" class="account-left__logo">
                <img src="{{getImage(getFilePath('logoIcon') .'/logo_base.png')}}">
            </a>
            <div class="account-left__thumb">
                <img src="{{ getImage('assets/images/frontend/login/'.@$content->data_values->image,'600x600') }}">
            </div>
        </div>
        <div class="account-right-wrapper position-relative">
            <div class="account-content__top">
                <div class="account-content__member @if(!is_mobile()) gap-2 @endif">
                    <p class="account-content__member-text"> @lang("Don't have an account")? </p>
                    <a href="{{ route('user.register') }}" class="account-link">@lang('Sign Up')</a>
                    @if ($general->multi_language)
                        <div class="custom--dropdown">
                            <div class="custom--dropdown__selected dropdown-list__item">
                                <div class="thumb">
                                    <img src="{{getImage(getFilePath('language') . '/' . @$langDetails->flag, getFileSize('language')) }}">
                                </div>
                                <span class="text text-uppercase">{{ __(@$langDetails->code) }}</span>
                            </div>
                            <ul class="dropdown-list" style="width: 100px">
                                @foreach ($languages as $language)
                                <li class="dropdown-list__item change-lang " data-code="{{ @$language->code }}">
                                    <div class="thumb">
                                        <img src="{{ getImage(getFilePath('language') . '/' . @$language->flag, getFileSize('language')) }}">
                                    </div>
                                    <span class="text text-uppercase">{{ __(@$language->code) }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="theme-switch-wrapper">
                        <label class="theme-switch" for="checkbox">
                            <input type="checkbox" class="d-none" id="checkbox">
                            <span class="slider">
                                <i class="las la-sun"></i>
                            </span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="account-right">
                <div class="account-content">
                    <div class="account-form">
                        <h3 class="account-form__title mb-0 @if(App::getLocale() == 'ar') text-end @endif">{{ __(@$content->data_values->heading_two) }}</h3>
                        <p class="account-form__desc @if(App::getLocale() == 'ar') text-end @endif">{{ __(@$content->data_values->subheading_two)}}</p>

                        <x-flexible-view :view="$activeTemplate . 'user.auth.social_provider.index'" action="login"/>
                        <x-flexible-view :view="$activeTemplate . 'user.auth.web3.index'" action="login"/>

                        @if (@$credentials->linkedin->status || @$credentials->facebook->status == Status::ENABLE || @$credentials->google->status == Status::ENABLE || $general->metamask_login)
                        <div class="other-option">
                            <span class="other-option__text">@lang('OR')</span>
                        </div>
                        @endif

                        <form method="POST" action="{{ route('user.login') }}" class="verify-gcaptcha">
                            @csrf
                            <div class="form-group @if(App::getLocale() == 'ar') text-end @endif">
                                <label class="form--label">@lang('Email')</label>
                                <input type="text" name="username" value="{{ old('username') }}" class="form--control" placeholder="@lang('Enter your email')" autocomplete="off" autocorrect="off" spellcheck="false">
                            </div>
                            <div class="form-group">
                                <div class="d-flex justify-content-between @if(App::getLocale() == 'ar') flex-row-reverse @endif">
                                    <label class="form--label">@lang('Password')</label>
                                    <a href="{{ route('user.password.request') }}" class="forget-password">@lang('Forgot Password?')</a>
                                </div>
                                <div class="position-relative">
                                    <input name="password" type="password" class="form--control" placeholder="@lang('Enter your password')" autocomplete="off" autocorrect="off" spellcheck="false">
                                    <div class="password-show-hide far fa-eye toggle-password fa-eye-slash" id="#toogle-password"></div>
                                </div>
                            </div>
                            <x-captcha isCustom="true" />
                            <div class="form-group form-check d-flex justify-content-start @if(App::getLocale() == 'ar') justify-content-end @endif">
                                <div>
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        @lang('Remember Me')
                                    </label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn--base w-100" style="background-color: hsl(var(--auth-base)) !important; border: 1px solid hsl(var(--auth-base)) !important">@lang('Log In')</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row mt-auto position-absolute bottom-0 w-100 px-4 mb-2">
                <div class="col-md-6">
                    <div class="bottom-footer__text" style="color: hsl(var(--black))">
                        @php echo copyRightText(); @endphp
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bottom-footer__right">
                        <span class="bottom-footer__right-text">
                            @foreach($policyPages as $policy)
                            <a class="bottom-footer__right-link" href="{{ route('policy.pages',[slug($policy->data_values->title),$policy->id]) }}" target="_blank">
                                {{ __(@$policy->data_values->title) }}
                            </a>
                            @endforeach
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('script')
<style>
input:-webkit-autofill {
    background-color: transparent !important;
    -webkit-box-shadow: 0 0 0 1000px transparent inset !important;
    box-shadow: 0 0 0 1000px transparent inset !important;
    color: inherit !important;
}
</style>
@endpush

