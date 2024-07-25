@php
    $meta    = (object) $meta;
    $pair    = $meta->pair;
    $markets = $meta->markets;
    $marketCurrencyWallet = $meta->marketCurrencyWallet;
    $coinWallet = $meta->coinWallet;
    $order_count = $meta->order_count;
    
    $isCategory = isset($_GET['category']);
@endphp

<div class="tab-inner-wrapper" style="background-color: var(--pane-bg)">
    <div class="tab-content">
        <div class="tab-pane fade {{ !$isCategory ? 'active show' : '' }}" id="market-list-sm" role="tabpanel">
            @if (is_mobile())
                <x-flexible-view :view="$activeTemplate . 'trade.coin_sync_list'" :meta="['pair' => $pair, 'screen' => 'small']" />
            @endif
        </div>
        <div class="tab-pane fade {{ $isCategory ? 'active show' : '' }}" id="chart-sm" role="tabpanel">
            <x-flexible-view :view="$activeTemplate . 'trade.chart'" :meta="['pair' => $pair, 'screen' => 'small']" />
            <div class="position-relative">
                <x-flexible-view :view="$activeTemplate . 'trade.buy_sell'" :meta="[
                    'pair' => $pair,
                    'marketCurrencyWallet' => $marketCurrencyWallet,
                    'coinWallet' => $coinWallet,
                    'screen' => 'medium',
                    'order_count' => $order_count,
                ]" />
            </div>
        </div>
        <div class="tab-pane fade" id="order-book-sm" role="tabpanel">
            <x-flexible-view :view="$activeTemplate . 'trade.my_order'" :meta="['pair' => $pair, 'screen' => 'small']" />
        </div>
        <div class="tab-pane fade" id="trade-history-sm" role="tabpanel">
            <x-flexible-view :view="$activeTemplate . 'trade.history'" :meta="['pair' => $pair]" />
        </div>
    </div>
</div>
<div class="mobile-navigator">
    <div class="bg-dark">
        <div>
            <ul
                class="d-flex justify-content-around nav nav-pills"
                id="pills-sm-tab-list"
                role="tablist"
                >
                <li class="nav-item" role="presentation">
                    <a
                        class="nav-link {{ !$isCategory ? 'active' : '' }}"
                        data-bs-toggle="pill"
                        data-bs-target="#market-list-sm"
                        role="tab"
                        aria-controls="pills-markettwentyfive"
                        aria-selected="false"
                        >
                        @lang('Market')
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a
                        class="nav-link {{ $isCategory ? 'active' : '' }}"
                        data-bs-toggle="pill"
                        data-bs-target="#chart-sm"
                        role="tab"
                        aria-controls="pills-chartthree"
                        aria-selected="true"
                        >
                        @lang('Chart')
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link"
                        data-bs-toggle="pill"
                        data-bs-target="#order-book-sm"
                        role="tab"
                        aria-controls="pills-orderbookthree"
                        aria-selected="false"
                        >
                        @lang('Order Book')
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a
                        class="nav-link"
                        data-bs-toggle="pill"
                        data-bs-target="#trade-history-sm"
                        role="tab"
                        aria-controls="pills-historytwentyfive"
                        aria-selected="false"
                        >
                        @lang('History')
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
@push('style')
<style>
    .tab-inner-wrapper {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        overflow: auto;
        padding: 2px;
        margin-bottom: 80px;
    }
    .mobile-navigator {
        position: absolute;
        bottom: 0;
        width: 100%;
        z-index: 999;
    }
    @media screen and (max-width: 575px) {
        .nav-pills .nav-link {
            padding: 20px 10px !important;
            border-radius: 0 !important;
        }
        .nav-pills .nav-link.active, .nav-pills .show>.nav-link {
            border-top: 4px solid yellow !important;
            background: transparent !important;
        }
    }
    
    @media screen and (min-width: 575px) {
        .trading-mobile {
            display: none;
        }
    }
</style>
@endpush