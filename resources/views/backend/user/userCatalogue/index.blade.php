@extends('backend.layouts.layout')
@section('title', __("breadcrump.".$config['module'].".".$config['method'].".title"))
@section('content')
 
    @include('backend.layouts.components.breadcrump', ['config' => $config])

    <div class="row">
        @include('backend.user.userCatalogue.components.filter')
        @include('backend.user.userCatalogue.components.table', ['userCatalogues' => $userCatalogues])
    </div>
    <input type="hidden" class="name-model" value="{{$config['module']}}">
@endsection
