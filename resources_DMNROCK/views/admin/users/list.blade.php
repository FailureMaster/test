@php
    $currentFilter = request('filter');
@endphp
@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card responsive-filter-card mb-4">
            <div class="card-body">
                <div class="mb-4">
                    <form method="GET" action="{{ url()->current() }}">
                        <div class="btn-group d-flex w-100 mb-2" role="group" aria-label="Basic example">
                            <button type="submit" name="filter" value="today" class="btn btn-lg btn-custom-border {{ $currentFilter == 'today' ? 'btn-primary' : 'btn-outline-primary' }}">Today</button>
                            <button type="submit" name="filter" value="yesterday" class="btn btn-lg btn-custom-border {{ $currentFilter == 'yesterday' ? 'btn-primary' : 'btn-outline-primary' }}">Yesterday</button>
                            <button type="submit" name="filter" value="this_week" class="btn btn-lg btn-custom-border {{ $currentFilter == 'this_week' ? 'btn-primary' : 'btn-outline-primary' }}">This Week</button>
                            <button type="submit" name="filter" value="last_week" class="btn btn-lg btn-custom-border {{ $currentFilter == 'last_week' ? 'btn-primary' : 'btn-outline-primary' }}">Last Week</button>
                            <button type="submit" name="filter" value="this_month" class="btn btn-lg btn-custom-border {{ $currentFilter == 'this_month' ? 'btn-primary' : 'btn-outline-primary' }}">This Month</button>
                            <button type="submit" name="filter" value="last_month" class="btn btn-lg btn-custom-border {{ $currentFilter == 'last_month' ? 'btn-primary' : 'btn-outline-primary' }}">Last Month</button>
                            <a id="customFilterButton" class="btn btn-lg btn-custom-border {{ $currentFilter == 'custom' ? 'btn-primary' : 'btn-outline-primary' }}" data-bs-toggle="modal" data-bs-target="#customDateFilterModal">Custom</a>
                        </div>
                    </form>
                </div>
                <div>
                <form action="">
                    <div class="d-flex gap-2">
                        <div class="flex-grow-1">
                            <label>@lang('ID')</label>
                            <input type="number" name="lead_code" value="{{ request()->lead_code }}" class="form-control">
                        </div>
                        <div class="flex-grow-1">
                            <label>@lang('email')</label>
                            <input type="text" name="email" value="{{ request()->email }}" class="form-control">
                        </div>
                        <div class="flex-grow-1">
                            <label>@lang('mobile')</label>
                            <input type="number" name="mobile" value="{{ request()->mobile }}" class="form-control">
                        </div>
                        <div class="flex-grow-1">
                            <label>@lang('Country')</label>
                            <select name="country_code" class="form-control">
                                <option value="">@lang('Select One')</option>
                                @foreach ($countries as $key => $country)
                                    <option value="{{ $key  }}" @selected(request()->country_code == $key)>
                                        {{ __(keyToTitle($country->country)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-grow-1">
                            <label>@lang('Status')</label>
                            <select name="sales_status" class="form-control">
                                <option value="">@lang('Select One')</option>
                                @foreach ($salesStatuses as $status)
                                    <option value="{{ $status->name }}" @selected(request()->sales_status == $status->name)>
                                        {{ __(keyToTitle($status->name)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-grow-1">
                            <label>@lang('Owner')</label>
                            <select name="owner_id" class="form-control">
                                <option value="">@lang('Select One')</option>
                                @foreach ($admins as $admin)
                                    <option value="{{ $admin->id }}" @selected(request()->owner_id == $admin->id)>
                                        {{ __(keyToTitle($admin->name)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-grow-1 align-self-end">
                            <button class="btn btn--primary w-100 h-45">
                                <i class="la la-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
                <div class="table-responsive--md  table-responsive">
                    <table class="table table--light style--two highlighted-table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Star</th>
                                <th>ID</th>
                                <th>Online</th>
                                <th>Type</th>
                                <th>@lang('First Name')</th>
                                <th>@lang('Last Name')</th>
                                <th>@lang('Phone')</th>
                                <th>@lang('Email')</th>
                                <th>@lang('Country')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Registered')</th>
                                @if(can_access('manage-sales-leads|manage-retention-leads'))
                                    <th>@lang('Owner')</th>
                                    <th>@lang('IP')</th>
                                @endif
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>
                                    <input
                                        type="checkbox"
                                        class="form-check-input"
                                        id="flexCheckDefault"
                                        name="selectUsers[]"
                                        value="{{ $user->id }}"
                                        >
                                </td>
                                <td>
                                    <form id="favoriteForm"
                                        action="{{ route('admin.users.toggle.favorite', $user->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" style="background: none; border: none; cursor: pointer;">
                                            @if ($user->favorite)
                                            <i class="fa fa-star" style="color: gold;"></i>
                                            <!-- Using Font Awesome Icons -->
                                            @else
                                            <i class="fa fa-star" style="color: grey;"></i>
                                            @endif
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <span class="fw-bold">
                                        {{ $user->lead_code ?? $user->id }}
                                        <a href="{{ route('admin.users.detail', $user->id) . ($history == 'clients' ? '?back=clients' : '?back=leads') }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </span>
                                </td>
                                <td>
                                    @if(($user->last_request && (\Carbon\Carbon::parse($user->last_request)->gt(\Carbon\Carbon::now()->subMinutes(5)))))
                                        <span class="badge-userstatus badge-online">●</span>
                                    @else
                                        <span class="badge-userstatus badge-offline">●</span>
                                    @endif
                                </td>
                                <td>
                                    @if(can_access('change-user-type'))
                                        <form id="userTypeForm" action="{{ route('admin.users.toggle.type', $user->id) }}"
                                            method="POST">
                                            @csrf
                                            <button type="submit" style="background: none; border: none; cursor: pointer;">
                                                @if ($user->account_type == 'real')
                                                    <span class="badge-userstatus badge-online">Real</span>
                                                @else
                                                    <span class="badge-userstatus badge-offline">Demo</span>
                                                @endif
                                            </button>
                                        </form>
                                    @elseif ($user->account_type == 'real')
                                        <span class="badge-userstatus badge-online">Real</span>
                                    @else
                                        <span class="badge-userstatus badge-offline">Demo</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bold">{{$user->firstname}}</span>
                                </td>
                                <td>
                                    <span class="fw-bold">{{$user->lastname}}</span>
                                </td>
                                <td>
                                    <span class="d-block"></span>
                                    {{ $user->mobile }}
                                </td>
                                 <td>
                                    <span class="d-block">
                                        {{ $user->email }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold" title="{{ @$user->address->country }}">
                                        {{ $user->country_code }}
                                    </span>
                                    <img src="https://flagcdn.com/24x18/{{ Illuminate\Support\Str::lower($user->country_code) }}.png" width="12" height="12">
                                </td>
                                <td>
                                    <form id="editStatusFormInline" action="/admin/users/update-status/{{$user->id}}" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <select
                                                class="form-select"
                                                name="status"
                                                id="userStatusInline"
                                                value="{{$user->sales_status}}"
                                                onchange="this.form.submit()"
                                                style="width: 200px"
                                                >
                                                <option disabled>@lang('Select One')</option>
                                                @foreach ($salesStatuses as $status)
                                                    <option value="{{ $status->name }}" {{ $status->name == $user->sales_status ? 'selected' : '' }}>
                                                        {{ $status->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    {{ showDateTime($user->created_at, 'd-m-y - H:i') }} 
                                </td>
                                @if(can_access('manage-sales-leads|manage-retention-leads'))
                                    <td>
                                        <form id="editOwnerFormInline" action="/admin/users/update-owner/{{$user->id}}"
                                            method="post">
                                            @csrf
                                            <div class="form-group">
                                                <!-- <label for="userOwner" class="col-form-label">Owner:</label> -->
                                                <select
                                                    class="form-select"
                                                    name="owner"
                                                    id="userOwnerInline"
                                                    onchange="this.form.submit()"
                                                    style="width: 200px"
                                                    >
                                                    <option disabled>@lang('Select One')</option>
                                                    <option value="0">No Owner</option>
                                                    @foreach ($admins as $admin)
                                                        <option value="{{ $admin->id }}" id="inline-option-{{$admin->id}}" {{ $admin->id == $user->owner_id ? 'selected' : '' }}>
                                                            {{ $admin->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <!-- <button type="submit" class="btn btn-primary">Save changes</button> -->
                                        </form>
                                    </td>
                                @endif
                                <td>
                                    <span class="d-block">
                                        {{ $user->user_ip ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="button--group">
                                        @if(can_access('delete-user'))
                                            <button
                                                type="button"
                                                class="btn btn-outline--primary"
                                                data-bs-target="#actionMessageModal{{ $user->id }}"
                                                data-bs-toggle="modal"
                                                >
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        @endif

                                        <button type="button" data-target="#commentModal"
                                            class="btn {{$user->comment ? 'btn--primary':'btn-outline--primary'}} edit-comment-btn"
                                            data-toggle="modal" data-comment="{{ $user->comment }}"
                                            data-userid="{{ $user->id }}">
                                            <i class="far fa-comments"></i>
                                        </button>

                                        <a href="{{ route('admin.users.detail', $user->id) . ($history == 'clients' ? '?back=clients' : '?back=leads') }}"
                                            class="btn btn-sm btn-outline--primary">
                                            <i class="fas fa-desktop"></i>
                                            <!-- @lang('Details') -->
                                        </a>
                                        @if (request()->routeIs('admin.users.kyc.pending'))
                                            <a href="{{ route('admin.users.kyc.details', $user->id) }}" target="_blank"
                                                class="btn btn-sm btn-outline--dark">
                                                <i class="las la-user-check"></i>@lang('KYC Data')
                                            </a>
                                        @endif
                                    </div>
                                </td>
                                <div id="actionMessageModal{{ $user->id }}" class="modal fade" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-sm" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Delete User {{ $user->username }}</h5>
                                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                    <i class="las la-times"></i>
                                                </button>
                                            </div>
                                            <form
                                                action="{{ route('admin.users.delete', $user->id) }}"
                                                method="POST"
                                                >
                                                @csrf
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('No')</button>
                                                    <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table><!-- table end -->
                </div>
            </div>
            @if ($users->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($users) }}
                </div>
            @endif
        </div>
    </div>

    {{-- Add Sub Balance MODAL --}}
    <div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="editCommentLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCommentLabel">Comment</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editCommentForm" action="" method="post">
                        @csrf
                        <input type="hidden" id="commentUserId" name="userId" value="">
                        <div class="form-group">
                            <label for="userComment" class="col-form-label">Comment:</label>
                            <textarea class="form-control" placeholder="@lang('Comment')" name="comment" type="text"
                                rows="4" id="userComment" value=""></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- Add Sub Balance MODAL --}}
    <div class="modal fade" id="ownerModal" tabindex="-1" role="dialog" aria-labelledby="editOwnerLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editOwnerLabel">Owner</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editOwnerForm" action="" method="post">
                        @csrf
                        <input type="hidden" id="ownerUserId" name="userId" value="">
                        <div class="form-group">
                            <label for="userOwner" class="col-form-label">Owner:</label>
                            <select class="form-control" name="owner" id="userOwner">
                                <option disabled>@lang('Sellet One')</option>
                                <option value="0">No Owner</option>
                                @foreach ($admins as $admin)
                                <option value="{{ $admin->id }}" id="option-{{$admin->id}}">{{
                                    $admin->name }}</option>
                                <!-- $admin->id == $user->owner_id ? 'selected' : '' -->
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="editStatusLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editStatusLabel">Sale Status</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                       
                    </button>
                </div>
                <div >
                    <form id="editStatusForm" action="" method="post">
                        @csrf
                        <input type="hidden" id="statusUserId" name="userId" value="">
                        <div >
                            <label for="userStatus" class="col-form-label">Status:</label>
                            <select class="form-control" name="status" id="userStatus">
                                <option disabled>@lang('Sellet One')</option>
                                <option value="NEW">NEW</option>
                                <option value="CALLBACK">CALLBACK</option>
                                <option value="NA">NA</option>
                                <option value="UNDER_AGE">UNDER_AGE</option>
                                <option value="DENY_REGISTRATION">DENY_REGISTRATION</option>
                                <option value="DEPOSIT">DEPOSIT</option>
                                <option value="NOT_INTERESTED">NOT_INTERESTED</option>
                                <option value="VOICE_MAIL">VOICE_MAIL</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="customDateFilterModal" tabindex="-1" role="dialog" aria-labelledby="customDateFilterLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customDateFilterLabel">Custom Filter</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="customDateFilterForm" action="{{ url()->current() }}" method="GET">
                        <div class="flex-grow-1">
                            <label>@lang('Sart date - End date')</label>
                            <input name="customfilter" data-range="true" data-multiple-dates-separator=" - " data-language="en" class="customDateFilterInput form-control" data-position='bottom right' placeholder="@lang('Start date - End date')" autocomplete="off" value="{{ request()->date }}">
                        </div>
                        <div class="my-3">
                            <button type="submit" class="btn-lg btn-primary w-100">Start Filter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
    .btn-custom-border {
        border: none;
        border-bottom: 1px solid; /* or specify a color if needed */
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
@push('script')
<script>
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
@push('script')
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
<script>
    // // Add event listeners to all edit buttons
    // (function ($) {
    //     "use strict";
    try {
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.edit-comment-btn').forEach(function (button) {
                button.addEventListener('click', function () {
                    const comment = button.getAttribute('data-comment');
                    const userId = button.getAttribute('data-userid');
                    document.getElementById('userComment').value = comment;
                    document.getElementById('commentUserId').value = userId;
                    const form = document.getElementById('editCommentForm');
                    form.action = '/admin/users/update-comment/' + userId;
                    const modal = new bootstrap.Modal(document.getElementById('commentModal'), {});
                    modal.show();
                });
            });
            document.querySelectorAll('.edit-owner-btn').forEach(function (button) {
                button.addEventListener('click', function () {
                    const gotOwner = button.getAttribute('data-owner');
                    const owner = gotOwner ? JSON.parse(gotOwner) : undefined;
                    const userId = button.getAttribute('data-userid');
                    const ownerSelect = document.getElementById('userOwner');
                    ownerSelect.value = String(owner ? owner.id : 0);
                    // // Iterate over the options and select the matching one
                    // let someSelected = false;
                    // Array.from(ownerSelect.options).forEach(function (option) {
                    //     console.log('inside closure');
                    //     console.log(owner, typeof owner);
                    //     console.log(option.value, typeof option.value, owner.id, typeof owner.id);
                    //     if (Number(option.value) === Number(owner.id)) {
                    //         option.selected = true;
                    //         someSelected = true;
                    //     }
                    // });
                    // if (!someSelected) {
                    //     Array.from(ownerSelect.options).forEach(function (option) {
                    //         if (Number(option.value) === 0) {
                    //             option.selected = true;
                    //         }
                    //     });
                    // }
                    document.getElementById('ownerUserId').value = userId;
                    const form = document.getElementById('editOwnerForm');
                    form.action = '/admin/users/update-owner/' + userId;
                    const modal = new bootstrap.Modal(document.getElementById('ownerModal'), {});
                    modal.show();
                });
            });
            document.querySelectorAll('.edit-status-btn').forEach(function (button) {
                button.addEventListener('click', function () {
                    const status = button.getAttribute('data-status');
                    const userId = button.getAttribute('data-userid');
                    const statusSelect = document.getElementById('userStatus');
                    statusSelect.value = String(status ? status : 'NEW');
                    document.getElementById('statusUserId').value = userId;
                    const form = document.getElementById('editStatusForm');
                    form.action = '/admin/users/update-status/' + userId;
                    const modal = new bootstrap.Modal(document.getElementById('statusModal'), {});
                    modal.show();
                });
            });
        });
    } catch (e) { console.error(e); }
        // })(jQuery);
</script>
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
  </script>
@endpush

