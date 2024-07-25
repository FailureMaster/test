@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="mb-1">
            <small>Note: Please download the CSV template, edit your data, and then upload the updated file. Thank you.</small>
        </div>
        <form
            action="{{ route('admin.users.import') }}"
            method="POST"
            enctype="multipart/form-data"
            class="import-leads mb-0"
            >
            @csrf
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="inner-wrapper">
                        <input
                            type="file"
                            name="file"
                            id="fileInput"
                            accept=".csv"
                            >
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn--primary w-100 h-45 mt-3" id="submitButton" disabled>@lang('Import CSV')</button>
        </form>
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
<a href="{{ route('admin.users.export') }}" class="btn btn-outline--primary addBtn h-45">
    <i class="las la-download"></i>@lang('Download CSV Template')
</a>
@endpush

@push('script')
<script>
    "use strict";
    (function ($) {
        $(document).ready(function() {
            $('#fileInput').change(function() {
                var fileInput = $(this);
                var submitButton = $('#submitButton');

                if (fileInput.val()) {
                    submitButton.prop('disabled', false);
                } else {
                    submitButton.prop('disabled', true);
                }
            });
        });
    })(jQuery);
</script>
@endpush

@push('style')
<style>
    .import-leads  .inner-wrapper {
        border: 1px solid #4634ff;
        border-radius: .25rem;
        display: flex;
        justify-content: center;
    }

    .import-leads  .inner-wrapper input {
        padding: 32px;
        box-shadow: none !important;
    }

    #submitButton {
        padding: .60rem 5px;
        color: white;
        background: #4634ff;
    }
</style>
@endpush