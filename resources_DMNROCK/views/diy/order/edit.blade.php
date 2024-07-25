@extends('diy.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-body p-0">
                <form
                    action="{{ route('diy.order.update', @$order->id) }}"
                    method="POST"
                    enctype="multipart/form-data" class="pair-form"
                    >
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label>@lang('Order ID')</label>
                                <div class="input-group appnend-coin-sym">
                                    <input
                                        type="number"
                                        step="any"
                                        class="form-control"
                                        name="id"
                                        value="{{ old('id',@$order->id) }}"
                                        disabled
                                        >
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label>@lang('Date')</label>
                                <div class="input-group appnend-coin-sym">
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="created_at"
                                        value="{{ old('created_at',@$order->formatted_date) }}"
                                        disabled
                                        >
                                </div>
                            </div>
                            <div class="form-gropup col-sm-6" id="symbol">
                                <label>@lang('Order Type')</label>
                                <select
                                    class="form-control select2-basic"
                                    name="order_type"
                                    required
                                    >
                                    <option selected disabled>@lang('Select One')</option>
                                    <option value="1" {{ @$order->order_type == 1 ? 'selected' : '' }}>
                                        Buy
                                    </option>
                                    <option value="2" {{ @$order->order_type == 2 ? 'selected' : '' }}>
                                        Sell
                                    </option>
                                </select>
                            </div>
                            <div class="form-group col-sm-6">
                                <label>@lang('Volume')</label>
                                <div class="input-group appnend-coin-sym">
                                    <input
                                        type="number"
                                        step="any"
                                        class="form-control"
                                        name="no_of_lot"
                                        value="{{ old('volume',@$order->no_of_lot) }}"
                                        required
                                        >
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label>@lang('Open Price')</label>
                                <div class="input-group appnend-coin-sym">
                                    <input
                                        type="number"
                                        step="any"
                                        class="form-control"
                                        name="rate"
                                        value="{{ old('rate', @$order->rate) }}"
                                        required
                                        >
                                </div>
                            </div>
                            <button type="submit" class="btn btn--primary w-100 h-45 ">@lang('Submit')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection