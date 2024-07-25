@php
    $currentFilter = request('filter');
@endphp
@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card responsive-filter-card mb-4">
                <div class="card-body">
                    <div class="mb-4">
                        <form method="GET" action="{{ url()->current() }}">
                            <div class="btn-group d-flex w-100 mb-2" role="group" aria-label="Basic example">
                                <button type="submit" name="filter" value="today" class="btn btn-lg btn-custom-border {{ $currentFilter == 'today' ? 'btn-primary' : 'btn-outline-primary' }}">Today</button>
                                <button type="submit" name="filter" value="yesterday" class="btn btn-lg btn-custom-border {{ $currentFilter == 'yesterday' ? 'btn-primary' : 'btn-outline-primary' }}">Yesterday</button>
                                <button type="submit" name="filter" value="this_week" class="btn btn-lg btn-custom-border {{ $currentFilter == 'this_week' ? 'btn-primary' : 'btn-outline-primary' }}">This Week</button>
                                <button type="submit" name="filter" value="last_week" class="btn btn-lg btn-custom-border {{ $currentFilter == 'last_week' ? 'btn-primary' : 'btn-outline-primary' }}">Last Week</button>
                                <button type="submit" name="filter" value="this_month" class="btn btn-lg btn-custom-border {{ $currentFilter == 'this_month' ? 'btn-primary' : 'btn-outline-primary' }}">This Month</button>
                                <button type="submit" name="filter" value="last_month" class="btn btn-lg btn-custom-border {{ $currentFilter == 'last_month' ? 'btn-primary' : 'btn-outline-primary' }}">Last Month</button>
                                <a id="customFilterButton" class="btn btn-lg btn-custom-border {{ $currentFilter == 'custom' ? 'btn-primary' : 'btn-outline-primary' }}" data-bs-toggle="modal" data-bs-target="#customDateFilterModal">Custom</a>
                            </div>
                        </form>
                    </div>
                    <div>
                        <div class="d-flex flex-wrap gap-2 justify-content-end">
                            <x-search-form placeholder="Order #,pair,coin,currency..." />
                            <form>
                                <div class="input-group">
                                    <select name="order_side" class="form-control">
                                        <option value="">@lang('Order Side')</option>
                                        <option value="{{ Status::BUY_SIDE_ORDER }}">@lang('Buy')</option>
                                        <option value="{{ Status::SELL_SIDE_ORDER }}">@lang('Sell')</option>
                                    </select>
                                    <button class="btn btn--primary input-group-text" type="submit"><i class="la la-search"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            @php
                                $showStatus = request()->routeIs('admin.order.history');
                            @endphp
                            <thead>
                                <tr>
                                    <th>@lang('Order ID')</th>
                                    <th>@lang('Date')</th>
                                    <th>@lang('Symbol')</th>
                                    <th>@lang('Order Type')</th>
                                    <th>@lang('Volume')</th>
                                    <th>@lang('Open Price')</th>
                                    @if(request()->routeIs('admin.order.close'))
                                        <th>@lang('Closed Price')</th>
                                    @endif
                                    @if(request()->routeIs('admin.order.open'))
                                        <th>@lang('Profit')</th>
                                    @endif
                                    <!--<th>@lang('Action')</th>-->
                                    @if ($showStatus)
                                        <th>@lang('Status')</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr data-order-id="{{ $order->id }}">
                                        <td>
                                            <div>
                                                <input type="hidden" class="rate" value="{{ $order->rate }}">
                                                <input type="hidden" class="lot_value" value="{{ $order->pair->percent_charge_for_buy }}">
                                                <input type="hidden" class="type" value="{{ $order->pair->type }}">
                                                <input type="hidden" class="symbol" value="{{ $order->pair->symbol }}">
                                                <input type="hidden" class="no_of_lot" value="{{ $order->no_of_lot }}">
                                                <input type="hidden" class="order_side" value="{{ $order->order_side }}">
                                                {{ $order->id }}
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                {{ $order->formatted_date }}
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                {{ @$order->pair->coin_name }}
                                            </div>
                                        </td>
                                        <td> @php echo $order->orderSideBadge; @endphp </td>
                                        <td>
                                            <div>
                                                {{ $order->no_of_lot }}
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                
                                                {{ showAmount($order->rate, 5) }} {{ @$order->pair->market->currency->symbol }}
                                            </div>
                                        </td>
                                        @if(request()->routeIs('admin.order.close'))
                                            <td>
                                                <div>
                                                    {{ $order->closed_price }}
                                                </div>
                                            </td>
                                        @endif
                                        @if(request()->routeIs('admin.order.open'))
                                            <td>
                                                <div>
                                                    <span class="order_profit"></span>
                                                </div>
                                            </td>
                                        @endif
                                        @if ($showStatus)
                                            <td> @php echo $order->statusBadge; @endphp </td>
                                        @endif
                                        <div id="actionMessageModal{{ $order->id }}" class="modal fade" tabindex="-1" role="dialog">
                                            <div class="modal-dialog modal-sm" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Delete Order #{{ $order->id }}</h5>
                                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                            <i class="las la-times"></i>
                                                        </button>
                                                    </div>
                                                    <form
                                                        action="{{ route('admin.order.delete', $order->id) }}"
                                                        method="POST"
                                                        >
                                                        @csrf
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('No')</button>
                                                            <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($orders->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($orders) }}
                    </div>
                @endif
            </div>
        </div>
        <div class="modal fade" id="customDateFilterModal" tabindex="-1" role="dialog" aria-labelledby="customDateFilterLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="customDateFilterLabel">Custom Filter</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="customDateFilterForm" action="{{ url()->current() }}" method="GET">
                            <div class="flex-grow-1">
                                <label>@lang('Sart date - End date')</label>
                                <input name="customfilter" data-range="true" data-multiple-dates-separator=" - " data-language="en" class="customDateFilterInput form-control" data-position='bottom right' placeholder="@lang('Start date - End date')" autocomplete="off" value="{{ request()->date }}">
                            </div>
                            <div class="my-3">
                                <button type="submit" class="btn-lg btn-primary w-100">Start Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .progress {
            height: 9px;
        }

        .btn-custom-border {
            border: none;
            border-bottom: 1px solid; /* or specify a color if needed */
        }

        .btn-outline-primary.btn-custom-border {
            border-bottom: 1px solid #007bff; /* match the border color to the outline-primary color */
        }

        .btn-primary.btn-custom-border {
            border-bottom: 1px solid #007bff; /* match the border color to the primary color */
        }

        .datepickers-container {
            z-index: 10000 !important;
        }
    </style>
