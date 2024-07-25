<div id="stopLossModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background-color: var(--pane-bg) !important">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Confirmation Alert!')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form class="stopLossModal-form">
                @csrf
                <div class="modal-body">
                    <table class="table table-sltp">
                        <thead>
                            <tr>
                              <th class="text-center">@lang('Symbol')</th>
                              <th class="text-center">@lang('Open Price')</th>
                              <th class="text-center">@lang('Current Price')</th>
                              <th class="text-center">@lang('Volume')</th>
                            </tr>
                        </thead>
                        <tbody style="background-color: var(--pane-bg) !important">
                            <tr>
                              <td class="symbol-modal text-center" style="color: hsl(var(--white));"></td>
                              <td class="open-price-modal text-center" style="color: hsl(var(--white));"></td>
                              <td class="current-price-modal text-center" style="color: hsl(var(--white));"></td>
                              <td class="volume-modal text-center" style="color: hsl(var(--white));"></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="container mt-5">
                        <div class="mb-3">
                            <div class="label mb-2">@lang('Pips')</div>
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center w-100">
                                    <div class="input-group" style="flex: 1">
                                        <input type="number" name="pips" value="10" class="form-control slpips">
                                        <span class="input-group-text" id="incrementslpips">+</span>
                                        <span class="input-group-text" id="decrementslpips">-</span>
                                    </div>
                                    <div style="margin: 0 10px; color: hsl(var(--white))">
                                        <span>@lang('Value')</span>
                                    </div>
                                    <div class="value-container" style="flex: 1">
                                        <span>-$</span>
                                        <span class="slpipsequivalent">
                                            100
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="label mb-2">@lang('Price ')</div>
                            <div class="input-group">
                                <input type="text" name="price" class="form-control slprice">
                                <input type="hidden" class="sl-order-id-hidden-i">
                                <input type="hidden" class="sl-order-side-hidden-i">
                                <input type="hidden" class="sl-lot-equivalent-hidden-i">
                                <span class="input-group-text" id="incrementslprice">+</span>
                                <span class="input-group-text" id="decrementslprice">-</span>
                            </div>
                        </div>
                        <div>
                            <div class="label mb-2">@lang('P&L Value')</div>
                            <div class="value-container w-100">
                                <span>-$</span>
                                <span class="plvalue"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-block mx-auto saveStopLoss">@lang('Submit Changes')</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script')
