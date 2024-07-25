@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-body p-0">
                <form action="{{ route('admin.users.sales.status.store') }}" method="POST"
                    enctype="multipart/form-data" class="pair-form">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label>@lang('Name')</label>
                                <div class="input-group">
                                    <input class="form-control" type="text" name="name" required>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn--primary w-100 h-45 ">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
<a href="{{ route('admin.users.sales.status') }}" class="btn btn-outline--primary btn-sm">
    <i class="las la-list"></i>@lang('Sales Statuses List')
</a>
@endpush
@push('script')
</script>

@endpush
@push('style')
@endpush