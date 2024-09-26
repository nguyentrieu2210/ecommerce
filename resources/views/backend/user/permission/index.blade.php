@extends('backend.layouts.layout')
@section('title', __("breadcrump.".$config['module'].".".$config['method'].".title"))
@section('content')
 
    @include('backend.layouts.components.breadcrump', ['config' => $config])

    <div class="row">
        @include('backend.user.permission.components.filter')
        @include('backend.user.permission.components.table', ['permissions' => $permissions])
    </div>
    <input type="hidden" class="name-model" value="{{$config['module']}}">
@endsection
