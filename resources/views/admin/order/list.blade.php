@php
    $currentFilter = request('filter');
@endphp
@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card responsive-filter-card mb-4">
                <div class="card-body">
                    <x-date-filter :currentFilter="$currentFilter" :currentUrl="url()->current()" />
                    <div>
                        <form action="">
                            <div class="d-flex gap-2">
                                <div class="flex-grow-1">
                                    <label>@lang('ID')</label>
                                    <input type="text" name="id" value="{{ request()->id }}" class="form-control">
                                </div>
                                <div class="flex-grow-1">
                                    <label>@lang('Name')</label>
                                    <input type="text" name="name" value="{{ request()->name }}" class="form-control">
                                </div>
                                <div class="flex-grow-1">
                                    <label>@lang('Symbol')</label>
                                    <input type="text" name="symbol" value="{{ request()->symbol }}" class="form-control">
                                </div>
                                <div class="flex-grow-1">
                                    <label>@lang('Volume')</label>
                                    <input type="text" name="volume" value="{{ request()->volume }}" class="form-control">
                                </div>
                                <div class="flex-grow-1">
                                    <label>@lang('Order Type')</label>
                                    <select name="order_type" class="form-control">
                                        <option value="">@lang('Select One')</option>
                                        <option value="1" @selected(request()->order_type == 1)>
                                            Buy
                                        </option>
                                        <option value="2" @selected(request()->order_type == 2)>
                                            Sell
                                        </option>
                                    </select>
                                </div>
                                <div class="flex-grow-1 align-self-end">
                                    <button class="btn btn--primary w-100 h-45">
                                        <i class="la la-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
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
                                    <th>@lang('Name')</th>
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
                                            {{ optional($order->user)->fullname }}
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
    </div>
@endsection

@push('style')
    <style>
        .progress {
            height: 9px;
        }
    </style>
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
