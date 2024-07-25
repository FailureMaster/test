@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
                <div class="table-responsive--md  table-responsive">
                    <table class="table table--light style--two highlighted-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($salesStatuses as $status)
                                <tr>
                                    <td>
                                        {{ $status->id }}
                                    </td>
                                    <td>
                                        {{ $status->name }}
                                    </td>
                                    <td>
                                        <div class="button--group">
                                            <button
                                                type="button"
                                                class="btn btn-outline--primary"
                                                data-bs-target="#actionMessageModal{{ $status->id }}"
                                                data-bs-toggle="modal"
                                                >
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <div id="actionMessageModal{{ $status->id }}" class="modal fade" tabindex="-1" role="dialog">
                                        <div class="modal-dialog modal-sm" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Delete User {{ $status->name }}</h5>
                                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                        <i class="las la-times"></i>
                                                    </button>
                                                </div>
                                                <form
                                                    action="{{ route('admin.users.sales.status.delete', $status->id) }}"
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
        </div>
    </div>
</div>
@endsection
@push('breadcrumb-plugins')
<a href="{{ route('admin.users.sales.status.create') }}" class="btn btn-outline--primary addBtn h-45">
    <i class="las la-plus"></i>@lang('Add New Status')
</a>
@endpush
@push('script')
<script>
</script>
@endpush