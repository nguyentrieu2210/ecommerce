@extends('backend.layouts.layout')
@section('title', __("breadcrump.".$config['module'].".".$config['method'].".title"))
@section('content')
 
    @include('backend.layouts.components.breadcrump', ['config' => $config])

    <div class="row">
        @include('backend.layouts.components.filter', ['modelCatalogues' => $userCatalogues])
        @include('backend.user.user.components.table', ['users' => $users])
    </div>
    <input type="hidden" class="name-model" value="{{$config['module']}}">
@endsection
