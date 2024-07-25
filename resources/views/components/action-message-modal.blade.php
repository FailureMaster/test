<div id="actionMessageModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white">@lang('Confirmation Alert!')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form action="" method="POST">
                @csrf
                <div class="modal-body">
                    
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
        $(document).on('click','.confirmationBtn', function () {
            var modal   = $('#actionMessageModal');
            let data    = $(this).data();
            
            modal.modal('show');
        });
    })(jQuery);
</script>
@endpush
