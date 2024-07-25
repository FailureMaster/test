<div class="trading-table two">
    <div class="flex-between trading-table__header">
        {{-- Header Content --}}
    </div>
    <div class="tab-content" id="pills-tabContentfortyfour">
        <div class="tab-pane fade show active" id="pills-marketnineteen" role="tabpanel"
            aria-labelledby="pills-marketnineteen-tab" tabindex="0">
            <div class="table-wrapper-two">
                @auth
                    <table class="table table-two history-table">
                        @if(App::getLocale() == 'ar')
                            <thead>
                                <tr>
                                    <th class="text-center">@lang('Status')</th>
                                    <th class="text-center">@lang('Profit')</th>
                                    <th class="text-center">@lang('Closed Price')</th>
                                    <th class="text-center">@lang('Open Price')</th>
                                    <th class="text-center">@lang('Volume')</th>
                                    <th class="text-center">@lang('Type')</th>
                                    <th class="text-center">@lang('Symbol')</th>
                                    <th class="text-center">@lang('Date')</th>
                                    <th class="text-center">@lang('Order ID')</th>
                                    <!--<th></th>-->
                                </tr>
                            </thead>
                        @else
                            <thead>
                                <tr>
                                    <th class="text-center">@lang('Order ID')</th>
                                    <th class="text-center">@lang('Date')</th>
                                    <th class="text-center">@lang('Symbol')</th>
                                    <th class="text-center">@lang('Type')</th>
                                    <th class="text-center">@lang('Volume')</th>
                                    <th class="text-center">@lang('Open Price')</th>
                                    <th class="text-center">@lang('Closed Price')</th>
                                    <th class="text-center">@lang('Profit')</th>
                                    <th class="text-center">@lang('Status')</th>
                                    <!--<th></th>-->
                                </tr>
                            </thead>
                        @endif
                        <tbody class="history-body">
                            {{-- Rows will be added here dynamically --}}
                        </tbody>
                    </table>
                @else
                    <div class="empty-thumb">
                        <img src="{{ asset('assets/images/extra_images/user.png') }}" alt="Please login"/>
                        <p class="empty-sell" style="color:#d1d4dc">@lang('Please login to explore your order')</p>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>
<div class="trading-table__mobile">
    <div class="card history-body">
      {{-- Data will be added here dynamically --}}
    </div>
</div>
@push('style')
<style>
    .history-table thead tr th {
        font-size: 0.875rem !important;
    }
    .history-body > tr > td {
        border-bottom: 1px solid hsl(var(--base-two)/0.09) !important;
    }    
</style>
@endpush
@push('script')
<script>
   
$(document).ready(function() {
    "use strict";

    function formatWithPrecision(value, precision = 5) {
        // Formats numbers with a specified precision
        return Number(value).toFixed(precision);
    }
    function formatWithPrecision1(value, precision = 2) {
        // Formats numbers with a specified precision
        return Number(value).toFixed(precision);
    }
    
    function getRandomItem(arr) {
        const randomIndex = Math.floor(Math.random() * arr.length);
        const item = arr[randomIndex];
    
        return item;
    }

    var i = 1;
    
    function generateHistoryRow(order, jsonData) {
        let current_price = jsonData[order.pair.symbol]
        
        let lotValue = order.pair.percent_charge_for_buy;
        
        let lotEquivalent = parseFloat(lotValue) * parseFloat(order.no_of_lot);
        let leverage = parseFloat(order.pair.percent_charge_for_sell);
        let total_price = formatWithPrecision(((parseFloat(order.rate) - parseFloat(current_price)) * lotEquivalent ) * leverage);
        
        let profitClass = order.profit <= 0 ? 'text-danger' : 'text-success';
        
        if (window.innerWidth < 579) {
            return `
                <div class="card-header">
                    <span>${order.id}</span>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Date: ${order.formatted_date}</li>
                        <li class="list-group-item">Symbol: ${order.pair.symbol.replace('_', '/')}</li>
                        <li class="list-group-item">Type: ${order.order_side_badge}</li>
                        <li class="list-group-item">Volume: ${removeTrailingZeros(order.no_of_lot)}</li>
                        <li class="list-group-item">Open Price: ${formatWithPrecision(order.rate)}</li>
                        <li class="list-group-item">Closed Price: <span>${removeTrailingZeros(formatWithPrecision(order.closed_price)) || 0}</span></li>
                        <li class="list-group-item">Profit: <span class="${profitClass}">${removeTrailingZeros(formatWithPrecision(order.profit)) || 0}</span></li>
                        <li class="list-group-item">Status: ${order.status_badge}</li>
                    </ul>
                </div>
            `;
        }
            
        return `
            @if (App::getLocale() != 'ar')
                <tr data-order-id="${order.id}">
                    <td class="text-center p-2">#${order.id}</td>
                    <td class="text-center p-2">${order.formatted_date}</td>
                    <td class="text-center p-2">${order.pair.symbol.replace('_', '/')}</td>
                    <td class="text-center p-2">${order.order_side_badge}</td>
                    <td class="text-center p-2">${removeTrailingZeros(order.no_of_lot)}</td>
                    <td class="text-center p-2">${formatWithPrecision(order.rate)}</td>
                    <td class="text-center p-2">${removeTrailingZeros(formatWithPrecision(order.closed_price)) || 0}</span></td>
                    <td class="text-center p-2"><span class="${profitClass}">${removeTrailingZeros(formatWithPrecision(order.profit)) || 0}</span></td>
                    <td class="text-center p-2">${order.status_badge}</td>
                </tr>
            @else
                <tr data-order-id="${order.id}">
                    <td class="text-center p-2">${order.status_badge}</td>
                    <td class="text-center p-2"><span class="${profitClass}">${removeTrailingZeros(formatWithPrecision(order.profit)) || 0}</span></td>
                    <td class="text-center p-2">${removeTrailingZeros(formatWithPrecision(order.closed_price)) || 0}</span></td>
                    <td class="text-center p-2">${formatWithPrecision(order.rate)}</td>
                    <td class="text-center p-2">${removeTrailingZeros(order.no_of_lot)}</td>
                    <td class="text-center p-2">${order.order_side_badge}</td>
                    <td class="text-center p-2">${order.pair.symbol.replace('_', '/')}</td>
                    <td class="text-center p-2">${order.formatted_date}</td>
                    <td class="text-center p-2">#${order.id}</td>
                </tr>
            @endif
        `;
    }
    
    function fetchHistory() {
        let actionUrl = "{{ route('trade.order.list', ['pairSym' => @$pair->symbol ?? 'default_symbol', 'status' => 'history' ]) }}";
        $.ajax({
            url: actionUrl,
            type: "GET",
            dataType: 'json',
            cache: false,
            data: {}, // Now fetching all orders without status differentiation
            success: function(resp) {
                let html = '';
                let jsonMarketData = resp.marketData;
                
                if (resp.orders && resp.orders.length > 0) {
                    resp.orders.forEach(order => {
                        html += generateHistoryRow(order, jsonMarketData[order.pair.type]);
                    });
                } else {
                    html = `<tr class="text-center"><td colspan="9">@lang('No order found')</td></tr>`;
                }
                $('.history-body').html(html);
            },
            error: function(xhr, status, error) {
                console.error("Error fetching history: ", error);
            }
        });
    }
    
    setInterval(function func() { 
        fetchHistory()
        return func; 
    }(), 10000); 
});
</script>
@endpush

