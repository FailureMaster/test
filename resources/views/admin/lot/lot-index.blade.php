@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('ID')</th>
                                    <th>@lang('Type')</th>
                                    <th>@lang('Listed Market Name')</th>
                                    <th>@lang('Symbol')</th>
                                    <th>@lang('Price')</th>
                                    <th>@lang('Actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pairs as $pair)
                                    <tr>
                                        <td>{{ $pair->id }}</td>
                                        <td>{{ $pair->type }}</td>
                                        <td>{{ $pair->listed_market_name }}</td>
                                        <td>{{ $pair->symbol }}</td>
                                        <td>{{ showAmount($pair->marketData->price) }}</td>
                                        <td>
                                            <div class="d-inline-block text-nowrap">
                                                <span class="action-tooltip" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="left" title="Edit">
                                                    <button class="btn btn--xs btn--primary btn-icon edit-pair open-form-btn" data-id="{{$pair->id}}" data-bs-toggle="offcanvas"
                                                    data-bs-target="#lotOffcanvas" aria-controls="editLog"><i class="las la-edit pe-0"></i></button>
                                                </span>
                                            </div>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($pairs->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($pairs) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="lotOffcanvas" aria-labelledby="addLogLabel">
        <div class="offcanvas-header">
            <h5 id="actualIncomeLabel" class="offcanvas-title">Lot Setup</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body mx-0 flex-grow-0" id="actualIncomeBody">
            <form class="form-repeater" id="form-repeater">
                @csrf
                <div data-repeater-list="group-a">
                    <div data-repeater-item>
                        <div class="row">
                            <div class="mb-3 col-3 mb-0 pe-0">
                                <input class="form-control numeral-mask numeral-maxlength p-2" type="number"
                                id="form-repeater-1-1" placeholder="Lot" maxlength="10"
                                autocomplete="off" name="actual_income" />
                            </div>
                            <div class="mb-3 col-7 mb-0 pe-0">
                                <input class="form-control numeral-mask numeral-maxlength p-2" type="number"
                                        id="form-repeater-1-2" placeholder="Pips Value" maxlength="10"
                                        autocomplete="off" name="actual_income" />
                            </div>
                            <div class="mb-3 col-lg-2 col-xl-2 col-2 mt-0 py-1">
                                <button class="btn btn-sm btn-danger py-2" data-repeater-delete="">
                                    <i class="las la-times px-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 pe-3">
                        <div class="mb-0 float-end">
                            <button class="btn btn-sm btn-primary py-2" data-repeater-create>
                                <i class="las la-plus px-1"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-12 pe-0">
                        <div class="mb-0">
                            <hr>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-12 pe-1">
                        <div class="mb-0 d-grid gap-2">
                            <button class="btn btn--primary" type="submit">SAVE</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <div class="d-flex flex-wrap gap-2 justify-content-between">
        <x-search-form placeholder="Enter Market Name or Symbol" />
        <form>
            <div class="input-group">
                <select name="type" class="form-control">
                    <option value="" disabled selected>@lang('Select Pair Type')</option>
                    <option value="Stocks">@lang('Stocks')</option>
                    <option value="FOREX">@lang('FOREX')</option>
                    <option value="INDEX">@lang('INDEX')</option>
                    <option value="Crypto">@lang('Crypto')</option>
                    <option value="COMMODITY">@lang('COMMODITY')</option>
                </select>
                {{-- <button class="btn btn--primary input-group-text" type="submit"><i class="la la-search"></i></button> --}}
            </div>
        </form>
    </div>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {

            $(`select[name=type]`).on('change', function(e) {
                $(this).closest('form').submit();
            });

            @if (request()->type)
                $(`select[name=type]`).val("{{ request()->type }}");
            @endif ()

        })(jQuery);
    </script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jquery-repeater/jquery-repeater.js') }}"></script>
    <script>
        /**
         * Initialize Select2
         * 
         * */ 
        $(".select2").select2({
            dropdownParent: $('#lot-oc-body')
        });
        
        /**
         * Initialize For Repeater
         * 
         * */ 
        var formRepeater = $(".form-repeater");

        var row = 2;
        var col = 1;
        formRepeater.on('submit', function(e) {
            e.preventDefault();
        });
        formRepeater.repeater({
        show: function() {
            var formControl = $(this).find('.form-control, .form-select');
            var formLabel = $(this).find('.form-label');

            formControl.each(function(i) {
                var id = 'form-repeater-' + row + '-' + col;
                $(formControl[i]).attr('id', id);
                $(formLabel[i]).attr('for', id);
                col++;
            });

            row++;

            $(this).slideDown();
        },
        hide: function(e) {
            $(this).slideUp(e)
        }
        });
    </script>
@endpush


@push('style')
    <style>
        .progress {
            height: 9px;
        }
        .select2-container .select2-selection--single {
            height: 100% !important;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endpush
