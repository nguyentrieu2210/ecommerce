@extends('backend.layouts.theme')
@section('title', __('breadcrump.' . $config['module'] . '.' . $config['method'] . '.title'))
@section('content')
    @include('backend.layouts.components.theme.homepage')
@endsection