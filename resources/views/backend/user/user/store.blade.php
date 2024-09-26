@extends('backend.layouts.layout')
@section('title', __("breadcrump.".$config['module'].".".$config['method'].".title"))
@section('content')
    @include('backend.layouts.components.breadcrump', ['config' => $config])

    <div class="wrapper wrapper-content animated fadeInRight">
        @include('backend.layouts.components.errors')
        @php
            $route = $config['method'] == 'create' ? route('user.store') : route('user.update', $user->id);   
        @endphp
        <form method="post" action="{{$route}}">
            @csrf
            <div class="row">
                <div class="col-lg-7">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Thông tin chung <small>(Nhập thông tin của người sử dụng)</small></h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content" style="">
                            <div class="row">
                                <div class="col-sm-6 b-r">
                                    <div class="form-group"><label>Email <span class="text-danger">(*)</span></label> <input
                                            type="text" value="{{old('email') ?? (isset($user) ? $user->email : '')}}" placeholder="Nhập Email..." name="email" class="form-control">
                                    </div>
                                    <div class="form-group"><label>Họ tên <span class="text-danger">(*)</span></label>
                                        <input type="text" placeholder="Nhập họ tên" value="{{old('name') ?? (isset($user) ? $user->name : '')}}" name="name" class="form-control">
                                    </div>
                                    @if($config['method'] == 'create')
                                    <div class="form-group"><label>Mật khẩu <span class="text-danger">(*)</span></label>
                                        <input type="text" value="{{old('password') ?? (isset($user) ? $user->password : '')}}" placeholder="Nhập mật khẩu..." name="password"
                                            class="form-control">
                                    </div>
                                    <div class="form-group"><label>Nhập lại mật khẩu <span
                                                class="text-danger">(*)</span></label> <input type="text"
                                            placeholder="Nhập lại mật khẩu..." value="{{old('password_confirmation')}}" name="password_confirmation" class="form-control"></div>
                                    @endif
                                    <div class="form-group">
                                        <label for="">Nhóm thành viên <span class="text-danger">(*)</span></label>
                                        <select style="width: 100%" name="user_catalogue_id" class="ml setupSelect2">
                                            <option value="0">Chọn nhóm thành viên</option>
                                            @foreach ($userCatalogues as $key => $val)
                                                <option
                                                    {{ (old('user_catalogue_id') ?? (isset($user) ? $user->user_catalogue_id : '')) == $val->id ? 'selected' : '' }}
                                                    value="{{ $val->id }}">{{ $val->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Ngày sinh</label>
                                        <div class="input-group date">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input
                                                name="birthday" value="{{old('birthday') ?? (isset($user) && $user->birthday !== null ? formatDateFromSql($user->birthday) : null)}}" type="text" class="form-control" value="03/04/2014">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <h4>Ảnh đại diện</h4>
                                    <p>Click vào hình ảnh bên dưới để chọn</p>
                                    <img data-type="Images" class="upload-image image-style "
                                        src="{{old('image') ?? (isset($user) && $user->image !== null ? $user->image : asset('/backend/img/empty-image.png')) }}" alt="">
                                    <input type="hidden" name="image" value="{{old('image') ?? (isset($user) ? $user->image : '') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Thông tin liên hệ <small>(Nhập thông tin liên hệ của người sử dụng)</small></h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="">Tỉnh / Thành phố</label>
                                        <select style="width: 100%" name="province_id" class="Province ml setupSelect2 changeLocation" data-target="District">
                                            <option value="0">Chọn tỉnh / thành phố</option>
                                            <div>
                                                @foreach ($provinces as $key => $val)
                                                <option {{ (old('province_id') ?? (isset($user) && $user->province_id !== null ? $user->province_id : '')) == $val->code ? 'selected' : '' }}
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
                                    <div class="form-group"><label>Số điện thoại</label> <input type="text"
                                            placeholder="Nhập số điện thoại..." name="phone" value="{{old('phone') ?? (isset($user) && $user->phone !== null ? $user->phone : '')}}" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="">Địa chỉ</label>
                                        <textarea style="height: 105px" class="form-control" name="address" id="" cols="30" rows="10">{{old('address') ?? (isset($user) && $user->address !== null ? $user->address : '')}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="">Ghi chú</label>
                                        <textarea style="height: 105px" class="form-control" name="description" id="" cols="30"
                                            rows="10">{{old('description')?? (isset($user) && $user->description !== null ? $user->description : '')}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ibox-button">
                <button class="btn btn-primary mr10" type="submit">Lưu lại</button>
                <a href="{{route('user.index')}}" class="btn btn-white" >Quay lại</a>
            </div>
                {{-- <input type="hidden" name="district" class="oldData district" value="{{old('district')}}">
                <input type="hidden" name="ward" class="oldData ward" value="{{old('ward')}}"> --}}
                <input type="hidden" class="oldDistrictId" value="{{old('district_id') ?? (isset($user) && $user->district_id !== null ? $user->district_id : '')}}">
                <input type="hidden" class="oldWardId" value="{{old('ward_id') ?? (isset($user) && $user->ward_id !== null ? $user->ward_id : '')}}">
        </form>
    </div>
@endsection
