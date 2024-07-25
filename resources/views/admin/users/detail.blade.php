@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-12">
        <div class="row gy-4">
            <div class="col-xxl-3 col-sm-6">
                <div class="widget-two style--two box--shadow2 b-radius--5 bg--19">
                    <div class="widget-two__icon b-radius--5 bg--primary">
                        <i class="las la-money-bill-wave-alt"></i>
                    </div>
                    <div class="widget-two__content">
                        <h3 class="text-white">{{ getAmount($widget['total_trade']) }}</h3>
                        <p class="text-white">@lang('Total Order')</p>
                    </div>
                    <a href="{{ route('admin.order.history') }}?user_id={{ $user->id }}"
                        class="widget-two__btn">@lang('View All')</a>
                </div>
            </div>
            <div class="col-xxl-3 col-sm-6">
                <div class="widget-two style--two box--shadow2 b-radius--5 bg--primary">
                    <div class="widget-two__icon b-radius--5 bg--primary">
                        <i class="las la-wallet"></i>
                    </div>
                    <div class="widget-two__content">
                        <h3 class="text-white">{{ getAmount($widget['total_trade']) }}</h3>
                        <p class="text-white">@lang('Total Trdae')</p>
                    </div>
                    <a href="{{ route('admin.trade.history') }}?trader_id={{ $user->id }}"
                        class="widget-two__btn">@lang('View All')</a>
                </div>
            </div>
            <div class="col-xxl-3 col-sm-6">
                <div class="widget-two style--two box--shadow2 b-radius--5 bg--1">
                    <div class="widget-two__icon b-radius--5 bg--primary">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="widget-two__content">
                        <h3 class="text-white">{{ getAmount($widget['total_deposit']) }}</h3>
                        <p class="text-white">@lang('Total Deposit')</p>
                    </div>
                    <a href="{{ route('admin.deposit.list') }}?search={{ $user->username }}"
                        class="widget-two__btn">@lang('View All')</a>
                </div>
            </div>
            <div class="col-xxl-3 col-sm-6">
                <div class="widget-two style--two box--shadow2 b-radius--5 bg--17">
                    <div class="widget-two__icon b-radius--5 bg--primary">
                        <i class="las la-exchange-alt"></i>
                    </div>
                    <div class="widget-two__content">
                        <h3 class="text-white">{{ getAmount($widget['total_transaction']) }}</h3>
                        <p class="text-white">@lang('Transactions')</p>
                    </div>
                    <a href="{{ route('admin.report.transaction') }}?search={{ $user->username }}"
                        class="widget-two__btn">@lang('View All')</a>
                </div>
            </div>
        </div>

        <div class="row gy-4 mt-1">
            <div class="col">
                <div class="widget-two style--two box--shadow2 b-radius--5 bg--19">
                    <div class="widget-two__icon b-radius--5 bg--primary">
                        <i class="las la-wallet"></i>
                    </div>
                    <div class="widget-two__content">
                        <h3 class="text-white">{{ getAmount($marketCurrencyWallet->balance ?? 0) }}</h3>
                        <p class="text-white">@lang('Balance')</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="widget-two style--two box--shadow2 b-radius--5 bg--primary">
                    <div class="widget-two__icon b-radius--5 bg--primary">
                        <i class="las la-wallet"></i>
                    </div>
                    <div class="widget-two__content">
                        <h3 class="text-white admin-equity-val">0</h3>
                        <p class="text-white">@lang('Equity')</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="widget-two style--two box--shadow2 b-radius--5 bg--1">
                    <div class="widget-two__icon b-radius--5 bg--primary">
                        <i class="las la-wallet"></i>
                    </div>
                    <div class="widget-two__content">
                        <h3 class="text-white admin-pl-val">0</h3>
                        <p class="text-white">@lang('P/L')</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="widget-two style--two box--shadow2 b-radius--5 bg--17">
                    <div class="widget-two__icon b-radius--5 bg--primary">
                        <i class="las la-wallet"></i>
                    </div>
                    <div class="widget-two__content">
                        <h3 class="text-white admin-used-margin-val">{{ getAmount($requiredMarginTotal) }}</h3>
                        <p class="text-white">@lang('Used Margin')</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row gy-4 mt-1">
            <div class="col">
                <div class="widget-two style--two box--shadow2 b-radius--5 bg--17">
                    <div class="widget-two__icon b-radius--5 bg--primary">
                        <i class="las la-wallet"></i>
                    </div>
                    <div class="widget-two__content">
                        <h3 class="text-white admin-free-margin-val">0</h3>
                        <p class="text-white">@lang('Free Margin')</p>
                    </div>
                </div>
            </div>
            
            <div class="col">
                <div class="widget-two style--two box--shadow2 b-radius--5 bg--17">
                    <div class="widget-two__icon b-radius--5 bg--primary">
                        <i class="las la-wallet"></i>
                    </div>
                    <div class="widget-two__content">
                        <h3 class="text-white">{{ getAmount($marketCurrencyWallet->bonus ?? 0) }}</h3>
                        <p class="text-white">@lang('Bonus')</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="widget-two style--two box--shadow2 b-radius--5 bg--17">
                    <div class="widget-two__icon b-radius--5 bg--primary">
                        <i class="las la-wallet"></i>
                    </div>
                    <div class="widget-two__content">
                        <h3 class="text-white">{{ getAmount($marketCurrencyWallet->credit ?? 0) }}</h3>
                        <p class="text-white">@lang('Credit')</p>
                    </div>
                </div>
            </div>
            <div class="col"></div>
        </div>

        <div class="d-flex flex-wrap gap-3 mt-4">
            <div class="flex-fill">
                <button data-bs-toggle="modal" data-bs-target="#addSubModal"
                    class="btn btn--success btn--shadow w-100 btn-lg bal-btn" data-act="add"
                    {{can_access("add-remove-user-balance") ? "" : "disabled" }}>
                    <i class="las la-plus-circle"></i> @lang('Balance')
                </button>
            </div>

            <div class="flex-fill">
                <button data-bs-toggle="modal" data-bs-target="#addSubModal"
                    class="btn btn--danger btn--shadow w-100 btn-lg bal-btn" data-act="sub"
                    {{can_access("add-remove-user-balance") ? "" : "disabled" }}>
                    <i class="las la-minus-circle"></i> @lang('Balance')
                </button>
            </div>

            <div class="flex-fill">
                <a href="{{route('admin.report.login.history')}}?search={{ $user->username }}"
                    class="btn btn--primary btn--shadow w-100 btn-lg">
                    <i class="las la-list-alt"></i>@lang('Logins')
                </a>
            </div>

            <div class="flex-fill">
                <a href="{{ route('admin.users.notification.log',$user->id) }}"
                    class="btn btn--secondary btn--shadow w-100 btn-lg">
                    <i class="las la-bell"></i>@lang('Notifications')
                </a>
            </div>

            <div class="flex-fill">
                <a href="{{route('admin.users.login',$user->id)}}" target="_blank"
                    class="btn btn--primary btn--gradi btn--shadow w-100 btn-lg">
                    <i class="las la-sign-in-alt"></i>@lang('Login as User')
                </a>
            </div>

            @if($user->kyc_data)
            <div class="flex-fill">
                <a href="{{ route('admin.users.kyc.details', $user->id) }}" target="_blank"
                    class="btn btn--dark btn--shadow w-100 btn-lg">
                    <i class="las la-user-check"></i>@lang('KYC Data')
                </a>
            </div>
            @endif

            <div class="flex-fill">
                @if($user->status == Status::USER_ACTIVE)
                <button type="button" class="btn btn--warning btn--gradi btn--shadow w-100 btn-lg userStatus"
                    data-bs-toggle="modal" data-bs-target="#userStatusModal">
                    <i class="las la-ban"></i>@lang('Ban User')
                </button>
                @else
                <button type="button" class="btn btn--success btn--gradi btn--shadow w-100 btn-lg userStatus"
                    data-bs-toggle="modal" data-bs-target="#userStatusModal">
                    <i class="las la-undo"></i>@lang('Unban User')
                </button>
                @endif
            </div>
        </div>


        <div class="card mt-30">
            <div class="card-header">
                <h5 class="card-title mb-0">@lang('Information of') {{$user->fullname}}</h5>
            </div>
            <div class="card-body">
                <form action="{{route('admin.users.update',[$user->id])}}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group ">
                                <label>@lang('First Name')</label>
                                <input class="form-control" type="text" name="firstname" required
                                    value="{{$user->firstname}}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">@lang('Last Name')</label>
                                <input class="form-control" type="text" name="lastname" required
                                    value="{{$user->lastname}}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Password') </label>
                                <input class="form-control" type="password" name="password">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Email') </label>
                                <input class="form-control" type="email" name="email" value="{{$user->email}}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Mobile Number') </label>
                                <div class="input-group ">
                                    <span class="input-group-text mobile-code"></span>
                                    <input type="number" name="mobile" value="{{ old('mobile') }}" id="mobile"
                                        class="form-control checkUser" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Source') </label>
                                <input class="form-control" type="source" name="source" value="{{$user->source}}" >
                            </div>
                        </div>
                    </div>


                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="form-group ">
                                <label>@lang('Address')</label>
                                <input class="form-control" type="text" name="address"
                                    value="{{@$user->address->address}}">
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="form-group">
                                <label>@lang('City')</label>
                                <input class="form-control" type="text" name="city" value="{{@$user->address->city}}">
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="form-group ">
                                <label>@lang('State')</label>
                                <input class="form-control" type="text" name="state" value="{{@$user->address->state}}">
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="form-group ">
                                <label>@lang('Zip/Postal')</label>
                                <input class="form-control" type="text" name="zip" value="{{@$user->address->zip}}">
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="form-group ">
                                <label>@lang('Country')</label>
                                <select name="country" class="form-control">
                                    @foreach($countries as $key => $country)
                                    <option data-mobile_code="{{ $country->dial_code }}" value="{{ $key }}">{{
                                        __($country->country) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group  col-xl-12 col-md-12 col-12">
                            <label>@lang('Status')</label>
                            <select class="form-control" name="status" id="userStatusInline"
                                value="{{$user->sales_status}}">
                                <option disabled>@lang('Select One')</option>
                                @foreach (STATUS_OPTIONS as $status)
                                <option value="{{ $status }}" {{ $status==$user->sales_status ?
                                    'selected' : '' }}
                                    >{{
                                    $status }}</option>
                                @endforeach
                                <!-- <option value="NEW">NEW</option>
                                <option value="CALLBACK">CALLBACK</option>
                                <option value="NA">NA</option>
                                <option value="UNDER_AGE">UNDER_AGE</option>
                                <option value="DENY_REGISTRATION">DENY_REGISTRATION</option>
                                <option value="DEPOSIT">DEPOSIT</option>
                                <option value="NOT_INTERESTED">NOT_INTERESTED</option>
                                <option value="VOICE_MAIL">VOICE_MAIL</option> -->
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-xl-12 col-md-12 col-12">
                            <label for="userComment" class="col-form-label">Comment:</label>
                            <textarea class="form-control" placeholder="@lang('Comment')" name="comment" type="text"
                                rows="4" id="userComment"></textarea>
                        </div>
                        
                        <div class="form-group col-xl-12 col-md-12 col-12">
                            <label for="userComment" class="col-form-label">Comment History:</label>
                            
                            <div>
                                <ul class="list-group">
                                    @foreach ($user->comments->sortByDesc('created_at') as $comment)
                                        <li class="list-group-item">
                                            <div class="d-flex justify-content-between">
                                                <div class="flex-grow-1">
                                                    {{ $comment->comment ?? '-' }}
                                                </div>
                                                <div class="d-flex justify-content-end" style="width: 250px; flex-shrink: 0">
                                                    <small>
                                                        <i class="fas fa-user"></i>
                                                        {{ $comment?->commentor?->username }}
                                                    </small>
                                                    <small class="mx-2">
                                                        <i class="fas fa-calendar"></i>
                                                        {{ $comment->formatted_date }}
                                                    </small>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group  col-xl-3 col-md-6 col-12">
                            <label>@lang('Email Verification')</label>
                            <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                data-bs-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')"
                                name="ev" @if($user->ev) checked @endif>

                        </div>

                        <div class="form-group  col-xl-3 col-md-6 col-12">
                            <label>@lang('Mobile Verification')</label>
                            <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                data-bs-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')"
                                name="sv" @if($user->sv) checked @endif>

                        </div>
                        <div class="form-group col-xl-3 col-md- col-12">
                            <label>@lang('2FA Verification') </label>
                            <input type="checkbox" data-width="100%" data-height="50" data-onstyle="-success"
                                data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Enable')"
                                data-off="@lang('Disable')" name="ts" @if($user->ts) checked @endif>
                        </div>
                        <div class="form-group col-xl-3 col-md- col-12">
                            <label>@lang('KYC') </label>
                            <input type="checkbox" data-width="100%" data-height="50" data-onstyle="-success"
                                data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Verified')"
                                data-off="@lang('Unverified')" name="kv" @if($user->kv == 1) checked @endif>
                        </div>
                    </div>


                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')
                                </button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>

    </div>
</div>



{{-- Add Sub Balance MODAL --}}
@if(can_access("add-remove-user-balance"))
<div id="addSubModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><span class="type"></span> <span>@lang('Funds')</span></h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form action="{{route('admin.users.add.sub.balance',$user->id)}}" method="POST">
                @csrf
                <input type="hidden" name="act">
                <div class="modal-body">
                    <div class="form-group position-relative">
                        <label>@lang('Currency')</label>
                        <select name="wallet" class="form-control" required>
                            <option value="4" data-symbol="USD" selected>
                                @lang('USD')
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>@lang('Funds Type')</label>
                        <select class="form-control" name="wallet_type" required>
                            <option value="spot" selected>
                                @lang('Funds')
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>@lang('Balance')</label>
                        <div class="input-group">
                            <input type="number" step="any" name="amount" class="form-control"
                                placeholder="@lang('Please provide positive amount')">
                            <div class="input-group-text wallet-cur-symbol">{{ __($general->cur_text) }}</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>@lang('Bonus')</label>
                        <div class="input-group">
                            <input 
                                ype="number"
                                step="any"
                                name="bonus"
                                class="form-control"
                                placeholder="@lang('Please provide positive amount')"
                                >
                            <div class="input-group-text wallet-cur-symbol">{{ __($general->cur_text) }}</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>@lang('Credit')</label>
                        <div class="input-group">
                            <input
                                type="number"
                                step="any"
                                name="credit"
                                class="form-control"
                                placeholder="@lang('Please provide positive amount')"
                                >
                            <div class="input-group-text wallet-cur-symbol">{{ __($general->cur_text) }}</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>@lang('Remark')</label>
                        <textarea class="form-control" placeholder="@lang('Remark')" name="remark" rows="4"
                            required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@if(can_access("add-remove-user-balance"))
<div id="userStatusModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    @if($user->status == Status::USER_ACTIVE)
                    <span>@lang('Ban User')</span>
                    @else
                    <span>@lang('Unban User')</span>
                    @endif
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form action="{{route('admin.users.status',$user->id)}}" method="POST">
                @csrf
                <div class="modal-body">
                    @if($user->status == Status::USER_ACTIVE)
                    <h6 class="mb-2">@lang('If you ban this user he/she won\'t able to access his/her dashboard.')</h6>
                    <div class="form-group">
                        <label>@lang('Reason')</label>
                        <textarea class="form-control" name="reason" rows="4" required></textarea>
                    </div>
                    @else
                    <p><span>@lang('Ban reason was'):</span></p>
                    <p>{{ $user->ban_reason }}</p>
                    <h4 class="text-center mt-3">@lang('Are you sure to unban this user?')</h4>
                    @endif
                </div>
                <div class="modal-footer">
                    @if($user->status == Status::USER_ACTIVE)
                    <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                    @else
                    <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('No')</button>
                    <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@push('breadcrumb-plugins')
@if (request()->query('back') == 'leads')
    <a href="{{ route('admin.users.all', ['filter' => 'this_month']) }}" class="btn btn-outline--primary mx-2">
        <i class="las la-list"></i>@lang('Leads List')
    </a>
@else
    <a href="{{ route('admin.users.active', ['filter' => 'this_month']) }}" class="btn btn-outline--primary mx-2">
        <i class="las la-list"></i>@lang('Clients List')
    </a>
@endif
@if ($previousUser)
<a href="{{ route('admin.users.detail', $previousUser->id) }}" class="btn btn-outline--primary">
    <i class="las la-arrow-left"></i>
    @lang('Previous')
</a>
@endif
@if ($nextUser)
    <a href="{{ route('admin.users.detail', $nextUser->id) }}" class="btn btn-outline--primary">
        @lang('Next')
        <i class="las la-arrow-right"></i>
    </a>
@endif
@endpush

@push('script')
<script>
    (function ($) {
        "use strict";

        let equity = 0;
        let pl = 0;
        let total_open_order_profit = 0;
        let free_margin = 0;
        let total_amount = 0;
        let total_used_margin = 0;
    
        $('.bal-btn').click(function () {
            var act = $(this).data('act');
            $('#addSubModal').find('input[name=act]').val(act);
            if (act == 'add') {
                $('.type').text('Add');
            } else {
                $('.type').text('Subtract');
            }
        });

        let mobileElement = $('.mobile-code');

        $('select[name=country]').change(function () {
            mobileElement.text(`+${$('select[name=country] :selected').data('mobile_code')}`);
        });

        $('select[name=country]').val('{{@$user->country_code}}');

        let dialCode = $('select[name=country] :selected').data('mobile_code');
        let mobileNumber = `{{ $user->mobile }}`;

        mobileNumber = mobileNumber.replace(dialCode, '');
        $('input[name=mobile]').val(mobileNumber);
        mobileElement.text(`+${dialCode}`);

        $('select[name=wallet]').on('change', function (e) {
            let symbol = $(this).find('option:selected').data('symbol');
            $(`.wallet-cur-symbol`).text(symbol);
        });

        $('.select2').select2({
            dropdownParent: $(`.position-relative`)
        });

        function generateOrderRow(order, jsonData) {
            let current_price = jsonData[order.pair.symbol]
            let lotValue = order.pair.percent_charge_for_buy;
            
            let lotEquivalent = parseFloat(lotValue) * parseFloat(order.no_of_lot);
            let total_price = parseInt(order.order_side) === 2
                ? formatWithPrecision(((parseFloat(order.rate) - parseFloat(current_price)) * lotEquivalent))
                : formatWithPrecision(((parseFloat(current_price) - parseFloat(order.rate)) * lotEquivalent));
            total_open_order_profit = parseFloat(total_open_order_profit) + parseFloat(total_price);
            total_amount = parseFloat(total_amount) + parseFloat(formatWithPrecision1(order.amount));
        }

        function fetchOrderHistory() {
            let actionUrl = "{{ route('trade.order.list', ['pairSym' => @$pair->symbol ?? 'default_symbol', 'status' => 0 ]) }}";
            $.ajax({
                url: actionUrl,
                type: "GET",
                dataType: 'json',
                cache: false,
                data: {},
                success: function(resp) {
                    
                    let html = '';
                    equity = 0;
                    pl = 0;
                    total_open_order_profit = 0;
                    total_amount = 0;
                    let jsonMarketData = resp.marketData;
                    
                    if (resp.orders && resp.orders.length > 0) {
                        resp.orders.forEach(order => {
                            html += generateOrderRow(order, jsonMarketData[order.pair.type]);
                        });
                        
                        pl = total_open_order_profit;
                        equity = parseFloat({{ @$marketCurrencyWallet->balance }}) || 0 + pl;
                        
                    } else {
                        pl = 0;
                        equity = parseFloat({{ @$marketCurrencyWallet->balance }}) || 0;
                    }

                    let bonus = parseFloat({{ @$marketCurrencyWallet->bonus }}) || 0;
                    let credit = parseFloat({{ @$marketCurrencyWallet->credit }}) || 0;
            
                    free_margin = equity - Math.abs(pl) - resp.totalRequiredMargin;
                    document.querySelector(".admin-free-margin-val").innerText = `${formatWithPrecision1(free_margin + bonus + credit)} USD`;
                    
                    $('.admin-equity-val').html(`${formatWithPrecision1(equity + bonus + credit)} USD`);
                    $('.admin-pl-val').html(`${formatWithPrecision1(pl)} USD`);
                    
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching order history: ", error);
                }
            });
        }

        function formatWithPrecision(value, precision = 5) {
            return Number(value).toFixed(precision);
        }
        function formatWithPrecision1(value, precision = 2) {
            return Number(value).toFixed(precision);
        }

        setInterval(function func() { 
            fetchOrderHistory()
            return func; 
        }(), 1500); 
    })(jQuery);
</script>
@endpush