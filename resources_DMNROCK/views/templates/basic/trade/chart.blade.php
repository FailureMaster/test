@php
 $meta   = (object) $meta;
 $pair   = $meta->pair;
 $widget = $general->trading_view_widget;

 $symbol = str_replace("_","",$pair->symbol);
 $widget = str_replace('{{pair}}',$symbol,$widget);
 $widget = str_replace('{{pairlistingmarket}}',$pair->listed_market_name,$widget);
@endphp
<div class="trading-chart  p-0 two">
    <iframe class="ichart chart-dark" src='https://www.tradingview-widget.com/embed-widget/advanced-chart/?login=user1&password=dfkjhoijogpoi&locale=en#%7B"symbol"%3A"{{$pair->listed_market_name}}%3A{{$symbol}}"%2C"frameElementId"%3A"tradingview_7abd4"%2C"interval"%3A"5"%2C"hide_side_toolbar"%3A"0"%2C"allow_symbol_change"%3A"8"%2C"save_image"%3A"1"%2C"studies"%3A"%5B%5D"%2C"theme"%3A"dark"%2C"style"%3A"1"%2C"timezone"%3A"Etc%2FUTC"%2C"studies_overrides"%3A"%7B%7D"%2C"utm_source"%3A"swf.centersooq.com"%2C"utm_medium"%3A"widget_new"%2C"utm_campaign"%3A"chart"%2C"utm_term"%3A"{{$pair->listed_market_name}}%3A{{$symbol}}"%2C"page-uri"%3A"swf.centersooq.com%2Ftrade%3Ftvwidgetsymbol%3D{{$pair->listed_market_name}}%253A{{$symbol}}"%7D' width="100%" height="550px" frameborder="0" target="_self"></iframe>
    <iframe class="ichart chart-light" src='https://www.tradingview-widget.com/embed-widget/advanced-chart/?login=user1&password=dfkjhoijogpoi&locale=en#%7B"symbol"%3A"{{$pair->listed_market_name}}%3A{{$symbol}}"%2C"frameElementId"%3A"tradingview_7abd4"%2C"interval"%3A"5"%2C"hide_side_toolbar"%3A"0"%2C"allow_symbol_change"%3A"8"%2C"save_image"%3A"1"%2C"studies"%3A"%5B%5D"%2C"theme"%3A"light"%2C"style"%3A"1"%2C"timezone"%3A"Etc%2FUTC"%2C"studies_overrides"%3A"%7B%7D"%2C"utm_source"%3A"swf.centersooq.com"%2C"utm_medium"%3A"widget_new"%2C"utm_campaign"%3A"chart"%2C"utm_term"%3A"{{$pair->listed_market_name}}%3A{{$symbol}}"%2C"page-uri"%3A"swf.centersooq.com%2Ftrade%3Ftvwidgetsymbol%3D{{$pair->listed_market_name}}%253A{{$symbol}}"%7D' width="100%" height="550px" frameborder="0" target="_self"></iframe>
</div>
@push('script')
<script>
    document.querySelector('.tv-header__link') && document.querySelector('.tv-header__link').remove();
</script>
@endpush
@push('style')
<style>
    [data-theme=light] .chart-dark {
        display: none;
    }
    [data-theme=dark] .chart-light {
        display: none;
    }
    @media screen and (max-width: 575px) {
         .trading-chart iframe {
             height: 350px !important;
         }
    }
</style>
@endpush

