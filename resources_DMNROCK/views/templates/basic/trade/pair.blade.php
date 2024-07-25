@php
    $meta = (object) $meta;
    $pair = @$meta->pair;

 $symbol = str_replace("_","",$pair->symbol);
 $listed_market_name = $pair->listed_market_name;
@endphp
<div class=" @if (@$meta->screen == 'small') col-sm-12  d-xl-none d-block @else d-xl-block d-none @endif ">
    <div class="trading-header skeleton selected-pair">
        <iframe src="https://crm.daimondrock.com/singleticker.html?locale=en&listed_market_name={{$listed_market_name}}&symbol={{$symbol}}" frameborder="0" style="width: 100%; height: 60px;"></iframe>
    </div>
    <script>
        // Find the iframe element
var iframe = document.getElementById('watchlistsgogogo');

// Add an event listener to intercept link clicks within the iframe
window.addEventListener('message', function(event) {
  if (event.source === iframe.contentWindow && event.data.type === 'linkClick') {
    var targetUrl = event.data.url;
    // Handle the link click by updating the iframe's URL
    iframe.src = targetUrl;
    iframe.target = "_top';
  }
});

// Within the content of the iframe, add JavaScript to send click events to the parent window
// Example JavaScript within the iframe content to send link clicks to the parent window
document.addEventListener('click', function(e) {
  if (e.target.tagName === 'A') {
       
    console.log("==================================================================")
    e.preventDefault(); // Prevent the default behavior of the link click
    var linkUrl = e.target.href;
    // Send a message to the parent window with the link URL
    window.parent.postMessage({ type: 'linkClick', url: linkUrl }, '*');
  }
});

    </script>
</div>

@push('script')
    <script>
        "use strict";
        (function($) {
            setTimeout(() => {
                $('.selected-pair').removeClass('skeleton');
            }, 1500);
        })(jQuery);
    </script>
@endpush
