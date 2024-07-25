@php
    $currentFilter = request('filter');
@endphp
@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card responsive-filter-card mb-4">
            <div class="card-body">
                <x-date-filter :currentFilter="$currentFilter" :currentUrl="url()->current()" />
                <div>
                    <form action="{{ url()->current() }}">
                        @foreach (request()->query() as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <div class="d-flex gap-2">
                            <div class="flex-grow-1">
                                <label>@lang('ID')</label>
                                <input type="number" name="lead_code" value="{{ request()->lead_code }}" class="form-control">
                            </div>
                            <div class="flex-grow-1">
                                <label>@lang('Name')</label>
                                <input type="text" name="name" value="{{ request()->name }}" class="form-control">
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
                                        <option value="{{ $key }}" @selected(request()->country_code == $key)>
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
                                    <option value="19">No Owner</option>
                                    @foreach ($admins as $admin)
                                        @if ($admin->id !== 19)
                                            <option value="{{ $admin->id }}" @selected(request()->owner_id == $admin->id)>
                                                {{ __(keyToTitle($admin->name)) }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="">
                                <label>@lang('Source')</label>
                                <select name="lead_source" class="form-control">
                                    <option value="0">@lang('No Source')</option>
                                    @foreach ($leadSources as $sources)
                                        <option value="{{$sources}}" >
                                            {{$sources}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-grow-1 align-self-end">
                                <button class="btn btn--primary w-100 h-45" data-bs-placement="top" title="Search">
                                    <i class="la la-search"></i>
                                </button>
                            </div>
                            <div class="flex-grow-1 align-self-end">
                                <a href="{{route('admin.users.active', ['filter' => 'this_month'])}}" class="btn btn--secondary w-100 h-45 d-flex align-items-center" data-bs-placement="top" title="Clear Search">
                                    <i class="la la-refresh"></i>
                                </a>
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
                    <div class="d-flex align-items-center p-3">
                        <small>
                            Showing {{ $users->firstItem() }} - {{ $users->lastItem() }} of {{ $users->total() }}
                        </small>
                        @if (can_access('bulk-update-leads'))
                            <div class="dropdown mx-2 bulk-action">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Selected Leads: 
                                    <span class="selected-leads-count text-white"></span>
                                </button>
                                <ul class="dropdown-menu px-2" style="width: 220px">
                                    <li>
                                        <div>
                                            <label>@lang('Owner')</label>
                                            <select class="owner_id w-100">
                                                <option value="">@lang('Select One')</option>
                                                @foreach ($admins as $admin)
                                                    <option value="{{ $admin->id }}">
                                                        {{ __(keyToTitle($admin->name)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <label>@lang('Status')</label>
                                            <select class="sales_status w-100">
                                                <option value="">@lang('Select One')</option>
                                                @foreach ($salesStatuses as $status)
                                                    <option value="{{ $status->name }}">
                                                        {{ __(keyToTitle($status->name)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <label>@lang('Account Type')</label>
                                            <select class="account_type w-100">
                                                <option value="">@lang('Select One')</option>
                                                <option value="demo">
                                                    Demo
                                                </option>
                                                <option value="demo">
                                                    Real
                                                </option>
                                            </select>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-primary w-100" id="submitBtn">Submit</button>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        @endif
                    </div>
                    <table class="table table--light style--two highlighted-table">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" class="form-check-input" id="checkAll">
                                </th>
                                <!--<th>Star</th>-->
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
                                @endif
                                <th>@lang('Last Comment')</th>
                                @if(can_access('manage-sales-leads|manage-retention-leads'))
                                    <th>@lang('IP')</th>
                                @endif
                                 <th>@lang('Source')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td class="text-center">
                                   <input
                                        type="checkbox"
                                        class="form-check-input selectUser"
                                        id="flexCheckDefault{{ $user->id }}"
                                        name="selectUsers[]"
                                        value="{{ $user->id }}"
                                    >
                                </td>
                                <!--<td>-->
                                <!--    <form id="favoriteForm"-->
                                <!--        action="{{ route('admin.users.toggle.favorite', $user->id) }}" method="POST">-->
                                <!--        @csrf-->
                                <!--        <button type="submit" style="background: none; border: none; cursor: pointer;">-->
                                <!--            @if ($user->favorite)-->
                                <!--            <i class="fa fa-star" style="color: gold;"></i>-->
                                            <!-- Using Font Awesome Icons -->
                                <!--            @else-->
                                <!--            <i class="fa fa-star" style="color: grey;"></i>-->
                                <!--            @endif-->
                                <!--        </button>-->
                                <!--    </form>-->
                                <!--</td>-->
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
                                    <form id="editStatusFormInline" action="/admin/users/update-status/{{$user->id}}" method="post" class="m-0">
                                        @csrf
                                        <div>
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
                                            method="post" class="m-0">
                                            @csrf
                                            <div>
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
                                     @if ($user->comments->isNotEmpty())
                                        <span>{{ showDateTime($user->comments->sortByDesc('created_at')->first()->created_at, 'd-m-y - H:i') }}</span>
                                    @else
                                        <span>No comments</span>
                                    @endif
                                </td>
                                @if(can_access('manage-sales-leads|manage-retention-leads'))
                                    <td>
                                        <span class="d-block">
                                             <button
                                                type="button"
                                                class="btn btn-outline--primary"
                                                title="{{ $user->user_ip ?? '-' }}"
                                                >
                                                <i class="fas fa-globe"></i>
                                            </button>
                                           
                                        </span>
                                    </td>
                                @endif
                                <td class="text-center" > 
                                        <span class="d-block"></span>
                                        {{ $user->lead_source ?? '-'}}
                                    </td>
                              
                                <td>
                                    <div class="">
                                        @if(can_access('delete-user'))
                                            <button
                                                type="button"
                                                class="btn btn-outline--primary"
                                                data-bs-target="#actionMessageModal{{ $user->id }}"
                                                data-bs-toggle="modal"
                                                data-bs-placement="top"
                                                title="Delete"
                                                >
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        @endif

                                        <button type="button" data-target="#commentModal"
                                            class="btn {{$user->comment ? 'btn--primary':'btn-outline--primary'}} edit-comment-btn"
                                            data-toggle="modal" data-comment="{{ $user->comment }}"
                                            data-userid="{{ $user->id }}"
                                            data-bs-placement="top"
                                            title="Comments"
                                            >
                                            <i class="far fa-comments"></i>
                                        </button>

                                        <a
                                            href="{{ route('admin.users.detail', $user->id) . ($history == 'clients' ? '?back=clients' : '?back=leads') }}"
                                            class="btn btn-sm btn-outline--primary"
                                            data-bs-placement="top"
                                            title="More Info"
                                            >
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
                    <div>
                        <small>
                            Showing {{ $users->firstItem() }} - {{ $users->lastItem() }} of {{ $users->total() }}
                        </small>
                    </div>
                    <div class="d-flex justify-content-center mb-3">
                        {{ paginateLinks($users) }}
                    </div>
                    <div class="d-flex justify-content-center align-items-center">
                        <form action="{{ url()->current() }}" method="GET" class="d-flex align-items-center gap-2">
                            <span for="per_page">View</span>
                            <select name="per_page" id="per_page" onchange="this.form.submit()">
                                <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                            </select>
                            <span for="per_page" class="me-2">Per Page</span>
                        </form>
                    </div>
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
                                rows="4" id="userComment" value="" readonly></textarea>
                        </div>
                        <!--<button type="submit" class="btn btn-primary">Save changes</button>-->
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
</div>
@endsection

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
                    
                    if (document.getElementById('userComment').value == "") {
                        document.getElementById('userComment').value ='No comments';
                    }
                    
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
@push('script')
    <script>
        "use strict";
        (function($) {
            $(document).ready(function() {
                const checkAll = $('#checkAll');
                const checkboxes = $('.selectUser');
                const selectedCountSpan = $('.selected-leads-count');
                
                function updateSelectedCount() {
                    let selectedCount = checkboxes.filter(':checked').length;
                    selectedCountSpan.text(selectedCount);
                    
                    $('.bulk-action').toggle(selectedCount > 0);
                }
                
                updateSelectedCount();
                
                checkAll.on('change', function() {
                    let isChecked = this.checked;

                    $('.bulk-action').toggle(isChecked);
                
                    checkboxes.prop('checked', isChecked);
                    
                    updateSelectedCount();
                });
                
                checkboxes.on('change', function() {
                    if (!this.checked) {
                        checkAll.prop('checked', false);
                    }
                    if (checkboxes.filter(':checked').length === checkboxes.length) {
                        checkAll.prop('checked', true);
                    }
                    
                    updateSelectedCount();
                });
                
                $('#submitBtn').on('click', function() {
                    // Collect the IDs of the selected checkboxes
                    let selectedIds = [];
                    checkboxes.filter(':checked').each(function() {
                        selectedIds.push(parseInt($(this).val()));
                    });
            
                    // Collect the form data
                    const formData = {
                        owner_id: parseInt($('.owner_id').val()),
                        sales_status: $('.sales_status').val(),
                        account_type: $('.account_type').val(),
                        selected_ids: selectedIds,
                        _token: "{{ csrf_token() }}"
                    };
            
                    console.log('Form Data:', formData);
                    
                    let url = "{{ route('admin.users.bulk.record.update')}}";

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            window.location.reload();
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                        }
                    });
                });
            });
        })(jQuery);
    </script>
@endpush

@push('style')
<style>
    #checkAll {
        border: 1px solid white;
    }
    
    .bulk-action {
        display: none;
    }

    
    tbody tr:nth-child(even) {
      background-color: #ebecee;
    }
    
    table.table--light.style--two thead th {
        border-top: none;
        padding-left: 10px;
        padding-right: 10px;
    }
    
    table.table--light.style--two tbody td {
        padding: 4px 5px;
    }
</style>
@endpush

