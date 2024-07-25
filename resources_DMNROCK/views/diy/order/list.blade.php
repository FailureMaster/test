@extends('diy.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            @php
                                var_dump(request()->session()->get('loggedIn'));
                            @endphp
                            @php
                                $showStatus = request()->routeIs('diy.order.history');
                            @endphp
                            <thead>
                                <tr>
                                    <th>@lang('Order ID')</th>
                                    <th>@lang('Date')</th>
                                    <th>@lang('Symbol')</th>
                                    <th>@lang('Order Type')</th>
                                    <th>@lang('Volume')</th>
                                    <th>@lang('Open Price')</th>
                                    <th>@lang('Profit')</th>
                                    <th>@lang('Action')</th>
                                    @if ($showStatus)
                                        <th>@lang('Status')</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td>
                                            <div>
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
                                        <td>
                                            <div>
                                                
                                                {{ showAmount($order->profit, 5) }} {{ @$order->pair->market->currency->symbol }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end">
                                                <div class="button--group m-0 ml-1">
                                                    <a href="{{ route('diy.order.edit', $order->id) }}"
                                                        class="btn btn-sm btn-outline--primary">
                                                        <i class="la la-pencil"></i>@lang('Edit')
                                                    </a>
                                                </div>
                                                <div class="button--group m-0">
                                                    <a  class="btn btn-sm btn-outline--danger deleteOpenOrderBtn" data-bs-toggle="modal" data-bs-target="#actionMessageModal{{ $order->id }}">
                                                        <i class="la la-trash"></i>@lang('Delete')
                                                    </a>
                                                </div>
                                                
                                            </div>
                                        </td>
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
                                                        action="{{ route('diy.order.delete', $order->id) }}"
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

@push('breadcrumb-plugins')
    <div class="d-flex flex-wrap gap-2 justify-content-between">
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
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {

            $(`select[name=order_side]`).on('change', function(e) {
                $(this).closest('form').submit();
            });

            @if (request()->order_side)
                $(`select[name=order_side]`).val("{{ request()->order_side }}");
            @endif ()
        })(jQuery);
    </script>
@endpush


@push('style')
    <style>
        .progress {
            height: 9px;
        }
    </style>
@endpush
