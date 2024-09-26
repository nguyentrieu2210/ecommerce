@extends('backend.layouts.layout')
@section('title', __("breadcrump.".$config['module'].".".$config['method'].".title"))
@section('content')
 
    @include('backend.layouts.components.breadcrump', ['config' => $config])

    <div class="row">
        @include('backend.warehouse.inventory.components.filter', ['config' => $config])
        @include('backend.warehouse.inventory.components.table', ['warehouse' => $warehouse, 'warehouses' => $warehouses])
    </div>
    <input type="hidden" class="name-model" value="{{$config['module']}}">
@endsection
