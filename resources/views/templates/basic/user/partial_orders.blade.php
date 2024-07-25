@forelse ($recentOrders as $recentOrder)
    <div class="transection__item skeleton">
        <div class="d-flex flex-wrap align-items-center">
            <div class="transection__date">
                <h6 class="transection__date-number text-white">
                    {{ showDateTime($recentOrder->created_at, 'd') }}
                </h6>
                <span class="transection__date-text">
                    {{ __(strtoupper(showDateTime($recentOrder->created_at, 'M'))) }}
                </span>
            </div>
            <div class="transection__content">
                <h6 class="transection__content-title">
                    @php echo $recentOrder->orderSideBadge; @endphp
                </h6>
                <p class="transection__content-desc">
                    @lang('Placed an order in the ')
                    {{ @$recentOrder->pair->symbol }} @lang('pair to')
                    {{ __(strtolower(strip_tags($recentOrder->orderSideBadge))) }}
                    {{ showAmount($recentOrder->amount) }}
                    {{ @$recentOrder->pair->coin->symbol }}
                </p>
            </div>
        </div>
        @php echo $recentOrder->statusBadge; @endphp
    </div>
@empty
    <div class="transection__item justify-content-center p-5 skeleton">
        <div class="empty-thumb text-center">
            <img src="{{ asset('assets/images/extra_images/empty.png') }}" />
            <p class="fs-14">@lang('No order found')</p>
        </div>
    </div>
@endforelse