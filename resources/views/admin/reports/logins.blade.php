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
                    <div class="position-relative">
                        <form action="">
                            <div class="d-flex gap-2">
                                <div class="flex-grow-1">
                                    <label>@lang('ID')</label>
                                    <input type="text" name="lead_code" value="{{ request()->lead_code }}" class="form-control">
                                </div>
                                <div class="flex-grow-1">
                                    <label>@lang('Name')</label>
                                    <input type="text" name="user_name" value="{{ request()->user_name }}" class="form-control">
                                </div>
                                <div class="flex-grow-1">
                                    <label>IP</label>
                                    <input type="text" name="ip" value="{{ request()->ip }}" class="form-control">
                                </div>
                                <div class="flex-grow-1">
                                    <label>Browser</label>
                                    <input type="text" name="browser" value="{{ request()->browser }}" class="form-control">
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
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>@lang('User')</th>
                                    <th>@lang('Login at')</th>
                                    <th>@lang('IP')</th>
                                    <th>@lang('Location')</th>
                                    <th>@lang('Browser | OS')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($loginLogs as $log)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.users.detail', $log->user_id) }}">{{ @$log?->user?->lead_code }}</a>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ @$log->user->fullname }}</span>
                                        </td>
                                        <td>
                                            {{showDateTime($log->created_at) }} <br> {{diffForHumans($log->created_at, true) }}
                                        </td>
                                        <td>
                                            <span class="fw-bold">
                                                <a href="{{route('admin.report.login.ipHistory',[$log->user_ip])}}">{{ $log->user_ip }}</a>
                                            </span>
                                        </td>
    
                                        <td>
                                            {{ __($log->city) }} <br> {{ __($log->country) }}
                                        </td>
                                        <td>
                                            {{ __($log->browser) }} <br> {{ __($log->os) }}
                                        </td>
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
                @if ($loginLogs->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($loginLogs) }}
                    </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>
@endsection

@if(request()->routeIs('admin.report.login.ipHistory'))
    @push('breadcrumb-plugins')
        <a href="https://www.ip2location.com/{{ $ip }}" target="_blank" class="btn btn--primary">@lang('Lookup IP') {{ $ip }}</a>
    @endpush
@endif