<div class="mb-4">
    <form method="GET" action="{{ $currentUrl }}">
        <div class="btn-group d-flex w-100 mb-2" role="group" aria-label="Basic example">
            <button
                type="submit"
                name="filter"
                value="today"
                class="btn btn-lg btn-custom-border {{ $currentFilter == 'today' ? 'btn-primary' : 'btn-outline-primary' }}"
                >
                Today
            </button>
            <button
                type="submit"
                name="filter"
                value="yesterday"
                class="btn btn-lg btn-custom-border {{ $currentFilter == 'yesterday' ? 'btn-primary' : 'btn-outline-primary' }}"
                >
                Yesterday
            </button>
            <button
                type="submit"
                name="filter"
                value="this_week"
                class="btn btn-lg btn-custom-border {{ $currentFilter == 'this_week' ? 'btn-primary' : 'btn-outline-primary' }}"
                >
                This Week
            </button>
            <button
                type="submit"
                name="filter"
                value="last_week"
                class="btn btn-lg btn-custom-border {{ $currentFilter == 'last_week' ? 'btn-primary' : 'btn-outline-primary' }}"
                >
                Last Week
            </button>
            <button
                type="submit"
                name="filter"
                value="this_month"
                class="btn btn-lg btn-custom-border {{ $currentFilter == 'this_month' ? 'btn-primary' : 'btn-outline-primary' }}"
                >
                This Month
            </button>
            <button
                type="submit"
                name="filter"
                value="last_month"
                class="btn btn-lg btn-custom-border {{ $currentFilter == 'last_month' ? 'btn-primary' : 'btn-outline-primary' }}"
                >
                Last Month
            </button>
            <a
                id="customFilterButton"
                class="btn btn-lg btn-custom-border {{ $currentFilter == 'custom' ? 'btn-primary' : 'btn-outline-primary' }}"
                data-bs-toggle="modal"
                data-bs-target="#customDateFilterModal"
                >
                Custom
            </a>
        </div>
    </form>
</div>
<div class="modal fade" id="customDateFilterModal" tabindex="-1" role="dialog" aria-labelledby="customDateFilterLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customDateFilterLabel">
                    By Date
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="customDateFilterForm" action="{{ url()->current() }}" method="GET">
                    <div class="flex-grow-1">
                        <label>@lang('Sart date - End date')</label>
                        <input
                            name="customfilter"
                            data-range="true"
                            data-multiple-dates-separator=" - "
                            data-language="en"
                            class="customDateFilterInput form-control"
                            data-position='bottom right'
                            placeholder="@lang('Start date - End date')"
                            autocomplete="off"
                            value="{{ request()->date }}"
                            >
                    </div>
                    <div class="my-3">
                        <button type="submit" class="btn-lg btn-primary w-100">Start Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('style')
    <style>
        .btn-custom-border {
            border: none;
            border-bottom: 1px solid;
        }

        .btn-outline-primary.btn-custom-border {
            border-bottom: 1px solid #007bff; /* match the border color to the outline-primary color */
        }

        .btn-primary.btn-custom-border {
            border-bottom: 1px solid #007bff; /* match the border color to the primary color */
        }

        .datepickers-container {
            z-index: 10000 !important;
        }
    </style>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{asset('assets/admin/css/vendor/datepicker.min.css')}}">
@endpush

@push('script-lib')
  <script src="{{ asset('assets/admin/js/vendor/datepicker.min.js') }}"></script>
  <script src="{{ asset('assets/admin/js/vendor/datepicker.en.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($){
            "use strict";
            if(!$('.customDateFilterInput').val()){
                $('.customDateFilterInput').datepicker();
            }
        })(jQuery)
    
        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            
            const customFilter = urlParams.get('customfilter');
    
            if (customFilter) {
                const decodedCustomFilter = decodeURIComponent(customFilter);
    
                const dateRange = decodedCustomFilter.split(' - ');
    
                const formattedStartDate = formatDate(dateRange[0]);
                const formattedEndDate = formatDate(dateRange[1]);
    
                if (formattedStartDate && formattedEndDate) {
                    const button = document.getElementById("customFilterButton");
                    button.innerHTML = `<i class="far fa-calendar"></i> ${formattedStartDate} - <i class="far fa-calendar"></i> ${formattedEndDate}`;
                    button.classList.add('btn-primary');
                    button.classList.add('text-white');
                } else {
                    console.error('Invalid date range format in customfilter parameter.');
                }
            }
        });
    
        function formatDate(dateString) {
            const date = new Date(dateString);
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = String(date.getFullYear()).slice(-2);
            return `${month}-${day}-${year}`;
        }
    </script>
@endpush