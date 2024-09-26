@extends('backend.layouts.layout')
@section('title', __("breadcrump.".$config['module'].".".$config['method'].".title"))
@section('content')
    @include('backend.layouts.components.breadcrump', ['config' => $config])

    <div class="wrapper wrapper-content animated fadeInRight">
        @include('backend.layouts.components.errors')
        @php
            $route = $config['method'] == 'create' ? route('widget.store') : route('widget.update', $widget->id);   
        @endphp
        <form method="post" action="{{$route}}">
            @csrf
            <div class="row">
                <div class="col-lg-9">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>THÔNG TIN WIDGET <small>(Những trường có dấu (*) là những trường bắt buộc)</small></h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content" style="">
                            <div class="form-group">
                                <label for="">Mô tả ngắn</label>
                                <textarea data-height="150" class="form-control setupCkeditor" name="description" id="" cols="30"
                                    rows="10">{{ old('description') ?? (isset($widget) && $widget->description !== null ? $widget->description : '') }}</textarea>
                            </div>
                        </div>
                    </div>
                    @include('backend.layouts.components.album', ['model' => isset($widget) ? $widget : null])
                    @include('backend.layouts.components.search_model', ['models' => ($models ?? []), 'modules' => __('filter.moduleWidget')])
                    
                </div>
                <div class="col-lg-3">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>CÀI ĐẶT CƠ BẢN<br></h5>
                        </div>
                        <div class="ibox-content config-basic" style="">
                            <div class="form-group">
                                <label>Tên Widget <span class="text-danger">(*)</span></label> 
                                <input type="text" name="name" value="{{old('name') ?? (isset($widget) ? $widget->name : '')}}" placeholder="Nhập tên Widget..." class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Từ khóa Widget <span class="text-danger">(*)</span></label> 
                                <input type="text" name="keyword" value="{{old('keyword') ?? (isset($widget) ? $widget->keyword : '')}}" placeholder="Nhập từ khóa Widget..." class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ibox-button">
                <button class="btn btn-primary mr10" type="submit">Lưu lại</button>
                <a href="{{route('widget.index')}}" class="btn btn-white" >Quay lại</a>
            </div>
        </form>
    </div>
@endsection
