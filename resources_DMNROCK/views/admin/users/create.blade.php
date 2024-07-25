@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-body p-0">
                <form action="{{ route('admin.users.store') }}" method="POST"
                    enctype="multipart/form-data" class="pair-form">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label>@lang('Firstname')</label>
                                <div class="input-group">
                                    <input class="form-control" type="text" name="firstname" required>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label>@lang('Lastname')</label>
                                <div class="input-group">
                                    <input class="form-control" type="text" name="lastname" required>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label>@lang('Email')</label>
                                <div class="input-group">
                                    <input class="form-control" type="email" name="email" required>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form--label">@lang('Country')</label>
                                <select name="country" class="form-control register-select">
                                    @foreach($countries as $key => $country)
                                    <option data-mobile_code="{{ $country->dial_code }}"
                                        value="{{ $country->country }}" data-code="{{ $key }}">{{__($country->country) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-6 style">
                                <label class="form--label">@lang('Mobile')</label>
                                <div class="input-group">
                                    <div class="input-group-text mobile-code"></div>
                                    <input type="hidden" name="mobile_code">
                                    <input type="hidden" name="country_code">
                                    <input type="number" placeholder="@lang('Your mobile')" name="mobile" value="{{ old('mobile') }}"  class="form-control form--control checkUser" required>
                                </div>
                                <small class="text--danger mobileExist"></small>
                            </div>

                        <button type="submit" class="btn btn--primary w-100 h-45 ">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
<a href="{{ route('admin.users.all') }}" class="btn btn-outline--primary btn-sm">
    <i class="las la-list"></i>@lang('Leads List')
</a>
@endpush
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
@push('style')
<style>
    .select2-container {
        z-index: 99 !important;
    }
</style>
@endpush