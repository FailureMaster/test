@extends('admin.layouts.app')
@section('panel')
<div class="card">
    <!-- extends('admin.layouts.master')
section('title',__('user'))
section('content') -->
    <div class="card-header">
        <h4>@lang('User') <a id="btn_add" href="#" data-bs-toggle="modal" data-bs-target="#exampleModal"
                class="btn btn-success btn-sm float-end"><i class="fa fa-plus"></i> @lang('Add New User')</a> </h4>
    </div>

    <div class="card-body p-0">
        <table class="table s7__table">
            <thead>
                <tr>
                    <th>{{__('Role')}}</th>
                    <th>{{__('Name')}}</th>
                    <th>{{__('User Name')}}</th>
                    <th>{{__('Email')}}</th>
                    <!-- 
                    @if(can_access('view_pass'))
                    <th>{{__('Password')}}</th>
                    @endif -->

                    <!-- <th> {{__('Status')}} </th> -->
                    <th> {{__('Action')}} </th>
                </tr>
            </thead>
            <tbody>
                @foreach($admins as $admin)
                <tr>
                    <td><span class="badge bg-success">{{$admin->group->name}}</span></td>
                    <td>{{$admin->name}}</td>
                    <td>{{$admin->username}}</td>
                    <td>{{$admin->email}}</td>
                    <!-- @if(can_access('view_pass'))
                    <td>{{$admin->sct}}</td>
                    @endif -->
                    <!-- <td>
                        @if ($admin->status == 1)
                        <span class="badge bg-success"> {{__('Active')}} </span>
                        @else
                        <span class="badge bg-danger"> {{__('Deactive')}} </span>
                        @endif
                    </td> -->
                    <td>
                        <a class="btn s7__btn-primary btn-sm" href="#editModal{{$admin->id}}" data-bs-toggle="modal">
                            <i class="fa fa-edit"></i>
                        </a>
                        @if(can_access('delete-admin'))
                            <a class="btn s7__btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#actionMessageModal{{ $admin->id }}">
                                <i class="fa fa-trash"></i>
                            </a>
                        @endif
                        <!-- s7__bg-base -->
                    </td>
                    <div id="actionMessageModal{{ $admin->id }}" class="modal fade" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-sm" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Delete Admin #{{ $admin->id }}</h5>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                        <i class="las la-times"></i>
                                    </button>
                                </div>
                                <form
                                    action="{{ route('admin.manage_admins.admin.delete', $admin->id) }}"
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

                <div class="modal fade innvoice_modal_ara" id="editModal{{$admin->id}}" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">{{__('Edit User')}}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin.manage_admins.admin.update',$admin->id)}}" method="post">
                                    @csrf
                                    <div class="form-row">
                                        <div class="form-group col-md-12 pb-2">
                                            <label class="form-label">{{__('Name')}}</label>
                                            <input type="text" class="form-control" value="{{$admin->name}}"
                                                name="name">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-12 pb-2">
                                            <label class="form-label">{{__('Group')}}</label>
                                            <select class="form-select" name="group_id">
                                                <option value="">{{__('Select Group')}}</option>
                                                @foreach($groups as $group)
                                                <option {{$admin->group->id == $group->id? 'selected':''}}
                                                    value="{{$group->id}}">{{$group->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-12 pb-2">
                                            <label class="form-label">{{__('Username')}}</label>
                                            <input type="text" class="form-control" name="username"
                                                value="{{$admin->username}}">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-12 pb-2">
                                            <label class="form-label">{{__('Password')}}</label>
                                            <input type="text" class="form-control" name="password">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-12 pb-2">
                                            <label class="form-label">{{__('Email')}}</label>
                                            <input type="email" class="form-control" value="{{$admin->email}}"
                                                name="email">
                                        </div>
                                    </div>

                                    <!-- <div class="form-row">
                                        <div class="form-group col-md-12 pb-2">
                                            <label class="form-label">{{__('Status')}}</label>
                                            <select class="form-select" name="status">
                                                <option {{$admin->status == 1 ? 'selected':''}} value="1">{{__('Active')}}</option>
                                                <option {{$admin->status == 0 ? 'selected':''}} value="0">{{__('Deactive')}}</option>
                                            </select>
                                        </div>
                                    </div> -->
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i
                                        class="fa fa-times"></i> {{__('Close')}}</button>
                                <button type="submit" class="btn btn-primary bold uppercase"><i class="fa fa-send"></i>
                                    {{__('Save')}}</button>
                            </div>

                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

<div class="modal fade innvoice_modal_ara" id="exampleModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{__('Create User')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.manage_admins.admin.store')}}" method="post">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label">{{__('Name')}}</label>
                        <input type="text" class="form-control" name="name">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">{{__('Group')}}</label>
                        <select class="form-select" name="group_id">
                            <option value="">{{__('Select Group')}}</option>
                            @foreach($groups as $group)
                            <option value="{{$group->id}}">{{$group->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">{{__('Username')}}</label>
                        <input type="text" class="form-control" name="username">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">{{__('Password')}}</label>
                        <input type="text" class="form-control" name="password">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">{{__('Email')}}</label>
                        <input type="email" class="form-control" name="email">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-times"></i>
                    {{__('Close')}}</button>
                <button type="submit" class="btn btn-primary">{{__('Save')}}</button>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection