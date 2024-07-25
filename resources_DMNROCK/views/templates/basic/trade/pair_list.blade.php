@php
    $meta = (object) $meta;
    $markets = @$meta->markets;
    $crypto_pairs = DB::table('coin_pairs')->where('type', 'Crypto')->get();
    $stock_pairs = DB::table('coin_pairs')->where('type', 'Stocks')->get();
    $forex_pairs = DB::table('coin_pairs')->where('type', "FOREX")->get();
    $index_pairs = DB::table('coin_pairs')->where('type', 'INDEX')->get();
    $commodity_pairs = DB::table('coin_pairs')->where('type', 'COMMODITY')->get();
    $pairs = [
        ["title" => "Stocks", "symbols"=>$stock_pairs->map(function ($pair) {
            return ["s"=>$pair->listed_market_name . ":" . $pair->symbol, "d"=>""];
        })->toArray()],
        ["title"=> "Forex", "symbols"=>$forex_pairs->map(function ($pair) {
            return ["s"=>$pair->listed_market_name . ":" . $pair->symbol, "d"=>""];
        })->toArray()],
        ["title"=> "Index", "symbols"=>$index_pairs->map(function ($pair) {
            return ["s"=>$pair->listed_market_name . ":" . $pair->symbol, "d"=>""];
        })->toArray()],
        ["title"=> "Crypto", "symbols"=>$crypto_pairs->map(function ($pair) {
            return ["s"=>$pair->listed_market_name . ":" . $pair->symbol, "d"=>""];
        })->toArray()],
        ["title"=> "Commodity", "symbols"=>$commodity_pairs->map(function ($pair) {
            return ["s"=>$pair->listed_market_name . ":" . $pair->symbol, "d"=>""];
        })->toArray()]
    ];

    $optionstr = '{
        "colorTheme": "dark",
        "dateRange": "12M",
        "showChart": false,
        "largeChartUrl": "' . Request::url() . '",
        "isTransparent": true,
        "showSymbolLogo": true,
        "showFloatingTooltip": false,
        "width": 400,
        "height": 465,
        "tabs": [],
        "utm_source": "www.tradingview.com",
        "utm_medium": "widget_new",
        "utm_campaign": "market-overview",
        "page-uri": "www.tradingview.com/widget-wizard/en/light/market-overview/"
    }';
    $option = json_decode($optionstr);
    $option->tabs = $pairs;
@endphp

<div class="trading-right">
    @if (@$meta->screen == 'big')
        <span class="sidebar__close d-xl-none d-block"><i class="fas fa-times"></i></span>
    @endif
    <div class="trading-right__top">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h5 class="trading-right__title">@lang('Markets')</h5>
            <form id="search-market">
                <div class="input--group">
                    <button class="search-btn" type="submit"><i class="las la-search"></i></button>
                    <input type="text" class="form--control style-two pjsInput" placeholder="Search" name="search" id="searchInput">
                </div>
            </form>
        </div>
    </div>
    <div class="tab-content" id="pills-tabContentsixteen">
        
        <div id="content-container" class="mx-1">
            <iframe class="watchlists" id="watchlists" src="{{ 'https://www.tradingview-widget.com/embed-widget/market-overview/?locale=en#' . json_encode($option) }}" frameborder="0" style="user-select: none; box-sizing: border-box; display: block; height: 822px; width: 100%;" allowfullscreen base target="_parent"></iframe>
        </div>
    </div>
</div>

