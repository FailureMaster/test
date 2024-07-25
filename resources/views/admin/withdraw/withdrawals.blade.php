@php
    $currentFilter = request('filter');
@endphp
@extends('admin.layouts.app')
@section('panel')
<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="card responsive-filter-card mb-4">
            <div class="card-body">
                <x-date-filter :currentFilter="$currentFilter" :currentUrl="url()->current()" />
                <div>
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
                                <label>@lang('Email')</label>
                                <input type="email" name="user_email" value="{{ request()->user_email }}" class="form-control">
                            </div>
                            <div class="flex-grow-1">
                                <label>Method</label>
                                <input type="text" name="method_name" value="{{ request()->method_name }}" class="form-control">
                            </div>
                            <div class="flex-grow-1">
                                <label>@lang('Status')</label>
                                <select name="status" class="form-control">
                                    <option value="">@lang('Select One')</option>
                                    <option value="1" @selected(request()->status == 1)>
                                        Approved
                                    </option>
                                    <option value="2" @selected(request()->status == 2)>
                                        Pending
                                    </option>
                                    <option value="3" @selected(request()->status == 3)>
                                        Cancelled
                                    </option>
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
    @if(request()->routeIs('admin.withdraw.log') || request()->routeIs('admin.withdraw.method') || request()->routeIs('admin.users.withdrawals') || request()->routeIs('admin.users.withdrawals.method'))
    <div class="col-xl-4 col-sm-6 mb-30">
        <div class="widget-two box--shadow2 has-link b-radius--5 bg--success">
            <a href="{{ route('admin.withdraw.approved') }}" class="item-link"></a>
            <div class="widget-two__content">
                <h2 class="text-white">{{ __($general->cur_sym) }}{{ showAmount($successful) }}</h2>
                <p class="text-white">@lang('Approved Withdrawals')</p>
            </div>
        </div><!-- widget-two end -->
    </div>
    <div class="col-xl-4 col-sm-6 mb-30">
        <div class="widget-two box--shadow2 has-link b-radius--5 bg--6">
            <a href="{{ route('admin.withdraw.pending') }}" class="item-link"></a>
            <div class="widget-two__content">
                <h2 class="text-white">{{ __($general->cur_sym) }}{{ showAmount($pending) }}</h2>
                <p class="text-white">@lang('Pending Withdrawals')</p>
            </div>
        </div><!-- widget-two end -->
    </div>
    <div class="col-xl-4 col-sm-6 mb-30">
        <div class="widget-two box--shadow2 b-radius--5 has-link bg--pink">
            <a href="{{ route('admin.withdraw.rejected') }}" class="item-link"></a>
            <div class="widget-two__content">
                <h2 class="text-white">{{ __($general->cur_sym) }}{{ showAmount($rejected) }}</h2>
                <p class="text-white">@lang('Rejected Withdrawals')</p>
            </div>
        </div><!-- widget-two end -->
    </div>
    @endif
    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body p-0">

                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>@lang('Currency')</th>
                                <th>@lang('Gateway | Transaction')</th>
                                <th>@lang('Initiated')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($withdrawals as $withdraw)
                                @php
                                    $details = ($withdraw->withdraw_information != null) ? json_encode($withdraw->withdraw_information) : null;
                                @endphp
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.users.detail', $withdraw->user_id) }}">{{ @$withdraw?->user?->lead_code }}</a>
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ $withdraw?->user?->fullname }}</span>
                                        <br>
                                        <span class="small"> <a href="{{ appendQuery('search',@$withdraw?->user?->username) }}"><span>@</span>{{ $withdraw?->user?->username }}</a> </span>
                                    </td>
                                    <td>
                                        {{ $withdraw->user->email }}
                                    </td>
                                    <td>
                                        <div>
                                            <span>{{ @$withdraw->wallet->currency->symbol }}</span>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="fw-bold"><a href="{{ appendQuery('method',@$withdraw->method->id) }}"> {{ __(@$withdraw->method->name) }}</a></span>
                                        <br>
                                        <small>{{ $withdraw->trx }}</small>
                                    </td>
                                    <td>
                                        {{ showDateTime($withdraw->created_at) }} <br>  {{ diffForHumans($withdraw->created_at) }}
                                    </td>

                                    <td>
                                    {{ showAmount($withdraw->amount ) }} - <span class="text-danger" title="@lang('charge')">{{ showAmount($withdraw->charge)}} </span>
                                        <br>
                                        <strong title="@lang('Amount after charge')">
                                        {{ showAmount($withdraw->amount-$withdraw->charge) }} {{ $withdraw->currency }}
                                        </strong>

                                    </td>
                                    <td>
                                        @php echo $withdraw->statusBadge @endphp
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.withdraw.details', $withdraw->id) }}" class="btn btn-sm btn-outline--primary ms-1">
                                            <i class="la la-desktop"></i> @lang('Details')
                                        </a>
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
            @if ($withdrawals->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($withdrawals) }}
            </div>
            @endif
        </div><!-- card end -->
    </div>
</div>
@endsection
