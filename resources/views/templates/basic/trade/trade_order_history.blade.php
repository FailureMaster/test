@php
    $meta = (object) $meta;
    $pair = @$meta->pair;

    $symbol = str_replace("_", "", $pair->symbol);
    $listed_market_name = $pair->listed_market_name;
    
@endphp

<!--<div class=" @if (@$meta->screen == 'small') col-sm-12  d-xl-none d-block @else d-xl-block d-none @endif ">-->
<!--    <div class="trading-header skeleton selected-pair">-->
<!--        <iframe id="watchlistsgogogo" src="https://crm.daimondrock.com/singleticker.html?locale=en&listed_market_name={{$listed_market_name}}&symbol={{$symbol}}" frameborder="0" style="width: 100%; height: 60px;"></iframe>-->
<!--    </div>-->
<!--</div>-->

@push('script')
    <!-- <script>
        // Add an event listener to ensure iframe content is loaded
        document.getElementById('watchlistsgogogo').addEventListener('load', function() {
            // Once loaded, remove the skeleton class to show content
            $('.selected-pair').removeClass('skeleton');
        });
    </script> -->
@endpush

@php
    $meta                   = (object) $meta;
    $pair                   = @$meta->pair;
    $markets                = @$meta->markets;
    $marketCurrencyWallet   = @$meta->marketCurrencyWallet;
    $order_count            = @$meta->order_count;
    $requiredMarginTotal    = @$meta->requiredMarginTotal;
@endphp

<div class="trading-table two" style="padding: 0">
    <div class="flex-between trading-table__header @if(App::getLocale() == 'ar') justify-content-end @endif">
        <div class="trading-bottom__header flex-between" style="padding: 8px 20px">
            <ul class="nav nav-pills mb-0 custom--tab tab-three" id="pills-tab-for-history" role="tablist-for">
                <li class="nav-item" role="presentation" data-order-type="trades">
                    <button class="order-nav-link nav-link" type="button"></button>
                </li>
                <li class="nav-item" role="presentation" data-order-type="orders">
                    <button class="order-nav-link nav-link active" type="button">@lang('Orders')</button>
                </li>
                <li class="nav-item" role="presentation" data-order-type="history">
                    <button class="order-nav-link nav-link" type="button">@lang('History')</button>
                </li>
            </ul>
        </div>
        <!--<div class="float-end text-white me-3">Equity: <span id="equity-span"></span></div>-->
    </div>
    <div class="tab-content" id="pills-tabContenttwenty">
        <div class="tab-pane fade show active">
            <div class="order-book-pan tab-pan-content" style="display: none"><x-flexible-view :view="$activeTemplate . 'trade.order_book'" :meta="['pair' => $pair, 'screen' => 'small']" /></div>
            <div class="my_order tab-pan-content px-4 pb-4 active"><x-flexible-view :view="$activeTemplate . 'trade.my_order'" :meta="['markets' => $markets, 'screen' => 'small','pair' => $pair, 'order_count' => $order_count, 'marketCurrencyWallet' => $marketCurrencyWallet, 'requiredMarginTotal' => $requiredMarginTotal]" /></div>
            <div class="history tab-pan-content px-4 pb-4" style="display: none"><x-flexible-view :view="$activeTemplate . 'trade.history'" :meta="['pair' => $pair, 'screen' => 'small']" /></div>
        </div>
    </div>
</div>

@push('script')
<script>
    "use strict";
    (function($) {
        // Add event listener to handle tab navigation
        $(".order-nav-link").on("click", function() {
            $(".order-nav-link").removeClass("active");
            $(this).addClass("active");

            if ($(".order-nav-link").eq(0).is($(this))) {
                $(".order-book-pan").css("display", "block");
                $(".my_order").css("display", "none");
                $(".history").css("display", "none");
            } else if ($(".order-nav-link").eq(1).is($(this))) {
                $(".order-book-pan").css("display", "none");
                $(".my_order").css("display", "block");
                $(".history").css("display", "none");
            } else {
                $(".order-book-pan").css("display", "none");
                $(".my_order").css("display", "none");
                $(".history").css("display", "block");
            }
        });
    })(jQuery);
</script>
@endpush

@push('style')
    <style>
        .custom--modal .modal-content {
            background-color: #0d1e23 !important;
            border-radius: 10px !important;
        }

        .custom--modal .modal-title {
            color: hsl(var(--white)/0.5);
        }

        .custom--modal .modal-header,
        .custom--modal .modal-footer {
            border-color: hsl(var(--white)/0.2) !important;
        }

        .btn-dark,
        .btn-dark:hover,
        .btn-dark:focus {
            border-color: hsl(var(--white)/0.1) !important;
            color: #ffffff !important;
        }
        .tab-pan-content {
            height: 300px;
            overflow-y: hidden
        }
        
        .card {
            border: 0;
        }
        
        .card-header {
            color: white;
            background-color: #3c4a4d;
            padding: 1rem 1rem;
        }
        
        .card-body {
            background-color: #0d1e23;
        }
        
        .list-group-item {
            color: white;
            background-color: #0d1e23;
        }
    
    
        @media screen and (max-width: 575px) {
            .trading-table {
                display: none;
            }
        }
        
        @media screen and (min-width: 575px) {
            .trading-table__mobile {
                display: none;
            }
        }
    </style>
@endpush