@endpush

@push('script')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        
        const customFilter = urlParams.get('customfilter');

        if (customFilter) {
            const decodedCustomFilter = decodeURIComponent(customFilter);

            const dateRange = decodedCustomFilter.split(' - ');

            const formattedStartDate = formatDate(dateRange[0]);
            const formattedEndDate = formatDate(dateRange[1]);

            if (formattedStartDate && formattedEndDate) {
                const button = document.getElementById("customFilterButton");
                button.innerHTML = `<i class="far fa-calendar"></i> ${formattedStartDate} - <i class="far fa-calendar"></i> ${formattedEndDate}`;
                button.classList.add('btn-primary');
                button.classList.add('text-white');
            } else {
                console.error('Invalid date range format in customfilter parameter.');
            }
        }
    });

    function formatDate(dateString) {
        const date = new Date(dateString);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = String(date.getFullYear()).slice(-2);
        return `${month}-${day}-${year}`;
    }
</script>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {

            $(document).ready(function() {
                let jsonData = {};
                function fetchMarketData() {
                    $.ajax({
                        url: `{{ route('admin.order.fetch.market.data') }}`,
                        method: 'GET',
                        success: function(response) {
                            console.log(response);
                            jsonData = response;
                            updateOrderProfit();
                        },
                        error: function(xhr, status, error) {
                        }
                    });
                }

                function updateOrderProfit() {
                    $.each($('tr[data-order-id]'), function() {
                        $('tr[data-order-id]').each(function() {
                            let id = $(this).data('order-id');
                            let rate = $(this).find('.rate').val();
                            let lot_value = $(this).find('.lot_value').val();
                            let no_of_lot = $(this).find('.no_of_lot').val();
                            let order_side = $(this).find('.order_side').val();
                            let type = $(this).find('.type').val();
                            let symbol = $(this).find('.symbol').val();

                            if (jsonData[type] && jsonData[type][symbol]) {
                                let current_price = parseFloat(jsonData[type][symbol]);

                                let lot_equivalent = lot_value * no_of_lot;
                                let total_price = order_side === 2
                                    ? formatWithPrecision(((rate - current_price) * lot_equivalent))
                                    : formatWithPrecision(((current_price - rate) * lot_equivalent));

                                $(this).find('.order_profit').text(formatWithPrecision(total_price));
                            } else {
                                console.error(`Current price not found for type: ${type}, symbol: ${symbol}`);
                            }
                        });
                    });
                }

                function formatWithPrecision(value, precision = 5) {
                    return Number(value).toFixed(precision);
                }

                fetchMarketData();

                setInterval(fetchMarketData, 1500);
            });

            $(`select[name=order_side]`).on('change', function(e) {
                $(this).closest('form').submit();
            });

            @if (request()->order_side)
                $(`select[name=order_side]`).val("{{ request()->order_side }}");
            @endif ()
            
        })(jQuery);
    </script>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{asset('assets/admin/css/vendor/datepicker.min.css')}}">
@endpush

@push('script-lib')
  <script src="{{ asset('assets/admin/js/vendor/datepicker.min.js') }}"></script>
  <script src="{{ asset('assets/admin/js/vendor/datepicker.en.js') }}"></script>
@endpush

@push('script')
  <script>
    (function($){
        "use strict";
        if(!$('.customDateFilterInput').val()){
            $('.customDateFilterInput').datepicker();
        }
    })(jQuery)
  </script>
@endpush
