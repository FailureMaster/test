@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="trading-section bg-color" style="padding-top: 0px">
        <div >
            <div class="row mb-3" style="max-width: 100%; margin: 0 auto">
                <template>
                <x-flexible-view :view="$activeTemplate . 'trade.pair'" :meta="['pair' => $pair, 'screen' => 'small']" />
                </template>
                <div class="col-md-3 col-lg-2 col-xl-3 px-0" style="position: relative;">
                    <x-flexible-view :view="$activeTemplate . 'trade.coin_sync_list'" :meta="['pair' => $pair, 'screen' => 'small']" />
                    <!--<div class="trading-sidebar">-->
                    <!--    <x-flexible-view :view="$activeTemplate . 'trade.pair_list'" :meta="['markets' => $markets]" />-->
                    <!--    {{-- <x-flexible-view :view="$activeTemplate . 'trade.history'" :meta="['pair' => $pair]" /> --}}-->
                    <!--</div>-->
                </div>
                <div class="col-md-9 col-lg-10 col-xl-9">
                    <div class="row gy-2">
                        <div class="col-xl-10 col-md-9 mt-1 px-1">
                            {{-- <x-flexible-view :view="$activeTemplate . 'trade.pair'" :meta="['pair' => $pair]" /> --}}
                            <x-flexible-view :view="$activeTemplate . 'trade.tab'"  :meta="['screen' => 'small', 'markets' => $markets, 'pair' => $pair]" />
                            <div class="d-none d-md-block d-xl-none">
                                <x-flexible-view :view="$activeTemplate . 'trade.tab'" :meta="['screen' => 'medium', 'markets' => $markets, 'pair' => $pair]" />
                            </div>
                        </div>
                      
                        <div class="col-xl-2 col-md-3" style="position: relative;">
                            <x-flexible-view :view="$activeTemplate . 'trade.buy_sell'" :meta="[
                                'pair' => $pair,
                                'marketCurrencyWallet' => $marketCurrencyWallet,
                                'coinWallet' => $coinWallet,
                                'screen' => 'big',
                                'order_count' => $order_count,
                            ]" />
                            {{-- <x-flexible-view :view="$activeTemplate . 'trade.order_book'" :meta="['pair' => $pair, 'screen' => 'big']" /> --}}
                        </div>
                        <div class="col-md-5 d-xl-none d-block p-0">
                            <x-flexible-view :view="$activeTemplate . 'trade.buy_sell'" :meta="[
                                'pair' => $pair,
                                'marketCurrencyWallet' => $marketCurrencyWallet,
                                'coinWallet' => $coinWallet,
                                'screen' => 'medium',
                                'order_count' => $order_count,
                            ]" />
                        </div>
                    </div>
                    <div class="row gy-2.5">
                        <div class="col-sm-12 mt-0 px-1">
                            <x-flexible-view :view="$activeTemplate . 'trade.trade_order_history'" :meta="['pair' => $pair, 'markets' => $markets, 'order_count' => $order_count, 'marketCurrencyWallet' => $marketCurrencyWallet, 'requiredMarginTotal' => $requiredMarginTotal]" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="trading-mobile">
        <x-flexible-view
            :view="$activeTemplate . 'trade.trading_mobile'" 
            :meta="['screen' => 'small', 'markets' => $markets, 'pair' => $pair, 'marketCurrencyWallet' => $marketCurrencyWallet, 'coinWallet' => $coinWallet, 'order_count' => $order_count]"
            />
    </div>
    
    <x-confirmation-modal isCustom="true" />
    <x-stop-loss-modal/>
    <x-take-profit-modal/>

    <div class="offcanvas offcanvas-end p-5" tabindex="-1" id="deposit-canvas" aria-labelledby="offcanvasLabel">
        <div class="offcanvas-header">
            <span class="fs-18">
                @lang('Deposit Money')
            </span>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="fa fa-times-circle"></i>
            </button>
        </div>
        <div class="offcanvas-body">
            @auth
            <form action="{{ route('user.deposit.insert') }}" method="post">
                @csrf
                <input type="hidden" name="currency" class="deposit-currency-symbol">
                <input type="hidden" value="spot" name="wallet_type">
                <div class="form-group">
                    <label class="form-label">@lang('Amount')</label>
                    <div class="input-group">
                        <input type="number" step="any" class="form--control form-control" name="amount" required>
                        <span class="input-group-text deposit-currency-symbol"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">@lang('Payment Gateway')</label>
                    <select class="form-control form--control form-select" name="gateway" required>
                    </select>
                </div>
                <div class="form-group preview-details d-none">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex flex-wrap justify-content-between">
                            <span>@lang('Limit')</span>
                            <span>
                                <span class="min fw-bold">0</span>
                                - <span class="max fw-bold">0</span>
                                <span class="deposit-currency-symbol"></span>
                            </span>
                        </li>
                        <li class="list-group-item d-flex flex-wrap justify-content-between">
                            <span>@lang('Charge')</span>
                            <span>
                                <span class="charge fw-bold">0</span>
                                <span class="deposit-currency-symbol"></span>
                            </span>
                        </li>
                        <li class="list-group-item d-flex flex-wrap justify-content-between">
                            <span> @lang('Payable')</span>
                            <span>
                                <span class="payable fw-bold">0</span>
                                <span class="deposit-currency-symbol"></span>
                            </span>
                        </li>
                    </ul>
                </div>
                <button class="deposit__button btn btn--base w-100" type="submit"> @lang('Submit') </button>
            </form>
            <div class="p-5 text-center empty-gateway">
                <img src="{{ asset('assets/images/extra_images/no_money.png') }}" alt="">
                <span class="mt-3 fs-14">
                    @lang('No payment gateway available for ')
                    <span class="text--base deposit-currency-symbol"></span>
                    @lang('Currency')
                </span>
            </div>
            @else
            <div class="p-5 text-center d-flex flex-column align-items-center justify-content-center h-100">
                <img src="{{ asset('assets/images/extra_images/user.png') }}">
                <span class="fs-12">@lang('Login required for depsoit money')</span>
                <div class="mt-3">
                    <a class="fs-12 text--base" href="{{ route('user.login') }}">@lang('Login')</a>
                    <span>@lang('or')</span>
                    <a class="fs-12 text--base" href="{{ route('user.register') }}">@lang('Register')</a>
                </div>
            </div>
            @endauth
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/global/js/pusher.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/broadcasting.js') }}"></script>
    <script src="{{ asset('assets/global/js/iziToast.min.js') }}"></script>
    <script>
        "use strict";
        function toastr(status, message, position = "topRight") {
            if (typeof message == 'string') {
                iziToast[status]({
                    message: message,
                    position,
                    displayMode: 1
                });
            } else {
                $.each(message, function(i, val) {
                    iziToast[status]({
                        message: val,
                        position,
                        displayMode: 1
                    });
                });
            }
        }
    </script>
