<div id="frozeAccountModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content" style="background-color: var(--pane-bg) !important">
            <div class="modal-header">
                <h2></h2>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <div class="modal-body py-2">
                <h3 class="modal-title text-themed">You are currently trading on a Demo account.</h3>
                <br>
                <p class="text-themed">In order to make a Deposit please switch to a Real account.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn--base w-100" data-bs-dismiss="modal">
                    @lang('Close')
                </button>
            </div>
        </div>
    </div>
</div>
@push('style')
<style>
    #frozeAccountModal .close {
        color: hsl(var(--white));
    }
    
    #frozeAccountModal .modal-body {
        min-height: 400px;
    }
    
    #frozeAccountModal h3 {
        font-size: 32px;
    }
</style>
@endpush