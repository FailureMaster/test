@props([
    'isCustom' => false
])
<div id="confirmationModal" class="modal fade @if($isCustom) custom--modal  @endif" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Confirmation Alert!')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="color: hsl(var(--white))">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form action="" method="POST">
                @csrf
                <div class="modal-body">
                    <table class="table table-close-order">
                        <thead>
                            <tr>
                              <th class="text-center">@lang('Symbol')</th>
                              <th class="text-center">@lang('Open Price')</th>
                              <th class="text-center">@lang('Current Price')</th>
                              <th class="text-center">@lang('Volume')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                              <td class="symbol-modal text-center"></td>
                              <td class="open-price-modal text-center"></td>
                              <td class="current-price-modal text-center"></td>
                              <td class="volume-modal text-center"></td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div class="mb-5"></div>
                    
                    <p class="question text-center"></p>
                    
                    <div class="mb-1"></div>
                    
                    <h3 class="profit-modal text-center"></h3>
                    
                    <div class="mb-3"></div>
                    <input type="hidden" id="orderId" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn {{ $isCustom ? 'btn-dark btn--dark btn--sm' :  'btn--dark' }}" data-bs-dismiss="modal">@lang('No')</button>
                    <button type="submit" class="btn {{ $isCustom ? 'btn--base btn--sm' :  'btn--primary' }}  ">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script')

<script>
    (function ($) {
        "use strict";

        function updateModalContent(order, jsonData) {
            var modal = $('#confirmationModal');

            let current_price = jsonData[order.pair.symbol]
            
            let lotValue = order.pair.percent_charge_for_buy;
            
            let lotEquivalent = parseFloat(lotValue) * parseFloat(order.no_of_lot);
            let total_price = parseInt(order.order_side) === 2
                ? formatWithPrecision(((parseFloat(order.rate) - parseFloat(current_price)) * lotEquivalent))
                : formatWithPrecision(((parseFloat(current_price) - parseFloat(order.rate)) * lotEquivalent));

            let profitModal = modal.find('.profit-modal');
            let profitValue = total_price;
            profitModal.text(`\$ ${profitValue}`);

            if (parseFloat(profitValue) <= 0) {
                profitModal.removeClass('text-success').addClass('text-danger'); // Add red class for negative profit
            } else if (profitValue > 0) {
                profitModal.removeClass('text-danger').addClass('text-success'); // Add green class for positive profit
            }
        }

        $(document).on('click','.confirmationBtn', function () {
            var modal   = $('#confirmationModal');
            let data    = $(this).data();
            
            modal.find('.question').text(`${data.question}`);
            modal.find('form').attr('action', `${data.action}`);
            modal.find('.modal-title').text(`${data.title}`);
            
            
            modal.find('.symbol-modal').text(`${data.symbol}`);
            modal.find('.open-price-modal').text(`${data.open}`);
            modal.find('.current-price-modal').text(`${data.curr}`);
            modal.find('.volume-modal').text(`${data.volume}`);
            
            let profitModal = modal.find('.profit-modal');
            let profitValue = data.profit;
            profitModal.text(`\$ ${profitValue}`);
            
            if (parseFloat(profitValue) <= 0) {
                profitModal.removeClass('text-success').addClass('text-danger'); // Add red class for negative profit
            } else if (profitValue > 0) {
                profitModal.removeClass('text-danger').addClass('text-success'); // Add green class for positive profit
            }

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

        $('#confirmationModal form').on('submit', function (e) {

            var form = $(this);
            var submitBtn = form.find('[type="submit"]');
            submitBtn.prop('disabled', true); 

            submitBtn.append(' <i class="fa fa-spinner fa-spin"></i>');

            setTimeout(function () {
                submitBtn.prop('disabled', false);

                submitBtn.html('@lang("Yes")');
            }, 2000);
        });
    })(jQuery);
</script>
@endpush
