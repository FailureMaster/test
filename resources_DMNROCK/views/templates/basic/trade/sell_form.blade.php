
<!--@php
    $meta                 = (object) $meta;
    $pair                 = @$meta->pair;
    $marketCurrencyWallet = @$meta->marketCurrencyWallet;
    $screen               = @$meta->screen;
@endphp
<form class="buy-sell-form buy-sell @if(@$meta->screen=='small') buy-sell-one @endif buy--form" method="POST">
    @csrf
    @if ($meta->screen=='small')
        <span class="sidebar__close"><i class="fas fa-times"></i></span>
    @endif
    <input type="hidden" name="order_side" value="{{ Status::BUY_SIDE_ORDER }}">
    <input type="hidden" name="order_type" value="{{ Status::ORDER_TYPE_LIMIT }}">
    
    <div class="flex-between buy-sell__wrapper">
        <h7 class="buy-sell__title">@lang('Available')</h7>
        <span class="fs-12">
            <span class="avl-market-cur-wallet">{{ showAmount(@$marketCurrencyWallet->balance) }}</span>
            {{ @$pair->market->currency->symbol }}
            <span class="cursor-pointer new--deposit" data-currency="{{  @$pair->market->currency->symbol }}">
                <i class="las la-plus-circle"></i>
            </span>
        </span>
    </div>
    
    <div class="trading-bottom__button">
        @auth
            <button type="submit" class="btn btn--danger w-100 btn--sm sell-btn">
                 {{-- <input type="number" step="any" class="form--control  style-three sell-rate" name="rate" value="" id="total-buy-amount"> --}}
           <span id="text" style="color:white; float:right;">   </span>
                @lang('SELL') 
            </button>
        @else
            <div class="btn  login-btn w-100 btn--sm">
                <a href="{{ route('user.login') }}">@lang('Login')</a>
                <span>@lang('or')</span>
                <a href="{{ route('user.register') }}">@lang('Register')</a>
            </div>
        @endauth
    </div>
</form>
@push('scripts')
<script>
    (function() {
        var multiplier = 0.0003; // The multiplier

        // Function to calculate and update the value
        function calculateTotal() {
            var titleValue = parseFloat(document.title); // Parse the title value as a float
            var result = titleValue * multiplier + titleValue; // Multiply the title value by the multiplier
            document.getElementById("text").innerText = result.toFixed(5); // Display the result with 2 decimal places
        }

        calculateTotal(); // Call the function initially

        // Update the result periodically
        setInterval(calculateTotal, 500); // Update the result every 500 milliseconds
    })();
</script>

@endpush-->