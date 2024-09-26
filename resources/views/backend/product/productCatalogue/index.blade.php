@extends('backend.layouts.layout')
@section('title', __("breadcrump.".$config['module'].".".$config['method'].".title"))
@section('content')
 
    @include('backend.layouts.components.breadcrump', ['config' => $config])

    <div class="row">
        @include('backend.layouts.components.filter', ['modelCatalogues' => $productCatalogues])
        @include('backend.product.productCatalogue.components.table', ['productCatalogues' => $productCatalogues])
    </div>
    <input type="hidden" class="name-model" value="{{$config['module']}}">
@endsection