{{-- @php
    $meta = (object) $meta;
    $pair = @$meta->pair;
@endphp
<div class="trading-right__bottom">
    <div class="d-flex trading-market__header justify-content-between text-center">
        <div class="trading-market__header-two">
            @lang('Price')({{ $pair->market->currency->symbol }})
        </div>
        <div class="trading-market__header-one">
            @lang('Amount') ({{ $pair->coin->symbol }})
        </div>
        <div class="trading-market__header-three">
            @lang('Date/Time')
        </div>
    </div>
    <div class="tab-content" id="pills-tabContentfortyfour">
        <div class="tab-pane fade show active" id="pills-marketnineteen" role="tabpanel"
            aria-labelledby="pills-marketnineteen-tab" tabindex="0">
            <div class="market-wrapper">
                <div class="history  trade-history"></div>
            </div>
        </div>
    </div>
</div>

@if (!app()->offsetExists('trade_script'))
@php app()->offsetSet('trade_script',true) @endphp
@push('script')
    <script>
        "use strict";
        (function ($) {
            let pairSymbol    = "{{ $pair->symbol }}";
            let sellSideTrade = parseInt("{{ Status::SELL_SIDE_TRADE }}");


            function newTradeHmtl (data) {
               let trades=data.trade;

               let newHtml=``;
               $.each(trades, function (symbol, trade) {
                    if(pairSymbol != symbol){
                        return;
                    }
                    $.each(trade, function (i, element) {
                        newHtml+=`<ul class="history__list flex-between trade-history-item" data-rate="${element.rate}">
                        <li class="history__amount-item text-start ${ sellSideTrade == parseInt(element.trade_side) ? 'text-danger' : '' }">
                            ${showAmount(element.rate)}
                        </li>
                        <li class="history__price-item text-center"> ${showAmount(element.amount)} </li>
                        <li class="history__date-item"> ${new Date().toLocaleString()} </li>
                    </ul>`
                    });
               });
               $('.trade-history').prepend(newHtml);
            }
            pusherConnection('trade', newTradeHmtl);

            $('.trade-history').on('click','.trade-history-item',function (e) {
                let rate=$(this).data('rate');
                $('.buy-rate').val(getAmount(rate)).trigger('change');
                $('.buy-amount').val(1);
                $('.sell-amount').val(1);
                $('.sell-rate').val(getAmount(rate)).trigger('change');
            });

            function tradeHistory(){
                let action        = "{{ route('trade.history',':curSym') }}";

                $.ajax({
                    url: action.replace(':curSym',"{{@$pair->symbol}}"),
                    type: "GET",
                    dataType: 'json',
                    cache: false,
                    success: function (resp) {
                        let html=``;
                        if(resp.success){
                            if(resp.trades.length > 0){
                                $.each(resp.trades, function (i, trade) {
                                    html+=`<ul class="history__list flex-between trade-history-item" data-rate="${trade.rate}">
                                        <li class="history__amount-item text-start ${ sellSideTrade == parseInt(trade.trade_side) ? 'text-danger' : '' }">
                                            ${showAmount(trade.rate)}
                                        </li>
                                        <li class="history__price-item text-center"> ${showAmount(trade.amount)} </li>
                                        <li class="history__date-item"> ${trade.formatted_date} </li>
                                    </ul>`
                                    ;
                                });
                                $('.trade-history').removeClass('justify-content-center');
                            }else{
                                html+=`
                                <div class="empty-thumb">
                                    <img src="{{ asset('assets/images/extra_images/empty.png') }}"/>
                                    <p   class="empty-sell" style="color:##d1d4dc">@lang('No trade found')</p>
                                </div>
                                `;
                                $('.trade-history').addClass('justify-content-center');
                            }
                        }
                        $('.trade-history').html(html);
                    }
                });
            }
            tradeHistory();
        })(jQuery);
    </script>
@endpush
@endif
 --}}