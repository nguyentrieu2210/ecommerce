@extends('backend.layouts.layout')
@section('title', __("breadcrump.".$config['module'].".".$config['method'].".title"))
@section('content')
    @include('backend.layouts.components.breadcrump', ['config' => $config])

    <div class="wrapper wrapper-content animated fadeInRight">
        @include('backend.layouts.components.errors')
        @php
            $route = $config['method'] == 'create' ? route('warehouse.store') : route('warehouse.update', $warehouse->id);   
        @endphp
        <form method="post" action="{{$route}}">
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Thông tin chung <small class="fs14">(Mỗi chi nhánh vừa là điểm lấy hàng vừa bao gồm quản lý kho)</small></h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content" style="">
                            <div class="row">
                                <div class="col-sm-6 b-r">
                                    <div class="form-group"><label>Tên cơ sở <span class="text-danger">(*)</span></label>
                                        <input type="text" placeholder="Nhập tên kho..." value="{{old('name') ?? (isset($warehouse) ? $warehouse->name : '')}}" name="name" class="form-control">
                                    </div>
                                    <div class="form-group"><label>Mã chi nhánh <span class="text-danger">(*)</span></label>
                                        <input type="text" placeholder="Nhập mã kho..." value="{{old('code') ?? (isset($warehouse) ? $warehouse->code : '')}}" name="code" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Người quản lý <span class="text-danger">(*)</span></label>
                                        <select style="width: 100%" name="user_id" class="ml setupSelect2">
                                            <option value="0">Chọn người quản lý</option>
                                            @foreach ($supervisors as $key => $val)
                                                <option
                                                    {{ (old('user_id') ?? (isset($warehouse) ? $warehouse->user_id : '')) == $val->id ? 'selected' : '' }}
                                                    value="{{ $val->id }}">{{ $val->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                          
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group"><label>Số điện thoại</label>
                                        <input type="text" placeholder="Nhập số điện thoại..." value="{{old('phone') ?? (isset($warehouse) ? $warehouse->phone : '')}}" name="phone" class="form-control">
                                    </div>
                                    <div class="form-group"><label>Địa chỉ</label>
                                        <input type="text" placeholder="Nhập địa chỉ..." value="{{old('address') ?? (isset($warehouse) ? $warehouse->address : '')}}" name="address" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Ghi chú</label>
                                        <textarea style="height: 105px" class="form-control" name="description" id="" cols="30" rows="10">{{old('description') ?? (isset($warehouse) && $warehouse->description !== null ? $warehouse->description : '')}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ibox-button">
                <button class="btn btn-primary mr10" type="submit">Lưu lại</button>
                <a href="{{route('warehouse.index')}}" class="btn btn-white" >Quay lại</a>
            </div>
        </form>
    </div>
@endsection
