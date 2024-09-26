@extends('backend.layouts.layout')
@section('title', __("breadcrump.".$config['module'].".".$config['method'].".title"))
@section('content')
 
    @include('backend.layouts.components.breadcrump', ['config' => $config])

    <div class="row">
        @include('backend.layouts.components.filter', ['config' => $config])
        @include('backend.configuration.menu.components.table', ['menus' => $menus])
    </div>
    <input type="hidden" class="name-model" value="{{$config['module']}}">
@endsection
