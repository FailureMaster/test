@php
    $meta                 = (object) $meta;
    $pair                 = @$meta->pair;
    $marketCurrencyWallet = @$meta->marketCurrencyWallet;
    $coinWallet           = @$meta->coinWallet;
    $screen               = @$meta->screen;
    $order_count          = @$meta->order_count;
@endphp
@if (@$meta->screen != 'small')
    <div class="trading-bottom pt-0 @if (@$meta->screen == 'medium') two @else d-xl-block d-none @endif" style="position: absolute; top: 0; bottom: 0; right: 0; left: 0;">
        <div class="tab-content">
            <div class="tab-pane fade show active" role="tabpanel">
                <div class="order-wrapper">
                    <x-flexible-view :view="$activeTemplate . 'trade.buy_form'"  :meta="['pair' => $pair,'marketCurrencyWallet' => $marketCurrencyWallet,'screen' => 'big', 'order_count' => $order_count]" />
                    <x-flexible-view :view="$activeTemplate . 'trade.sell_form'" :meta="['pair' => $pair,'coinWallet' => $coinWallet,'screen' => 'big']" />
                </div>
            </div>
        </div>
    </div>

@else
    <div class="trading-bottom__fixed">
        <div class="trading-bottom__footer d-flex">
            <div class="trading-bottom__button buy-btn">
                <button class="btn btn--base-two w-100 btn--sm buy-btn-sm"> @lang('BUY') {{ __(@$pair->coin->symbol) }} </button>
            </div>
            <div class="trading-bottom__button sell-btn">
                <button class="btn btn--danger w-100 btn--sm sell-btn-sm"> @lang('SELL') {{ __(@$pair->coin->symbol) }} </button>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="d-sm-block d-md-none">
            <x-flexible-view :view="$activeTemplate . 'trade.buy_form'"  :meta="['pair' => $pair,'marketCurrencyWallet' => $marketCurrencyWallet,'screen' => 'small', 'order_count' => $order_count]" />
            <x-flexible-view :view="$activeTemplate . 'trade.sell_form'" :meta="['pair' => $pair,'coinWallet' => $coinWallet,'screen' => 'small']" />
        </div>
    </div>
@endif