@endpush

@push('script')
    <script>
        "use strict";

        $('.new--deposit').on('click', function(e) {

            @auth
            let currency         = $(this).data('currency');
            let gateways         = @json($gateways);
            let currencyGateways = gateways.filter(ele => ele.currency == currency);

            if(currencyGateways && currencyGateways.length > 0){
                let gatewaysOption   = "<option selected disabled> @lang('Select Payment Gateway')</option>";
                $.each(currencyGateways, function(i, currencyGateway) {
                    gatewaysOption +=`<option value="${currencyGateway.method_code}"  data-gateway='${JSON.stringify(currencyGateway)}'>
                            ${currencyGateway.name}
                        </option>`;
                });
                $("#deposit-canvas").find('select[name=gateway]').html(gatewaysOption);
                $("#deposit-canvas").find('.deposit-currency-symbol').val(currency);

                $("#deposit-canvas").find(".empty-gateway").addClass('d-none');
                $("#deposit-canvas").find("form").removeClass('d-none');
            }else{
                $("#deposit-canvas").find(".empty-gateway").removeClass('d-none');
                $("#deposit-canvas").find("form").addClass('d-none');
            }
            $("#deposit-canvas").find('.deposit-currency-symbol').text(currency);
            @endauth
            var myOffcanvas = document.getElementById('deposit-canvas');
            var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas).show();
        });

        @auth
        $('#deposit-canvas').on('change', 'select[name=gateway]', function() {

            if (!$(this).val()) {
                $('#deposit-canvas .preview-details').addClass('d-none');
                return false; 
            }

            var resource       = $('select[name=gateway] option:selected').data('gateway');
            var fixed_charge   = parseFloat(resource.fixed_charge);
            var percent_charge = parseFloat(resource.percent_charge);
            var rate           = parseFloat(resource.rate);
            var amount         = parseFloat($('#deposit-canvas input[name=amount]').val());

            $('#deposit-canvas .min').text(getAmount(resource.min_amount));
            $('#deposit-canvas .max').text(getAmount(resource.max_amount));


            if (!amount) {
                $('#deposit-canvas .preview-details').addClass('d-none');
                return false;
            }

            $('#deposit-canvas .preview-details').removeClass('d-none');

            var charge    = parseFloat(fixed_charge + (amount * percent_charge / 100));
            var payable   = parseFloat((parseFloat(amount) + parseFloat(charge)));
            var final_amo = (parseFloat((parseFloat(amount) + parseFloat(charge))) * rate);


            $("#deposit-canvas").find(".empty-gateway").addClass('d-none');
            $("#deposit-canvas").find("form").removeClass('d-none');

            $('#deposit-canvas .charge').text(getAmount(charge));
            $('#deposit-canvas .payable').text(getAmount(payable));
            $('#deposit-canvas .final_amo').text(getAmount(final_amo));

            $('#deposit-canvas .method_currency').text(resource.currency);
            $('#deposit-canvas input[name=amount]').on('input');

        });

        $('#deposit-canvas').on('input', 'input[name=amount]', function() {
            var data = $('#deposit-canvas select[name=gateway]').change();
            $('#deposit-canvas .amount').text(parseFloat($(this).val()).toFixed(2));
        });
        @endauth

        pusherConnection('market-data', marketChangeHtml);

        var swiper = new Swiper(".myswiper-two", {
            slidesPerView: 5,
            spaceBetween: 0,
            navigation: {
                nextEl: ".swiper-button-next-two",
                prevEl: ".swiper-button-prev-two",
            },
            breakpoints: {
                575: {
                    slidesPerView: 6,
                    spaceBetween: 0,
                },
                992: {
                    slidesPerView:5,
                    spaceBetween: 0,
                },
            },
        });

        window.visit_pair = {
            selection: "{{ @$pair->marketData->id }}",
            symbol   : "{{ @$pair->symbol }}",
            site_name: "{{ __($general->site_name) }}"
        };

        $('header').find(`.container`).addClass(`custom--container`);
    </script>
@endpush


@push('style')
    <style>
        .cookies-card {
            background-color: #181d20 !important;
            color: #93988f !important;
        }
        .has-mega-menu .mega-menu{
            background: #181d20 !important;
        }
        
        @media screen and (min-width: 575px) {
            .trading-mobile {
                display: none;
            }
        }
    </style>
@endpush
