<div id="takeProfitModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background-color: var(--pane-bg) !important">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Confirmation Alert!')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form action="" method="POST" class="takeProfitModal-form">
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
                                        <input type="text" name="pips" value="10" class="form-control tppips" aria-label="Dollar amount (with dot and two decimal places)">
                                        <span class="input-group-text incrementPips" id="incrementtppips">+</span>
                                        <span class="input-group-text decrementPips" id="decrementtppips">-</span>
                                    </div>
                                    <div style="margin: 0 10px; color: hsl(var(--white))">
                                        <span>@lang('Value')</span>
                                    </div>
                                    <div class="value-container" style="flex: 1">
                                        <span>$</span>
                                        <span class="tppipsequivalent">
                                            100
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="label mb-2">@lang('Price ')</div>
                            <div class="input-group">
                                <input type="text" name="price" class="form-control tpprice">
                                <input type="hidden" class="tp-order-id-hidden-i">
                                <input type="hidden" class="tp-order-side-hidden-i">
                                <input type="hidden" class="tp-lot-equivalent-hidden-i">
                                <span class="input-group-text" id="incrementtpprice">+</span>
                                <span class="input-group-text" id="decrementtpprice">-</span>
                            </div>
                        </div>
                        <div>
                            <div class="label mb-2">@lang('P&L Value')</div>
                            <div class="value-container w-100">
                                <span>$</span>
                                <span class="tpplvalue">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-block mx-auto saveTakeProfit">@lang('Submit Changes')</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script')
<script>
    (function ($) {
        "use strict";

        let isTPUpdateModalContent = true;

        $(document).ready(function() {
            $('#incrementtppips').on('click', incrementPips);
            $('#decrementtppips').on('click', decrementPips);
            $('.tppips').on('input', handlePipsInput);

            $('#incrementtpprice').on('click', incrementPrice);
            $('#decrementtpprice').on('click', decrementPrice);
            $('.tpprice').on('input', handlePriceInput);

            function incrementPips() {
                let value = parseInt($('.tppips').val()) + 1;
                $('.tppips').val(value);
                $('.tppipsequivalent').text(value * 10);

                recalculatePLValue();
            }

            function decrementPips() {
                let value = parseInt($('.tppips').val()) - 1;
                if (value < 0) value = 0;
                $('.tppips').val(value);
                $('.tppipsequivalent').text(value * 10);
                
                recalculatePLValue();
            }

            function handlePipsInput() {
                let value = parseInt($('.tppips').val());
                if (isNaN(value) || value < 0) value = 0;
                $('.tppips').val(value);
                $('.tppipsequivalent').text(value * 10);

                recalculatePLValue();
            }

            function incrementPrice() {
                isTPUpdateModalContent = false;
                let value = parseFloat($('.tpprice').val()) + 0.0001;
                $('.tpprice').val(Number(value).toFixed(5));

                recalculatePLValue();
            }

            function decrementPrice() {
                isTPUpdateModalContent = false;
                let value = parseFloat($('.tpprice').val()) - 0.0001;
                if (value < 0) value = 0;
                $('.tpprice').val(Number(value).toFixed(5));

                recalculatePLValue();
            }

            function handlePriceInput() {
                let value = parseFloat($('.tpprice').val());
                if (isNaN(value) || value < 0) value = 0;
                $('.tpprice').val(value);

                recalculatePLValue();
            }

            function recalculatePLValue() {
                let order = $('.tp-order-side-hidden-i').val();
                let lot_equivalent = $('.tp-lot-equivalent-hidden-i').val();
                let open_price = parseFloat($('.open-price-modal').text());
                let value = parseFloat($('.tpprice').val());

                let plValue = Math.abs(calculatePLValue(order, lot_equivalent, open_price, value)) + parseInt($('.tppipsequivalent').text())

                $('.tpplvalue').text(`${plValue}`);
            }

            $('.saveTakeProfit').on('click', function() {
                $.ajax({
                    type:"POST",
                    url:"{{route('user.order.take.profit')}}",
                    data:{
                        id : $('.tp-order-id-hidden-i').val(),
                        price: parseFloat($('.tpprice').val()),
                        _token: "{{ csrf_token() }}"
                    },
                    success:function(data){
                        notify('success','Take Profit Value Saved!');
                        $('#takeProfitModal').modal('hide');
                    }
                });
            })

            $('#takeProfitModal').on('hidden.bs.modal', function () {
                $(this).find('form').trigger('reset');
            })
        });

        function updateModalContent(order, jsonData) {
            let current_price = jsonData[order.pair.symbol]
            
            let lotValue = order.pair.percent_charge_for_buy;
            
            let lotEquivalent = parseFloat(lotValue) * parseFloat(order.no_of_lot);
            let total_price = parseInt(order.order_side) === 2
                ? formatWithPrecision(((parseFloat(order.rate) - parseFloat(current_price)) * lotEquivalent))
                : formatWithPrecision(((parseFloat(current_price) - parseFloat(order.rate)) * lotEquivalent));

            if (isTPUpdateModalContent) {
                $('.current-price-modal').text(`${parseFloat(current_price)}`);
                $('.tpprice').val(`${parseFloat(current_price)}`);

                $('.tpplvalue').text(`${parseInt($('.tppipsequivalent').text()) + Math.abs(total_price)}`);
            }
        }

        $(document).on('click','.takeProfitModalBtn', function () {
            var modal   = $('#takeProfitModal');
            let data    = $(this).data();
            
            modal.find('.question').text(`${data.question}`);
            modal.find('form').attr('action', `${data.action}`);
            modal.find('.modal-title').text(`${data.title}`);
            
            
            modal.find('.symbol-modal').text(`${data.symbol}`);
            modal.find('.open-price-modal').text(`${data.open}`);
            modal.find('.current-price-modal').text(`${data.curr}`);
            modal.find('.volume-modal').text(`${data.volume}`);

            modal.find('.tpprice').val(`${data.curr}`);
            modal.find('.tp-order-id-hidden-i').val(`${data.orderid}`);
            modal.find('.tp-order-side-hidden-i').val(`${data.side}`);
            modal.find('.tp-lot-equivalent-hidden-i').val(`${data.equivalent}`);

            let plValue = parseInt(100) + parseFloat(Math.abs(calculatePLValue(data.side, data.equivalent, data.open, data.curr)))

            modal.find('.tpplvalue').text(`${plValue}`)

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
    #takeProfitModal .modal-title,
    #takeProfitModal .close {
        color: hsl(var(--white));
    }

    #takeProfitModal .table-sltp thead tr th {
        background-color: var(--pane-bg) !important;
        padding: 10px 3px;
    }

    .takeProfitModal-form input,
    .takeProfitModal-form .input-group-text {
        background-color: transparent;
        color: hsl(var(--white));
        border-color: hsl(var(--white) / 0.2);
    }

    .takeProfitModal-form .label {
        color: hsl(var(--white));
    }

    .takeProfitModal-form .value-container {
        display: flex;
        align-items: center;
        height: 2.8em;
        padding: 0 .7em;
        color: hsl(var(--white));
        background-color: var(--pane-bg-secondary);
        border-color: hsl(var(--white) / 0.2);
        border-radius: .25rem;
    }

    #incrementtppips,
    #decrementtppips,
    #incrementtpprice,
    #decrementtpprice {
        cursor: pointer;
    }
</style>
@endpush