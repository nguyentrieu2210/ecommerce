@extends('backend.layouts.layout')
@section('title', __("breadcrump.".$config['module'].".".$config['method'].".title"))
@section('content')
    @include('backend.layouts.components.breadcrump', ['config' => $config])

    <div class="wrapper wrapper-content animated fadeInRight">
        @include('backend.layouts.components.errors')
        @php
            $route = $config['method'] == 'create' ? route('source.store') : route('source.update', $source->id);   
        @endphp
        <form method="post" action="{{$route}}">
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Thông tin chung <small>(Những trường có dấu (*) là những trường bắt buộc)</small></h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content" style="">
                            <div class="row">
                                <div class="col-sm-9 b-r">
                                    <div class="form-group"><label>Tên nguồn khách <span class="text-danger">(*)</span></label>
                                        <input type="text" placeholder="Nhập tên nhóm..." value="{{old('name') ?? (isset($source) ? $source->name : '')}}" name="name" class="form-control">
                                    </div>
                                    <div class="form-group"><label>Từ khóa <span class="text-danger">(*)</span></label>
                                        <input type="text" placeholder="Nhập từ khóa..." value="{{old('keyword') ?? (isset($source) ? $source->keyword : '')}}" name="keyword" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Mô tả chung</label>
                                        <textarea style="height: 105px" class="form-control" name="description" id="" cols="30" rows="10">{{old('description') ?? (isset($source) && $source->description !== null ? $source->description : '')}}</textarea>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <h4>Ảnh đại diện</h4>
                                    <p>Click vào hình ảnh bên dưới để chọn</p>
                                    <img data-type="Images" class="upload-image image-style "
                                        src="{{old('image') ?? (isset($source) && $source->image !== null ? $source->image : asset('/backend/img/empty-image.png')) }}" alt="">
                                    <input type="hidden" name="image" value="{{old('image') ?? (isset($source) ? $source->image : '') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ibox-button">
                <button class="btn btn-primary mr10" type="submit">Lưu lại</button>
                <a href="{{route('source.index')}}" class="btn btn-white" >Quay lại</a>
            </div>
        </form>
    </div>
@endsection
