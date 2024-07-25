@php
    $meta                 = (object) $meta;
    $pair                 = @$meta->pair;
    $marketCurrencyWallet = @$meta->marketCurrencyWallet;
    $screen               = @$meta->screen;
    $percentChargeForBuy = @$pair->percent_charge_for_buy;
    $order_count          = @$meta->order_count;
@endphp
<form class="buy-sell-form buy-sell @if(@$meta->screen=='small') buy-sell-one @endif buy--form" method="POST">
    @csrf
    @if ($meta->screen=='small')
        <span class="sidebar__close"><i class="fas fa-times"></i></span>
    @endif
    <input type="hidden" name="order_side" value="{{ Status::BUY_SIDE_ORDER }}">
    <input type="hidden" name="order_type" value="{{ Status::ORDER_TYPE_LIMIT }}">
    
    <div class="buy-sell__wrapper">
        <div class="flex-between mx-0 mt-1 @if(App::getLocale() == 'ar') flex-row-reverse @endif">
            <h7 class="buy-sell__title">@lang('Balance')</h7>
            <span class="fs-12 text-themed">
                @auth
                    <span class="avl-market-cur-wallet text-themed" id="balance_span">{{ showAmount(@$marketCurrencyWallet->balance) }}</span> 
                    <span>USD</span>
                @else
                    <span>00000</span>
                @endauth
            </span>
        </div>
        
        <div class="flex-between mx-0 mt-1 @if(App::getLocale() == 'ar') flex-row-reverse @endif">
            <h7 class="buy-sell__title">@lang('Equity')</h7>
            <span class="fs-12 text-themed">
                @auth
                    <span id="equity-span"></span>
                @else
                    <span>00000</span>
                @endauth
            </span>
        </div>

        <div class="flex-between mx-0 mt-1 @if(App::getLocale() == 'ar') flex-row-reverse @endif">
            <h7 class="buy-sell__title">@lang('Bonus')</h7>
            <span class="fs-12 text-themed">
                @auth
                    <span id="bonus-span">{{ showAmount(@$marketCurrencyWallet->bonus) }} USD</span>
                @else
                    <span>00000</span>
                @endauth
            </span>
        </div>

        <div class="flex-between mx-0 mt-1 @if(App::getLocale() == 'ar') flex-row-reverse @endif">
            <h7 class="buy-sell__title">@lang('Credit')</h7>
            <span class="fs-12 text-themed">
                @auth
                    <span id="credit-span">{{ showAmount(@$marketCurrencyWallet->credit) }} USD</span>
                @else
                    <span>00000</span>
                @endauth
            </span>
        </div>
        
        <!--<div class="flex-between mx-0 mt-1">-->
        <!--    <h7 class="buy-sell__title">@lang('Total')</h7>-->
        <!--    <span class="fs-12">-->
        <!--        <span id="total-span"></span>-->
        <!--    </span>-->
        <!--</div>-->
        
        <div class="flex-between mx-0 mt-1 @if(App::getLocale() == 'ar') flex-row-reverse @endif">
            <h7 class="buy-sell__title">@lang('PL')</h7>
            <span class="fs-12 text-themed">
                @auth
                    <span id="pl-span"></span>
                @else
                    <span>00000</span>
                @endauth
            </span>
        </div>
        
        <div class="flex-between mx-0 mt-1 @if(App::getLocale() == 'ar') flex-row-reverse @endif">
            <h7 class="buy-sell__title">@lang('Used Margin')</h7>
            <span class="fs-12 text-themed">
                @auth
                    <span id="used-margin-span">0</span>
                @else
                    <span>00000</span>
                @endauth
            </span>
        </div>
        
        <div class="flex-between mx-0 mt-1 @if(App::getLocale() == 'ar') flex-row-reverse @endif">
            <h7 class="buy-sell__title">@lang('Free Margin')</h7>
            <span class="fs-12 text-themed">
                @auth
                    <span id="free-margin-span">0</span>
                @else
                    <span>00000</span>
                @endauth
            </span>
        </div>
        
        <div class="flex-between mx-0 mt-1 @if(App::getLocale() == 'ar') flex-row-reverse @endif">
            <h7 class="buy-sell__title">@lang('ST Level') ({{ number_format($pair->level_percent,0) }}%)</h7>
            <span class="fs-12 text-themed">
                @auth
                    <span id="level-span"></span>
                @else
                    <span>00000</span>
                @endauth
            </span>
        </div>
    </div>

    {{-- lot size --}}
    <div class="buy-sell__price pt-1 pb-1">
        <!--<div class="input--group group-two">-->
        <!--    <span class="buy-sell__price-title fs-12">@lang('Lots')</span>-->
        <!--    <select id="lot-size-select" class="form--control style-three lot-size-select" name="amount" onchange="updateLotValues(this)">-->
        <!--        <option value="2.5">0.25</option> <!-- 0.25 lots * 100,000 -->
        <!--        <option value="5">0.5</option>  <!-- 0.5 lots * 100,000 -->
        <!--        <option value="7.5">0.75</option> <!-- 0.75 lots * 100,000 -->
        <!--        <option value="10" selected>1</option> <!-- 1 lot * 100,000 -->
        <!--        <option value="20">2</option> <!-- 2 lots * 100,000 -->
        <!--        <option value="30">3</option> <!-- 3 lots * 100,000 -->
        <!--        <option value="40">4</option> <!-- 2 lots * 100,000 -->
        <!--        <option value="50">5</option> <!-- 3 lots * 100,000 -->
        <!--        <option value="60">6</option> <!-- 2 lots * 100,000 -->
        <!--        <option value="70">7</option> <!-- 3 lots * 100,000 -->
        <!--        <option value="80">8</option> <!-- 2 lots * 100,000 -->
        <!--        <option value="90">9</option> <!-- 3 lots * 100,000 -->
        <!--        <option value="100">10</option> <!-- 2 lots * 100,000 -->
                
        <!--         Add more options as needed -->
        <!--    </select>-->
        <!--</div>-->
        <div class="input--group group-two @if(App::getLocale() == 'ar') text-end @endif">
            <!--<span class="buy-sell__price-title fs-12">@lang('Lots')</span>-->
            <label for="id_label_single">
                <span class="text-themed mb-1">
                    <span class="@if(App::getLocale() != 'ar') d-none @endif">:</span>
                    @lang('Volume in Lot')
                    <span class="@if(App::getLocale() == 'ar') d-none @endif">:</span>
                </span>
                <select id="lot-size-select" class="form--control style-three lot-size-select" name="amount"  style="height: 60px; height: 100%;" onchange="updateLotValues(this)">
                    <option value="1">0.1</option>
                    <option value="2.5">0.25</option> <!-- 0.25 lots * 100,000 -->
                    <option value="5">0.5</option>  <!-- 0.5 lots * 100,000 -->
                    <option value="7.5">0.75</option> <!-- 0.75 lots * 100,000 -->
                    <option value="10" selected>1</option> <!-- 1 lot * 100,000 -->
                    <option value="20">2</option> <!-- 2 lots * 100,000 -->
                    <option value="30">3</option> <!-- 3 lots * 100,000 -->
                    <option value="40">4</option> <!-- 2 lots * 100,000 -->
                    <option value="50">5</option> <!-- 3 lots * 100,000 -->
                    <option value="60">6</option> <!-- 2 lots * 100,000 -->
                    <option value="70">7</option> <!-- 3 lots * 100,000 -->
                    <option value="80">8</option> <!-- 2 lots * 100,000 -->
                    <option value="90">9</option> <!-- 3 lots * 100,000 -->
                    <option value="100">10</option> <!-- 2 lots * 100,000 -->
                    
                </select>
            </label>
        </div>
    </div>
    
    <div class="mx-4 mb-3">
        <ul class="p-0 m-0">
            <li class="d-flex">
                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2 @if(App::getLocale() == 'ar') flex-row-reverse @endif">
                    <div class="me-2">
                        <small class="text-themed d-block mb-1 lot-label">
                            <span class="@if(App::getLocale() != 'ar') d-none @endif">:</span>
                            @lang('Lots')
                            <span class="@if(App::getLocale() == 'ar') d-none @endif">:</span>
                        </small>
                        {{-- <h6 class="mb-0">Send money</h6> --}}
                    </div>
                    <div class="user-progress d-flex align-items-center gap-1">
                        <small class="text-themed d-block mb-1 lot-eq">
                            <span class="lot-eq-span">{{ @$pair->percent_charge_for_buy }}</span> <span class="lot-currency">{{ @$pair->coin_name }}</span>
                        </small>
                    </div>
                </div>
            </li>
            <li class="d-flex mb-1 pb-1">
                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2 @if(App::getLocale() == 'ar') flex-row-reverse @endif">
                    <div class="me-2">
                        <small class="text-themed d-block mb-1">&nbsp</small>
                    </div>
                    <div class="user-progress d-flex align-items-center gap-1">
                        <small class="text-themed d-block mb-1 lot-eq2"><span class="ll-size-span"></span> {{ @$pair->market_name }}</small>
                    </div>
                </div>
            </li>
            <li class="d-flex mt-1 pt-1">
                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2 @if(App::getLocale() == 'ar') flex-row-reverse @endif">
                    <div class="me-2">
                        <small class="text-themed d-block mb-1 pip-label">
                            <span class="@if(App::getLocale() != 'ar') d-none @endif">:</span>
                            @lang('Pips Value')
                            <span class="@if(App::getLocale() == 'ar') d-none @endif">:</span>
                        </small>
                        {{-- <h6 class="mb-0">Send money</h6> --}}
                    </div>
                    <div class="user-progress d-flex align-items-center gap-1">
                        {{-- <h6 class="mb-0">+82.6</h6> <span class="text-muted">USD</span> --}}
                        <small class="text-themed d-block mb-1 pip-value">$0.00</small>
                    </div>
                </div>
            </li>
            <li class="d-flex mt-1 pt-1">
                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2 @if(App::getLocale() == 'ar') flex-row-reverse @endif">
                    <div class="me-2">
                        <small class="text-themed d-block mb-1 required-margin-label">
                            <span class="@if(App::getLocale() != 'ar') d-none @endif">:</span>
                            @lang('Required Margin')
                            <span class="@if(App::getLocale() == 'ar') d-none @endif">:</span>
                        </small>
                        {{-- <h6 class="mb-0">Send money</h6> --}}
                    </div>
                    <div class="user-progress d-flex align-items-center gap-1">
                        {{-- <h6 class="mb-0">+82.6</h6> <span class="text-muted">USD</span> --}}
                        <small class="text-themed d-block mb-1 required-margin-value">$0.00</small>
                    </div>
                </div>
            </li>
        </ul>
    </div>

    {{-- total price --}}
    <div style="margin-top: 10px;"></div>
    
    <div class="trading-bottom__button">
    <!--<div class="mx-3 my-4">-->
        @auth
            <button class="btn btn--danger w-100 btn--sm sell-btn" type="submit" id="sellButton" data-orderside="2">
                <span class="@if(App::getLocale() == 'ar') fs-4 mb-1 @endif">@lang('SELL')</span>
                <input type="number" step="any" class="form--control style-three sell-rate" name="sell_rate" id="sell-rate" style="display: none;"> 
                <span id="sellSpan" style="color:white;display: block"></span>
            </button>
            <div style="margin: 0 2px;"></div>
            <button class="btn btn--base-two w-100 btn--sm buy-btn" type="submit" id="buyButton" data-orderside="1" style="color: white !important">
                <span class="@if(App::getLocale() == 'ar') fs-4 mb-1 @endif">@lang('BUY')</span>
                <input type="number" step="any" class="form--control style-three buy-rate" name="buy_rate" id="buy-rate" style="display: none;"> 
                <span id="buySpan" style="color:white;display: block"></span>
            </button>
        @else
            <div class="btn login-btn w-100 btn--sm">
                <a href="{{ route('user.login') }}">@lang('Login')</a>
                <span>@lang('or')</span>
                <a href="{{ route('user.register') }}">@lang('Register')</a>
            </div>
            
            <div class="mx-1"></div>
            
            <div class="btn login-btn w-100 btn--sm">
                <a href="{{ route('user.login') }}">@lang('Login')</a>
                <span>@lang('or')</span>
                <a href="{{ route('user.register') }}">@lang('Register')</a>
            </div>
        @endauth
    </div>

    <x-flexible-view :view="$activeTemplate . 'trade.traders_trend'"/>