<script>
    (function ($) {
        "use strict";

        let isSLUpdateModalContent = true;

        $(document).ready(function() {
            $('#incrementslpips').on('click', incrementPips);
            $('#decrementslpips').on('click', decrementPips);
            $('.slpips').on('input', handlePipsInput);

            $('#incrementslprice').on('click', incrementPrice);
            $('#decrementslprice').on('click', decrementPrice);
            $('.slprice').on('input', handlePriceInput);

            function incrementPips() {
                isSLUpdateModalContent = true;
                let value = parseInt($('.slpips').val()) + 1;
                $('.slpips').val(value);
                $('.slpipsequivalent').text(value * 10);

                recalculatePLValue();
            }

            function decrementPips() {
                isSLUpdateModalContent = true;
                let value = parseInt($('.slpips').val()) - 1;
                if (value < 0) value = 0;
                $('.slpips').val(value);
                $('.slpipsequivalent').text(value * 10);

                recalculatePLValue();
            }

            function handlePipsInput() {
                let value = parseInt($('.slpips').val());
                if (isNaN(value) || value < 0) value = 0;
                $('.slpips').val(value);
                $('.slpipsequivalent').text(value * 10);
            }

            function incrementPrice() {
                isSLUpdateModalContent = false;
                let value = parseFloat($('.slprice').val()) + 0.0001;
                $('.slprice').val(Number(value).toFixed(5));

                recalculatePLValue();
            }

            function decrementPrice() {
                isSLUpdateModalContent = false;
                let value = parseFloat($('.slprice').val()) - 0.0001;
                if (value < 0) value = 0;
                $('.slprice').val(Number(value).toFixed(5));

                recalculatePLValue();
            }

            function handlePriceInput() {
                let value = parseFloat($('.slprice').val());
                if (isNaN(value) || value < 0) value = 0;
                $('.slprice').val(value);

                recalculatePLValue();
            }

            function recalculatePLValue() {
                let order = $('.sl-order-side-hidden-i').val();
                let lot_equivalent = $('.sl-lot-equivalent-hidden-i').val();
                let open_price = parseFloat($('.open-price-modal').text());
                let value = parseFloat($('.slprice').val());

                let plValue = Math.abs(calculatePLValue(order, lot_equivalent, open_price, value)) + parseInt($('.slpipsequivalent').text())

                $('.plvalue').text(`${plValue}`);
            }

            $('.saveStopLoss').on('click', function() {
                $.ajax({
                    type:"POST",
                    url:"{{route('user.order.stop.loss')}}",
                    data:{
                        id : $('.sl-order-id-hidden-i').val(),
                        price: parseFloat($('.slprice').val()),
                        _token: "{{ csrf_token() }}"
                    },
                    success:function(data){
                        notify('success','Stop Loss Value Saved!');
                        $('#stopLossModal').modal('hide');
                    }
                });
            })

            $('#stopLossModal').on('hidden.bs.modal', function () {
                $(this).find('form').trigger('reset');
            })
        });

        function plFinalCalculation(profitloss, pips) {
            let plValue = parseInt(pips) + parseFloat(profitloss)
            $('.plvalue').text(`${plValue}`);
        }

        function updateModalContent(order, jsonData) {
            var modal = $('#confirmationModal');

            let current_price = jsonData[order.pair.symbol]
            
            let lotValue = order.pair.percent_charge_for_buy;
            
            let lotEquivalent = parseFloat(lotValue) * parseFloat(order.no_of_lot);
            let total_price = parseInt(order.order_side) === 2
                ? formatWithPrecision(((parseFloat(order.rate) - parseFloat(current_price)) * lotEquivalent))
                : formatWithPrecision(((parseFloat(current_price) - parseFloat(order.rate)) * lotEquivalent));

            if (isSLUpdateModalContent) {
                $('.current-price-modal').text(`${parseFloat(current_price)}`);
                $('.slprice').val(`${parseFloat(current_price)}`);
                $('.plvalue').text(`${parseInt($('.slpipsequivalent').text()) + Math.abs(total_price)}`);
            }
        }

        $(document).on('click','.stopLossModalBtn', function () {
            console.log('TRIGGERED')
            var modal   = $('#stopLossModal');
            let data    = $(this).data();
            
            modal.find('.question').text(`${data.question}`);
            modal.find('form').attr('action', `${data.action}`);
            modal.find('.modal-title').text(`${data.title}`);
            
            modal.find('.symbol-modal').text(`${data.symbol}`);
            modal.find('.open-price-modal').text(`${data.open}`);
            modal.find('.current-price-modal').text(`${data.curr}`);
            modal.find('.volume-modal').text(`${data.volume}`);

            modal.find('.slprice').val(`${data.curr}`);
            modal.find('.sl-order-id-hidden-i').val(`${data.orderid}`);
            modal.find('.sl-order-side-hidden-i').val(`${data.side}`);
            modal.find('.sl-lot-equivalent-hidden-i').val(`${data.equivalent}`);

            let plValue = parseInt(100) + parseFloat(Math.abs(calculatePLValue(data.order, data.equivalent, data.open, data.curr)))
            console.log(plValue)

            modal.find('.plvalue').text(`${plValue}`)

            setInterval(function () {
                let actionUrl = `{{ route('trade.order.fetchModalProfit', ['id' => ':id']) }}`
                actionUrl = actionUrl.replace(':id', data.orderid)

                $.ajax({
                    url: actionUrl,
                    method: 'GET',
                    success: function(response) {
                        let jsonMarketData = response.marketData;
                        let order = response.order;

                        updateModalContent(order, jsonMarketData[order.pair.type]);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }, 1000);
            
            modal.modal('show');
        });

        function calculatePLValue(orderSide, lotEquivalent, rate, currentPrice) {
            let totalPrice = parseInt(orderSide) === 2
                ? formatWithPrecision(((parseFloat(rate) - parseFloat(currentPrice)) * lotEquivalent))
                : formatWithPrecision(((parseFloat(currentPrice) - parseFloat(rate)) * lotEquivalent));

            return totalPrice;
        }

        function formatWithPrecision(value, precision = 5) {
            return Number(value).toFixed(precision);
        }
    })(jQuery);
</script>
@endpush

@push('style')
<style>
    #stopLossModal .modal-title,
    #stopLossModal .close {
        color: hsl(var(--white));
    }

    #stopLossModal .table-sltp thead tr th {
        background-color: var(--pane-bg) !important;
        padding: 10px 3px;
    }

    .stopLossModal-form input,
    .stopLossModal-form .input-group-text {
        background-color: transparent;
        color: hsl(var(--white));
        border-color: hsl(var(--white) / 0.2);
    }

    .stopLossModal-form .label {
        color: hsl(var(--white));
    }

    .stopLossModal-form .value-container {
        display: flex;
        align-items: center;
        height: 2.8em;
        padding: 0 .7em;
        color: hsl(var(--white));
        background-color: var(--pane-bg-secondary);
        border-color: hsl(var(--white) / 0.2);
        border-radius: .25rem;
    }

    #incrementslpips,
    #decrementslpips,
    #incrementslprice,
    #decrementslprice {
        cursor: pointer;
    }
</style>
@endpush