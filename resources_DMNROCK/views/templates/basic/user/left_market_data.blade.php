@php
    $crypto_pairs = DB::table('coin_pairs')->where('type', 'Crypto')->get();
    $stock_pairs = DB::table('coin_pairs')->where('type', 'Stocks')->get();
    $forex_pairs = DB::table('coin_pairs')->where('type', "FOREX")->get();
    $index_pairs = DB::table('coin_pairs')->where('type', 'INDEX')->get();
    $commodity_pairs = DB::table('coin_pairs')->where('type', 'COMMODITY')->get();
    $pairs = [
        ["title" => "Stocks", "symbols"=>$stock_pairs->map(function ($pair) {
            return ["s"=>$pair->listed_market_name . ":" . $pair->symbol, "d"=>""];
        })->toArray()],
        ["title"=> "FOREX", "symbols"=>$forex_pairs->map(function ($pair) {
            return ["s"=>$pair->listed_market_name . ":" . $pair->symbol, "d"=>""];
        })->toArray()],
        ["title"=> "INDEX", "symbols"=>$index_pairs->map(function ($pair) {
            return ["s"=>$pair->listed_market_name . ":" . $pair->symbol, "d"=>""];
        })->toArray()],
        ["title"=> "Crypto", "symbols"=>$crypto_pairs->map(function ($pair) {
            return ["s"=>$pair->listed_market_name . ":" . $pair->symbol, "d"=>""];
        })->toArray()],
        ["title"=> "COMMODITY", "symbols"=>$commodity_pairs->map(function ($pair) {
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
@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="row justify-content-center gy-4">
        <div class="col-lg-2"></div>
        <div class="col-lg-8">
            <div class="tab-content" id="pills-tabContentsixteen">
                <div id="content-container" class="mx-1">
                    <iframe class="watchlists" id="watchlists" src="{{ 'https://www.tradingview-widget.com/embed-widget/market-overview/?locale=en#' . json_encode($option) }}" frameborder="0" style="user-select: none; box-sizing: border-box; display: block; height: 822px; width: 100%;" allowfullscreen base target="_parent"></iframe>
                </div>
            </div>
        </div>
        <div class="col-lg-2"></div>
    </div>
@endsection

@push('topContent')
    <h4 class="mb-4">{{ __($pageTitle) }}</h4>
@endpush