@extends($activeTemplate.'layouts.app')
@section('main-content')
@php
$content     = getContent('register.content',true);
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
            <div class="account-left__thumb-two">
                <img src="{{ getImage('assets/images/frontend/register/'.@$content->data_values->image,'600x600') }}">
            </div>
        </div>
        <div class="account-right-wrapper">
            <div class="account-content__top">
                <div class="account-content__member @if(!is_mobile()) gap-2 @endif">
                    <p class="account-content__member-text"> @lang('Already have an account? ') </p>
                    <a href="{{ route('user.login') }}" class="account-link"> @lang('Sign In') </a>
                    @if ($general->multi_language)
                    <div class="custom--dropdown">
                        <div class="custom--dropdown__selected dropdown-list__item">
                            <div class="thumb">
                                <img src="{{ getImage(getFilePath('language') . '/' . @$langDetails->flag, getFileSize('language')) }}">
                            </div>
                            <span class="text text-uppercase">{{ __(@$langDetails->code) }}</span>
                        </div>
                        <ul class="dropdown-list" style="width: 100px">
                            @foreach ($languages as $language)
                                <li class="dropdown-list__item change-lang " data-code="{{ @$language->code }}">
                                    <div class="thumb">
                                        <img src="{{ getImage(getFilePath('language') . '/' . @$language->flag, getFileSize('language')) }}">
                                    </div>
                                    <span class="text">{{ __(@$language->name) }}</span>
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
                        <h3 class="account-form__title mb-0 @if(App::getLocale() == 'ar') text-end @endif"> {{ __(@$content->data_values->heading_two)}}</h3>
                        <p class="account-form__desc @if(App::getLocale() == 'ar') text-end @endif">{{ __(@$content->data_values->subheading_two)}}</p>

                        <x-flexible-view :view="$activeTemplate . 'user.auth.social_provider.index'" action="register"/>
                        <x-flexible-view :view="$activeTemplate . 'user.auth.web3.index'" action="register"/>

                        @if (@$credentials->linkedin->status || @$credentials->facebook->status == Status::ENABLE || @$credentials->google->status == Status::ENABLE || $general->metamask_login)
                        <div class="other-option">
                            <span class="other-option__text">@lang('OR')</span>
                        </div>
                        @endif

                        <form action="{{ route('user.register') }}" method="POST" class="verify-gcaptcha">
                            @csrf
                            <div class="row">
                                @if(session()->get('reference') != null)
                                <div class="col-md-12">
                                    <div class="form-group @if(App::getLocale() == 'ar') text-end @endif">
                                        <label for="referenceBy" class="form--label">@lang('Reference by')</label>
                                        <input type="text" name="referBy" id="referenceBy" class="form--control @if(App::getLocale() == 'ar') text-end @endif" value="{{session()->get('reference')}}"  readonly>
                                    </div>
                                </div>
                                @endif
                                <div class="col-md-6">
                                    <div class="form-group @if(App::getLocale() == 'ar') text-end @endif">
                                        <label class="form--label">@lang('Firstname')</label>
                                        <input type="text" class="form--control checkUser @if(App::getLocale() == 'ar') text-end @endif" name="firstname" value="{{ old('firstname') }}" required placeholder="@lang('Your firstname')" autocomplete="off">
                                        <small class="text--danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group @if(App::getLocale() == 'ar') text-end @endif">
                                        <label class="form--label">@lang('Lastname')</label>
                                        <input type="text" class="form--control checkUser @if(App::getLocale() == 'ar') text-end @endif" name="lastname" value="{{ old('lastname') }}" required placeholder="@lang('Your lastname')" autocomplete="off">
                                        <small class="text--danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group @if(App::getLocale() == 'ar') text-end @endif">
                                        <label class="form--label">@lang('E-Mail Address')</label>
                                        <input type="email" class="form--control checkUser @if(App::getLocale() == 'ar') text-end @endif" placeholder="@lang('Your email')" name="email"  value="{{ old('email') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group @if(App::getLocale() == 'ar') text-end @endif">
                                        <label class="form--label">@lang('Country')</label>
                                        <select name="country" class="form--control register-select">
                                            @foreach($countries as $key => $country)
                                            <option data-mobile_code="{{ $country->dial_code }}"
                                                value="{{ $country->country }}" data-code="{{ $key }}">{{__($country->country) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 form-group style @if(App::getLocale() == 'ar') text-end @endif">
                                     <label class="form--label">@lang('Mobile')</label>
                                    <div class="input-group">
                                        <div class="input-group-text mobile-code"></div>
                                        <input type="hidden" name="mobile_code">
                                        <input type="hidden" name="country_code">
                                        <input type="number" placeholder="@lang('Your mobile')" name="mobile" value="{{ old('mobile') }}"  class="form-control form--control checkUser @if(App::getLocale() == 'ar') text-end @endif" required>
                                    </div>
                                    <small class="text--danger mobileExist"></small>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group @if(App::getLocale() == 'ar') text-end @endif">
                                        <label class="form--label">@lang('Password')</label>
                                        <input type="password" class="form--control @if($general->secure_password) secure-password @endif @if(App::getLocale() == 'ar') text-end @endif" name="password" placeholder="@lang('Your password')" required autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group @if(App::getLocale() == 'ar') text-end @endif">
                                        <label class="form--label">@lang('Confirm Password')</label>
                                        <input type="password" class="form--control @if(App::getLocale() == 'ar') text-end @endif" name="password_confirmation" placeholder="@lang('Password Confirmation')" required>
                                    </div>
                                </div>
                                <x-captcha isCustom="true" />
                            </div>
                            @if($general->agree)
                            <div class="form-group">
                                <input type="checkbox" id="agree" @checked(old('agree')) name="agree" required>
                                <label for="agree">@lang('I agree with')</label>
                                <span>
                                    @foreach($policyPages as $policy) <a class="text--base" href="{{ route('policy.pages',[slug($policy->data_values->title),$policy->id]) }}"
                                        target="_blank">{{ __($policy->data_values->title) }}</a> @if(!$loop->last), @endif
                                    @endforeach
                                </span>
                            </div>
                            @endif
                            <button type="submit" id="recaptcha" class="btn btn--base w-100" style="background-color: hsl(var(--auth-base)) !important; border: 1px solid hsl(var(--auth-base)) !important"> @lang('Register')</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row gy-3 mt-auto">
                <div class="col-md-6">
                    <div class="bottom-footer__text" style="color: hsl(var(--black))"> @php echo copyRightText(); @endphp</div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade custom--modal" id="existModalCenter" tabindex="-1" role="dialog" aria-labelledby="existModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="existModalLongTitle">@lang('You are with us')</h5>
                <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </span>
            </div>
            <div class="modal-body">
                <h6 class="text-center">@lang('You already have an account please Login ')</h6>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn--dark btn--sm" data-bs-dismiss="modal">@lang('Close')</button>
                <a href="{{ route('user.login') }}" class="btn btn--base btn--sm">@lang('Login')</a>
            </div>
        </div>
    </div>
</div>
@endsection
@push('style')
<style>
    .country-code .input-group-text {
        background: #fff !important;
    }

    .country-code select {
        border: none;
    }

    .country-code select:focus {
        border: none;
        outline: none;
    }
</style>
@endpush
@if($general->secure_password)
@push('script-lib')
<script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
@endpush
@endif
@push('script')
<script>
    "use strict";
    (function ($) {
        @if ($mobileCode)
            $(`option[data-code={{ $mobileCode }}]`).attr('selected', '');
        @endif

        $('select[name=country]').change(function () {
            $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
            $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
            $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));
        });
        $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
        $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
        $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));

        $('.checkUser').on('focusout', function (e) {
            var url = '{{ route('user.checkUser') }}';
            var value = $(this).val();
            var token = '{{ csrf_token() }}';
            if ($(this).attr('name') == 'mobile') {
                var mobile = `${$('.mobile-code').text().substr(1)}${value}`;
                var data = { mobile: mobile, _token: token }
            }
            if ($(this).attr('name') == 'email') {
                var data = { email: value, _token: token }
            }
            if ($(this).attr('name') == 'username') {
                var data = { username: value, _token: token }
            }
            $.post(url, data, function (response) {
                if (response.data != false && response.type == 'email') {
                    $('#existModalCenter').modal('show');
                } else if (response.data != false) {
                    $(`.${response.type}Exist`).text(`${response.type} already exist`);
                } else {
                    $(`.${response.type}Exist`).text('');
                }
            });
        });

    })(jQuery);
</script>

@endpush