@if (!app()->offsetExists('buy_sell_script'))

    @php app()->offsetSet('buy_sell_script',true) @endphp

    @stack('scripts')
    @push('script')
        <script>
            "use strict";
            (function($) {

                let marketPrice          = parseFloat("{{ @$pair->marketData->price }}");
                let coinSymbol           = "{{ @$pair->coin->symbol }}";
                let marketCurrencySymbol = "{{ @$pair->market->currency->symbol }}";

                function buyCalculation() {
                    let amount = parseFloat($('.buy-amount').val());
                    if (!amount){
                        $('.buy-charge').addClass('d-none');
                        return false;
                    }
                    let rate           = buyRate();
                    let totalBuyAmount = amount ;
                    $('.total-buy-amount').val(getAmount(totalBuyAmount));
                    buyCharge()
                };

                $('.buy-amount, .buy-rate').on('input change', function(e) {
                    let allSameElement=$(this).attr('name');
                    $(`form.buy--form`).find(`input[name=${allSameElement}]`).not(this).val($(this).val())
                    buyCalculation();
                });

                function buyCharge() {
                    let buyPercentCharge = parseFloat("{{ $pair->percent_charge_for_buy }}");
                    let amount           = parseFloat($('.total-buy-amount').val());
                    if(amount && amount > 0){
                        let charge      = (amount / 100) * buyPercentCharge;
                        $('.buy-charge').text(', '+getAmount(charge) + ' ' + marketCurrencySymbol).removeClass('d-none')
                    }else{
                        $('.buy-charge').addClass('d-none');
                    }
                }

                function buyRate() {
                    return parseFloat($('.buy-rate').val() || marketPrice);
                }

                $('.total-buy-amount').on('keyup input change', function(e) {
                    let amount = parseFloat($(this).val());
                    if (!amount) return false;
                    let charge     = buyCharge(amount);
                    let rate       = buyRate();
                    let coinAmount = amount / rate;
                    $('.buy-amount').val(getAmount(coinAmount));
                    buyCharge();
                });

                $('.buy-amount-range').on('click', '.range-list__number', function(e) {
                    @guest return false; @endguest

                    let percent = parseInt($(this).data('percent'));
                    changeBuyAmountRange(percent);

                    $(".buy-amount-slider").find('.ui-widget-header').css({
                        'width': `${percent}%`
                    });

                    $(".buy-amount-slider").find('.ui-state-default').css({
                        'left': `${percent ==100 ? 97 : percent}%`
                    });
                });

                function changeBuyAmountRange(percent) {
                    @guest return false; @endguest

                    percent = parseFloat(percent);

                    if (percent > 100) {
                        notify('error', "@lang('Invalid amount range selected')");
                        return false;
                    }

                    let availableBalance = parseFloat("{{ @$marketCurrencyWallet->balance }}");
                    if (availableBalance <= 0) return false;

                    let percentAmount = (availableBalance / 100) * percent;
                    $('.total-buy-amount').val(getAmount(percentAmount)).trigger('change');
                }

                $(".buy-amount-slider").slider({
                    range: true,
                    min: 0,
                    max: 100,
                    values: [0, 0],
                    slide: function(event, ui) {
                        changeBuyAmountRange(ui.value);
                    },
                    change: function(event, ui) {
                        changeBuyAmountRange(ui.value);
                    }
                });

                $('.sell-rate, .sell-amount').on('input change', function(e) {
                    let allSameElement=$(this).attr('name');
                    $(`form.sel--form`).find(`input[name=${allSameElement}]`).not(this).val($(this).val())
                    sellCalculation();
                });

                // sell calculation
                function sellCharge() {
                    let sellPercentCharge = parseFloat("{{ $pair->percent_charge_for_sell }}");
                    let amount            = parseFloat($('.total-sell-amount').val());
                    if(amount && amount > 0){
                        let charge      = (amount / 100) * sellPercentCharge;
                        $('.sell-charge').text(', '+getAmount(charge) + ' ' + marketCurrencySymbol).removeClass('d-none')
                    }else{
                        $('.sell-charge').addClass('d-none');
                    }
                }

                function sellRate() {
                    return parseFloat($('.sell-rate').val() || marketPrice);
                }

                function sellCalculation() {
                    let amount = parseFloat($('.sell-amount').val());
                    if (!amount){
                        $('.sell-charge').addClass('d-none');
                        return false;
                    }
                    let rate            = sellRate();
                    let totalSellAmount = amount ;
                    $('.total-sell-amount').val(getAmount(totalSellAmount));
                    sellCharge();
                };

                $('.total-sell-amount').on('keyup input change', function(e) {
                    let amount = parseFloat($(this).val());
                    if (!amount) return false;
                    let charge = sellCharge(amount);
                    let rate   = sellRate();
                    let marketAmount = amount / rate;
                    $('.sell-amount').val(getAmount(marketAmount));
                    sellCharge();
                });

                function changeSellAmountRange(percent) {
                    @guest return false; @endguest

                    percent = parseFloat(percent);

                    if (percent > 100) {
                        notify('error', "@lang('Invalid amount range selected')");
                        return false;
                    }

                    let availableBalance = parseFloat("{{ @$coinWallet->balance }}");
                    if (availableBalance <= 0) return false;

                    let percentAmount = (availableBalance / 100) * percent;
                    $('.sell-amount').val(getAmount(percentAmount)).trigger('change');
                }

                $('.sell-amount-range').on('click', '.range-list__number', function(e) {

                    @guest return false; @endguest
                    let percent = parseInt($(this).data('percent'));
                    changeSellAmountRange(percent);

                    $(".sell-amount-slider").find('.ui-widget-header').css({
                        'width': `${percent}%`
                    });

                    $(".sell-amount-slider").find('.ui-state-default').css({
                        'left': `${percent == 100 ? 97 : percent}%`
                    });
                });

                $(".sell-amount-slider").slider({
                    range: true,
                    min: 0,
                    max: 100,
                    values: [0, 0],
                    slide: function(event, ui) {
                        changeSellAmountRange(ui.value);
                    },
                    change: function(event, ui) {
                        changeSellAmountRange(ui.value);
                    }
                });
                
                $('.buy-sell-form button[type="submit"]').click(function() {
                    // Store the value or any other attribute of the clicked button
                    var buttonValue = $(this).data('orderside');
            
                    // Store the clicked button's value in a hidden input field within the form
                    $('.buy-sell-form').append('<input type="hidden" name="orderside" value="' + buttonValue + '">');
                });

                $('.buy-sell-form').on('submit', function(e) {
                    e.preventDefault();
                    let formData      = new FormData($(this)[0]);
                    let action        = "{{ route('user.order.save', ':symbol') }}";
                    let symbol        = "{{ @$pair->symbol }}";
                    let token         = $(this).find('input[name=_token]');
                    let orderSide     = $(this).find('input[name="orderside"]').val();
                    let cancelMessage = "@lang('Are you sure to Close this order?')";
                    let actionCancel  = "{{ route('user.order.cancel',':id') }}";
                    formData.set("order_side", orderSide); 
                    $('input[name="orderside"]').remove();
                    
                    let select = document.querySelector(".lot-size-select");
                    let selectedOption = select.options[select.selectedIndex];
                    let selectedLotText = selectedOption.textContent;
                    formData.set("no_of_lot", parseFloat(selectedLotText));
                    
                    let level = document.querySelector('#level-span').innerText.replace(/ USD/g, "");
                    let equity = document.querySelector('#equity-span').innerText.replace(/ USD/g, "");
                    let used_margin = document.querySelector('#used-margin-span').innerText.replace(/ USD/g, "");
                    let free_margin = document.querySelector('#free-margin-span').innerText.replace(/ USD/g, "");
                    let required_margin = document.querySelector(".required-margin-value").innerText.replace(/ USD/g, "");
                    // let level_equity_threshold = parseFloat({{$level_equity_threshold}}) / 100;
                    let used_margin_equity_threshold = parseFloat({{$used_margin_equity_threshold}}) / 100;

                    if (parseFloat(level) >= parseFloat(equity)) {
                        toastr('error', 'Unable to open an order: Level is already below or equal to the 10% of the equity. Need to increase your balance.');
                        return;
                    }
                    
                    if (parseFloat(used_margin) >= (parseFloat(equity) * used_margin_equity_threshold)) {
                        toastr('error', 'This order needs greater required margin than your available free margin.');
                        return;
                    }

                    if (parseFloat(free_margin) <= parseFloat(required_margin)) {
                        toastr('error', 'You do not have enough margin to open this order.');
                        return;
                    }
                    
                    formData.set("required_margin", required_margin);
                    
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': token
                        },
                        url: action.replace(':symbol', symbol),
                        method: "POST",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        beforeSend: function() {
                            if (parseInt(orderSide) === 1) {
                                $('.buy-btn').append(` <i class="fa fa-spinner fa-spin"></i>`);
                                $('.buy-btn').attr('disabled', true);
                            } 
                            if (parseInt(orderSide) === 2) {
                                $('.sell-btn').append(` <i class="fa fa-spinner fa-spin"></i>`);
                                $('.sell-btn').attr('disabled', true);
                            }
                        },
                        complete: function() {
                            if (parseInt(orderSide) === 1) {
                                $('.buy-btn').find(`.fa-spin`).remove();
                                $('.buy-btn').attr('disabled', false);
                            }
                            if (parseInt(orderSide) === 2) {
                                $('.sell-btn').find(`.fa-spin`).remove();
                                $('.sell-btn').attr('disabled', false);
                            }
                        },
                        success: function(resp) {
                            if (resp.success) {
                                $('.avl-market-cur-wallet').text(formatWithPrecision1(resp.data.wallet_balance));
                                notify('success', resp.message);
                            } else {
                                notify('error', resp.message);
                            }
                        },
                        error: function(e) {
                            notify("@lang('Something went to wrong')")
                        }
                    });
                });

                $('.order-type').on('click', function(e) {
                    let orderType = $(this).data('order-type');

                    $('.order-type').find('button').removeClass('active');
                    $(this).find('button').addClass('active');
                    $(this).closest('.trading-bottom').find('.order-wrapper');

                    if (orderType == 'market') {
                        $('.buy-rate').attr('readonly', true);
                        $('.sell-rate').attr('readonly', true);
                        $(`input[name=order_type]`).val(`{{ Status::ORDER_TYPE_MARKET }}`);
                    } else {
                        $('.buy-rate').attr('readonly', false);
                        $('.sell-rate').attr('readonly', false);
                        $(`input[name=order_type]`).val(`{{ Status::ORDER_TYPE_LIMIT }}`);
                    }
                });

            })(jQuery);
        </script>
        
    @endpush

    @push('style')
        <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/range-ui.css') }}">
    @endpush

    @push('script-lib')
        <script src="{{ asset($activeTemplateTrue . 'js/jquery-ui.js') }}"></script>
    @endpush
@endif
