@extends('admin.layouts.app')
@section('panel')
<div class="card">
    <!-- extends('admin.layouts.master')
    section('title',__('Group Edit'))
    section('content') -->
    <div class="card-body">
        <form action="{{route('admin.manage_admins.permission_group.update', $group->id)}}" method="POST">
            @csrf
            <div class="row mb-2 justify-content-center">
                <div class="col-md-6">
                    <div class="form-group col-md-12 mb-2">
                        <label class="form-label">{{__('Group Name')}}</label>
                        <input class="form-control" name="name" placeholder="Group Name" value="{{$group->name}}">
                    </div>
                    <div class="form-group col-md-12">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="customChec" name="status" {{$group->status?'checked':''}}>
                            <label class="form-label custom-control-label" for="customChec">{{__('Status')}}</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-2">
                <h3>{{__('Permission')}}</h3>
                <hr />
                @foreach($permission as $key => $per)
                <div class="col-md-3">
                    <div class="form-group">
                        @if($group->is_default && $per['name']==='manage_user')
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" checked disabled>
                            <input type="hidden" class="custom-control-input" id="customCheck{{$key+1}}" name="permission[{{$per['name']}}]" value="true">
                            <label class="form-label custom-control-label" for="customCheck{{$key+1}}">{{$per['label']}}</label>
                        </div>
                        @else
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="customCheck{{$key+1}}" name="permission[{{$per['name']}}]" {{$per['value']?'checked':''}} value="true">
                            <label class="form-label custom-control-label" for="customCheck{{$key+1}}">{{$per['label']}}</label>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn btn--primary mt-2 w-100 h-45">@lang('Update')</button>
            </div>
        </form>
    </div>
</div>
@endsection