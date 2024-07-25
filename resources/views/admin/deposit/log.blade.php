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
                                    <label>Gateway</label>
                                    <input type="text" name="gateway_name" value="{{ request()->gateway_name }}" class="form-control">
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

        @if (request()->routeIs('admin.deposit.list') ||
                request()->routeIs('admin.deposit.method') ||
                request()->routeIs('admin.users.deposits') ||
                request()->routeIs('admin.users.deposits.method'))
            <div class="col-xxl-3 col-sm-6 mb-30">
                <div class="widget-two box--shadow2 b-radius--5 bg--success has-link">
                    <a href="{{ route('admin.deposit.successful') }}" class="item-link"></a>
                    <div class="widget-two__content">
                        <h2 class="text-white">{{ __($general->cur_sym) }}{{ showAmount($successful) }}</h2>
                        <p class="text-white">@lang('Successful Deposit')</p>
                    </div>
                    <span class="estimated-badge">@lang('Estimated')</span>
                </div><!-- widget-two end -->
            </div>
            <div class="col-xxl-3 col-sm-6 mb-30">
                <div class="widget-two box--shadow2 b-radius--5 bg--6 has-link">
                    <a href="{{ route('admin.deposit.pending') }}" class="item-link"></a>
                    <div class="widget-two__content">
                        <h2 class="text-white">{{ __($general->cur_sym) }}{{ showAmount($pending) }}</h2>
                        <p class="text-white">@lang('Pending Deposit')</p>
                    </div>
                    <span class="estimated-badge">@lang('Estimated')</span>
                </div><!-- widget-two end -->
            </div>
            <div class="col-xxl-3 col-sm-6 mb-30">
                <div class="widget-two box--shadow2 has-link b-radius--5 bg--pink">
                    <a href="{{ route('admin.deposit.rejected') }}" class="item-link"></a>
                    <div class="widget-two__content">
                        <h2 class="text-white">{{ __($general->cur_sym) }}{{ showAmount($rejected) }}</h2>
                        <p class="text-white">@lang('Rejected Deposit')</p>
                    </div>
                    <span class="estimated-badge">@lang('Estimated')</span>
                </div><!-- widget-two end -->
            </div>
            <div class="col-xxl-3 col-sm-6 mb-30">
                <div class="widget-two box--shadow2 has-link b-radius--5 bg--dark">
                    <a href="{{ route('admin.deposit.initiated') }}" class="item-link"></a>
                    <div class="widget-two__content">
                        <h2 class="text-white">{{ __($general->cur_sym) }}{{ showAmount($initiated) }}</h2>
                        <p class="text-white">@lang('Initiated Deposit')</p>
                    </div>
                    <span class="estimated-badge">@lang('Estimated')</span>
                </div><!-- widget-two end -->
            </div>
        @endif

        <div class="col-md-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('ID')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Email')</th>
                                    <th>@lang('Currency')</th>
                                    <th>@lang('Gateway | Transaction')</th>
                                    <th>@lang('Initiated')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($deposits as $deposit)
                                    @php
                                        $details = $deposit->detail ? json_encode($deposit->detail) : null;
                                    @endphp
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.users.detail', $deposit->user_id) }}">{{ @$deposit?->user?->lead_code }}</a>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ optional($deposit->user)->fullname }}</span>
                                        </td>
                                        <td>
                                            {{ $deposit->user->email }}
                                        </td>
                                        <td>
                                            <div class="text-end text-lg-start">
                                                <span>{{ @$deposit->currency->symbol }}</span>
                                                <br>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-bold"> <a
                                                    href="{{ appendQuery('method', @$deposit->gateway->alias) }}">{{ __(@$deposit->gateway->name) }}</a>
                                            </span>
                                            <br>
                                            <small> {{ $deposit->trx }} </small>
                                        </td>

                                        <td>
                                            {{ showDateTime($deposit->created_at) }}<br>{{ diffForHumans($deposit->created_at) }}
                                        </td>
                                        <td>
                                            {{ showAmount($deposit->amount) }} + <span class="text-danger"
                                                title="@lang('charge')">{{ showAmount($deposit->charge) }} </span>
                                            <br>
                                            <strong title="@lang('Amount with charge')">
                                                {{ showAmount($deposit->amount + $deposit->charge) }}
                                                {{ __($deposit->method_currency) }}
                                            </strong>
                                        </td>
                                        <td>
                                            @php echo $deposit->statusBadge @endphp
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.deposit.details', $deposit->id) }}"
                                                class="btn btn-sm btn-outline--primary ms-1">
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
                @if ($deposits->hasPages())
                    <div class="card-footer py-4">
                        @php echo paginateLinks($deposits) @endphp
                    </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>
@endsection

@push('style')
    <style>
        .estimated-badge {
            font-size: 10px;
            position: absolute;
            right: 10px;
            background: #fff;
            padding: 4px 5px;
            border-radius: 5px;
            top: 10px;
            font-weight: 500;
        }
    </style>
@endpush
