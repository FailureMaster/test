@extends('admin.layouts.app')
@section('panel')
<div class="card">
    <!-- extends('admin.layouts.master')
section('title',__('group-create'))
section('content')
 -->
    <div class="card-body">
        <form action="{{route('admin.manage_admins.permission_group.store')}}" method="POST">
            @csrf
            <div class="row mb-2 justify-content-center">
                <div class="col-md-6">
                    <div class="form-group mb-2">
                        <label class="form-label">{{__('Group Name')}}</label>
                        <input class="form-control" name="name" placeholder="Group Name">
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="customChec" name="status">
                            <label class="form-label custom-control-label" for="customChec">{{__('Status')}}</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-2">
                <h3>{{__('Permission')}}</h3>
                <hr />
                @foreach($permission_collect as $key => $permission)
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="customCheck{{$key+1}}"
                                name="permission[{{$permission['name']}}]" {{$permission['value']?'checked':''}}
                                value="true">
                            <label class="form-label custom-control-label"
                                for="customCheck{{$key+1}}">{{$permission['label']}}</label>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn btn--primary mt-2 w-100 h-45">@lang('Submit')</button>
            </div>
        </form>
    </div>
</div>
@endsection