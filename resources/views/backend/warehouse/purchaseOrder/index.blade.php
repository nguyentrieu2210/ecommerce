@extends('backend.layouts.layout')
@section('title', __("breadcrump.".$config['module'].".".$config['method'].".title"))
@section('content')
 
    @include('backend.layouts.components.breadcrump', ['config' => $config])

    <div class="row">
        @include('backend.warehouse.purchaseOrder.components.filter', ['config' => $config])
        @include('backend.warehouse.purchaseOrder.components.table', ['purchaseOrders' => $purchaseOrders])
    </div>
    <input type="hidden" class="name-model" value="{{$config['module']}}">
@endsection
