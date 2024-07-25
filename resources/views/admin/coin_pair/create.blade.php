@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-body p-0">
                <form action="{{ route('admin.coin.pair.save',@$coinPair->id) }}" method="POST"
                    enctype="multipart/form-data" class="pair-form">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group position-relative" id="currency_list_wrapper">
                                    <label>@lang('Symbol')</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="symbol"
                                        value="{{ old('symbol', @$coinPair->symbol) }}"
                                        required
                                        disabled
                                    >
                                </div>
                            </div>
                            <div class="form-gropup col-sm-6" id="market-list">
                                <label>@lang('Market Coin')</label>
                                <select class="form-control select2-basic" required name="market" @if(isset($coinPair->id)) disabled @endif>
                                    <option selected disabled>@lang('Select One')</option>
                                    @php
                                        $selecetdMarketId=old('market',@$coinPair->market_id) ?
                                        old('market',@$coinPair->market_id) :(request()->market_id ?? '');
                                    @endphp
                                    @foreach ($markets as $market)
                                        <option value="{{ $market->id }}" data-cur-sym="{{ $market->currency ? $market->currency->symbol : null }}"                                        @selected($market->id == $selecetdMarketId)>
                                            {{ __($market->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-6">
                                <label>@lang('Lots')</label>
                                
                                <div class="input-group">
                                    <input type="number" step="any" class="form-control" name="percent_charge_for_buy"
                                        value="{{ getAmount(old('percent_charge_for_buy',@$coinPair->percent_charge_for_buy)) }}"
                                        required>
                                    
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label>@lang('Level')</label>
                                
                                <div class="input-group">
                                    <input type="number" step="any" class="form-control" name="level_percent"
                                        value="{{ getAmount(old('level_percent',@$coinPair->level_percent)) }}"
                                        required>
                                    
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label>@lang('Leveregel')</label>
                               
                                <div class="input-group">
                                    <input type="number" step="any" class="form-control" name="percent_charge_for_sell"
                                        value="{{ getAmount(old('percent_charge_for_sell',@$coinPair->percent_charge_for_sell)) }}"
                                        required>
                                    
                                </div>
                            </div>
                            <div class="form-group col-sm-6 d-none">
                                <label>@lang('Listed Market Name')</label>
                                
                                <div class="input-group">
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="listed_market_name"
                                        value="{{ old('listed_market_name',@$coinPair->listed_market_name) }}"
                                        required
                                        >
                                </div>
                            </div>
                            
                        <button type="submit" class="btn btn--primary w-100 h-45 ">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
<a href="{{ route('admin.coin.pair.list') }}" class="btn btn-outline--primary btn-sm">
    <i class="las la-list"></i>@lang('Symbols List')
</a>
@endpush

@push('script')
<script>
    "use strict";
    (function ($) {

        @if (@$coinPair)
            let newOption = new Option("{{@$coinPair->coin->symbol}}-{{@$coinPair->coin->name }}", "{{ @$coinPair->coin_id }}", true, true);
        $('#currency_list').append(newOption).trigger('change');
        $("select[name=coin]").attr('readonly', true);
        coinSym("{{ @$coinPair->coin->symbol }}");
        @endif

        $('select[name=coin]').on('change', function (e) {
            let coin = $(this).find('option:selected').text().split('-');
            let symbol = coin.pop();
            coinSym(symbol);
        });

        function coinSym(coinSym) {
            $.each($('.appnend-coin-sym'), function (i, element) {
                let symbolHtml = $(element).find('.input-group-text');
                if (symbolHtml.length) {
                    symbolHtml.text(coinSym);
                } else {
                    $(element).append(`<span class="input-group-text">${coinSym}</span>`);
                }
            });
        };
        $(document).ready(function() {
    $('#customSymbolToggle').change(function() {
        if ($(this).is(':checked')) {
            $('#symbolInput').removeAttr('disabled');
        } else {
            $('#symbolInput').attr('disabled', 'disabled');
        }
    });
});
    })(jQuery);
</script>
@endpush


@push('style')
<style>
    .select2-container {
        z-index: 99 !important;
    }
</style>
@endpush