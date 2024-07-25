@extends('admin.layouts.app')
@section('panel')
<div class="card">
    <!-- extends('admin.layouts.master')
section('title',__('group'))
section('content')
 -->
    <div class="card-header">
        <h4>@lang('Group List') <a href="{{route('admin.manage_admins.permission_group.create')}}" class="btn btn-success btn-sm float-end"><i class="fa fa-plus"></i> @lang('Add New')</a> </h4>
    </div>

    <div class="card-body p-0">
        <table class="table s7__table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{__('Group Name')}}</th>
                    <th>{{__('Status')}}</th>
                    <th>{{__('Action')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groups as $key => $group)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$group->name}}</td>
                    <td>
                        @if ($group->status == 1)
                        <span class="badge bg-success"> {{__('Active')}} </span>
                        @else
                        <span class="badge bg-danger"> {{__('Deactive')}} </span>
                        @endif
                    </td>
                    <td>
                        <a href="{{route('admin.manage_admins.permission_group.edit',$group->id)}}" class="btn s7__btn-primary btn-sm" data-bs-toggle="tooltip">
                            <i class="fa fa-edit"></i>
                        </a>
                        @if(can_access('delete-group'))
                            <a class="btn s7__btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#actionMessageModal{{ $group->id }}">
                                <i class="fa fa-trash"></i>
                            </a>
                        @endif
                        <!-- s7__bg-base -->
                    </td>
                    <div id="actionMessageModal{{ $group->id }}" class="modal fade" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-sm" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Delete Group #{{ $group->id }}</h5>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                        <i class="las la-times"></i>
                                    </button>
                                </div>
                                <form
                                    action="{{ route('admin.manage_admins.group.delete', $group->id) }}"
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
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection