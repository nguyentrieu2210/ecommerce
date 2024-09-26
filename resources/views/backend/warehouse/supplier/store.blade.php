@extends('backend.layouts.layout')
@section('title', __("breadcrump.".$config['module'].".".$config['method'].".title"))
@section('content')
    @include('backend.layouts.components.breadcrump', ['config' => $config])

    <div class="wrapper wrapper-content animated fadeInRight">
        @include('backend.layouts.components.errors')
        @php
            $route = $config['method'] == 'create' ? route('supplier.store') : route('supplier.update', $supplier->id);   
        @endphp
        <form method="post" action="{{$route}}">
            @csrf
            <div class="row">
                <div class="col-lg-8">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>THÔNG TIN CHUNG</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content" style="">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group"><label>Tên nhà cung cấp <span class="text-danger">(*)</span></label>
                                        <input type="text" placeholder="Nhập tên nhà cung cấp" value="{{old('name') ?? (isset($supplier) ? $supplier->name : '')}}" name="name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group"><label>Mã nhà cung cấp <span class="text-danger">(*)</span></label>
                                        <input type="text" placeholder="Nhập mã nhà cung cấp" value="{{old('code') ?? (isset($supplier) ? $supplier->code : '')}}" name="code" class="form-control">
                                    </div>
                                    <div class="form-group"><label>Email</label>
                                        <input type="text" placeholder="Nhập email" value="{{old('email') ?? (isset($supplier) ? $supplier->email : '')}}" name="email" class="form-control">
                                    </div>
                                    <div class="form-group"><label>Website</label>
                                        <input type="text" placeholder="https://" value="{{old('website') ?? (isset($supplier) ? $supplier->website : '')}}" name="website" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group"><label>Số điện thoại</label>
                                        <input type="text" placeholder="Nhập số điện thoại" value="{{old('phone') ?? (isset($supplier) ? $supplier->phone : '')}}" name="phone" class="form-control">
                                    </div>
                                    <div class="form-group"><label>Mã số thuế</label>
                                        <input type="text" placeholder="Nhập mã số thuế" value="{{old('tax_number') ?? (isset($supplier) ? $supplier->tax_number : '')}}" name="tax_number" class="form-control">
                                    </div>
                                    <div class="form-group"><label>Số fax</label>
                                        <input type="text" placeholder="Nhập số fax" value="{{old('fax') ?? (isset($supplier) ? $supplier->fax : '')}}" name="fax" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>ĐỊA CHỈ</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content" style="">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="">Tỉnh / Thành phố</label>
                                        <select style="width: 100%" name="province_id" class="Province ml setupSelect2 changeLocation" data-target="District">
                                            <option value="0">Chọn tỉnh / thành phố</option>
                                            <div>
                                                @foreach ($provinces as $key => $val)
                                                <option {{ (old('province_id') ?? (isset($supplier) && $supplier->province_id !== null ? $supplier->province_id : '')) == $val->code ? 'selected' : '' }}
                                                    value="{{ $val->code }}">{{ $val->name }}</option>
                                                @endforeach
                                            </div>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="">Quận / Huyện</label>
                                        <select style="width: 100%" name="district_id" class="District ml setupSelect2 changeLocation" data-target="Ward">
                                            <option value="0">Chọn quận / huyện</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="">Phường / Xã</label>
                                        <select style="width: 100%" name="ward_id" class="Ward ml setupSelect2">
                                            <option value="0">Chọn phường / xã</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group"><label>Địa chỉ cụ thể</label> <input type="text"
                                            placeholder="Nhập địa chỉ cụ thể" name="address" value="{{old('address') ?? (isset($supplier) && $supplier->address !== null ? $supplier->address : '')}}" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>NHÂN VIÊN PHỤ TRÁCH</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content" style="">
                            <select style="width: 100%" name="user_id" class="ml setupSelect2">
                                <div>
                                    @foreach ($users as $key => $val)
                                    <option {{ (old('user_id') ?? (isset($supplier) && $supplier->user_id !== null ? $supplier->user_id : '')) == $val->id ? 'selected' : '' }}
                                        value="{{ $val->id }}">{{ $val->name }}</option>
                                    @endforeach
                                </div>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ibox-button">
                <button class="btn btn-primary mr10" type="submit">Lưu lại</button>
                <a href="{{route('supplier.index')}}" class="btn btn-white" >Quay lại</a>
            </div>
            <input type="hidden" class="oldDistrictId" value="{{old('district_id') ?? (isset($supplier) && $supplier->district_id !== null ? $supplier->district_id : '')}}">
            <input type="hidden" class="oldWardId" value="{{old('ward_id') ?? (isset($supplier) && $supplier->ward_id !== null ? $supplier->ward_id : '')}}">
        </form>
    </div>
@endsection