</form>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        updatePipValue(document.querySelector(".lot-size-select"));

        function calculateBuyValue(buyPrice) {
            return (buyPrice * 0.0003) + buyPrice;
        }
        
        function calculateSellValue(sellPrice) {
            return sellPrice; // No calculation needed for sell value
        }

        function updateSpanValues(currentPrice) {
            let coin_name = `{{@$pair->type}}`;

            var curr_price = parseFloat((coin_name === 'Crypto' ? parseFloat(currentPrice).toFixed(5) : formatWithPrecision(currentPrice)));
            var buyValue = calculateBuyValue(curr_price);
            var sellValue = calculateSellValue(curr_price);
            document.title = `${curr_price} {{@$pair->symbol}} | Trading Fusion`;
            
            let buySpan = document.getElementById("buySpan");
            let sellSpan = document.getElementById("sellSpan");
            
            let buyRate = document.querySelector(".buy-rate");
            let sellRate = document.querySelector(".sell-rate");

            buySpan.innerText = removeTrailingZeros((coin_name === 'Crypto' ? buyValue.toFixed(5) : buyValue.toFixed(5)));
            sellSpan.innerText = removeTrailingZeros((coin_name === 'Crypto' ? sellValue.toFixed(5) : sellValue.toFixed(5)));

            buyRate.value = buyValue;
            sellRate.value = sellValue;

            // buySpan.style.fontWeight = 'bold';
            // sellSpan.style.fontWeight = 'bold';

            setTimeout(function() {
                buySpan.style.fontWeight = 'normal';
                sellSpan.style.fontWeight = 'normal';
            }, 100);
        }

        function fetchSymbolCurrentPrice() {
            let actionUrl = "{{ route('trade.current-price', ['type' => @$pair->type, 'symbol' => @$pair->symbol ]) }}";
            let buySpan = $('#buySpan');
            let sellSpan = $('#sellSpan');

            $.ajax({
                url: actionUrl,
                type: "GET",
                dataType: 'json',
                cache: false,
                beforeSend: function() {
                    if (buySpan.text() === '') buySpan.append(` <i class="fa fa-spinner fa-spin"></i>`);
                    if (sellSpan.text() === '') sellSpan.append(` <i class="fa fa-spinner fa-spin"></i>`);
                },
                complete: function() {
                    if (buySpan.text() === '') buySpan.find(`.fa-spin`).remove();
                    if (sellSpan.text() === '') sellSpan.find(`.fa-spin`).remove();
                },
                success: function(resp) {
                    let current_price = resp.current_price;
                    updateSpanValues(current_price);
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching order history: ", error);
                }
            });
        }

        setInterval(function func() { 
            fetchSymbolCurrentPrice();
            updateLLSize();
            
            let level = document.querySelector('#level-span').innerText.replace(/ USD/g, "");
            let equity = document.querySelector('#equity-span').innerText.replace(/ USD/g, "");
            
            if (isLevelMoreThanOrEqualToEquity(level, equity)) {
                closeAllOpenTrade(parseFloat(level), parseFloat(equity));
            }
            
            return func; 
        }(), 1000); 
        
    });

    function updateLotValues(select) {
        console.log('Is select triggered')
        var selectedOption = select.options[select.selectedIndex];
        var selectedLotText = selectedOption.textContent;
        var selectedLot = select.value;
        var lotLabel = document.querySelector('.lot-label');
        lotLabel.innerText =  selectedLotText + ' Lot:';
        
        let lotValue = {{ @$pair->percent_charge_for_buy }};
        let lotEquivalent = parseFloat(lotValue) * parseFloat(selectedLotText);
        document.querySelector('.lot-eq-span').innerText = lotEquivalent;
        
        updateLLSize();
        updatePipValue(select);
    }

    function updateLLSize() {
        let lotEquivalent = parseFloat(document.querySelector('.lot-eq-span').innerText);
        
        let currentPrice = document.querySelector("#sellSpan").innerText;
        let llSizeVal = parseFloat(currentPrice) * lotEquivalent;
        let llSize = parseInt(llSizeVal) >= 0 ? llSizeVal : 0;
        
        document.querySelector('.ll-size-span').innerText = llSize.toFixed();

        let leverage = parseFloat({{ @$pair->percent_charge_for_sell }} || 0);
        let required_margin = llSize / leverage;
        document.querySelector('.required-margin-value').innerText = `${formatWithPrecision1(required_margin)} USD`;
    }

    function updatePipValue(select) {
        let pipValueElement = document.querySelector('.pip-value');
        pipValueElement.innerText = '$ ' + select.value;
    }
    
    function removeTrailingZeros(number) {
        var numberString = number.toString();

        var trimmedNumberString = numberString.replace(/\.?0+$/, '');

        var trimmedNumber = parseFloat(trimmedNumberString);

        if (Number.isInteger(trimmedNumber)) {
            return trimmedNumber.toFixed(2);
        }
        
        return trimmedNumber;
    }
    
    function isLevelMoreThanOrEqualToEquity() {
        let level = parseFloat(document.querySelector('#level-span').innerText.replace(/ USD/g, ""));
        let equity = parseFloat(document.querySelector('#equity-span').innerText.replace(/ USD/g, ""));
        
        return level >= equity;
    }
    
    function closeAllOpenTrade(level, equity)
    {
        $.ajax({
            url: "{{ route('close-all-orders') }}",
            headers: {
                'X-CSRF-TOKEN': "{{csrf_token()}}"
            },
            method: 'POST',
            data: JSON.stringify({ level, equity }),
            contentType: 'application/json',
            success: function(response) {
                if (response.type === 'ALL_ORDERS_CLOSED') {
                    toastr('error', response.message, 'center');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error closing all orders:', error);
            }
        });
    }
</script>
@endpush

@push('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(() => {
        $('#amount').select2({
            tags: true,
            height: 'resolve',
            width: 'resolve',
            createTag: function (params) {
                console.log(params)
                
                if (! isValidNumberOrDecimal(params.term)) {
                    return null;
                }
                
                return {
                    id: (parseFloat(params.term) * 10).toFixed(2),
                    text: params.term,
                    newTag: true
                }
            }
        });
    });

    function isValidNumberOrDecimal(num) {
        return /^-?\d*\.?\d+$/.test(num);
    }
</script>
@endpush

@push('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <style>
        .progress {
            height: 9px;
        }

        .select2-container {
            height: 100% !important;
            min-width: 100% !important;
        }

        .selection {
            min-width: 100% !important;
        }

        .select2-selection__rendered {
            line-height: 31px !important;
        }

        .select2-container .select2-selection--single {
            height: 35px !important;
        }

        .select2-selection__arrow {
            height: 34px !important;
        }
        
        .select2-selection.select2-selection--single {
          color: hsl(var(--body-color));
          background-color: #0d1e23;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: white;
        }
        
        .select2-search--dropdown .select2-search__field {
            background-color: #0d1e23;
            color: white;
        }
        
        .select2-search--dropdown {
            background-color: #0d1e23;
        }
        
        .select2-results__option--selectable {
            background-color: #0d1e23;
            color: white;
        }
        
        .select2-container--default .select2-results__option--selected {
            background-color: #5897fb;
        }
        
        .select2-container--open {
            min-width: 0 !important;
            min-height: 0 !important;
        }
    </style>
@endpush

