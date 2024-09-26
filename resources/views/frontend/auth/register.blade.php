@extends('frontend.layouts.layout')
@section('content')
<div id="login">
    <div class="container">
        <div id="global-register">
            <a href="/">Trang chủ</a> / <b>Đăng kí</b>
        </div>
        <div class="row">
            <div id="login-main" class="col-lg-8 col-md-10 col-sm-12 mx-auto">
                <div class="register-main-t">
                    <a href="{{setupUrl('dang-ki')}}">ĐĂNG KÝ</a>
                    <a href="{{setupUrl('dang-nhap')}}">ĐĂNG NHẬP</a>
                </div>
                <div class="login-main-m">
                    <form class="formRegister" action="{{route('register.store')}}" method="post">
                        @csrf
                        <div class="login-item form-group">
                            <label for="name">Name <span>*</span></label>
                            <input class="form-control inputValidate" name="name" id="name" type="text">
                        </div>
                        <div class="login-item form-group">
                            <label for="phone">Số điện thoại <span>*</span></label>
                            <input class="form-control inputValidate" name="phone" id="phone" type="text">
                        </div>
                        <div class="login-item form-group">
                            <label for="email">Email <span>*</span></label>
                            <input class="form-control inputValidate" name="email" id="email" type="text">
                        </div>
                        <div class="login-item form-group ">
                            <label for="password">Mật khẩu <span>*</span></label>
                            <div class="box-input-password">
                                <input class="form-control inputValidate" name="password" type="password" id="password">
                                <i class="fas fa-eye"></i>
                            </div>
                        </div>
                        <div class="login-item form-group ">
                            <label for="password">Nhập lại mật khẩu <span>*</span></label>
                            <div class="box-input-password">
                                <input class="form-control inputValidate" name="re_password" type="password" id="re_password">
                                <i class="fas fa-eye"></i>
                            </div>
                        </div>
                        <div class="login-item form-group">
                            <label for="">Tỉnh / Thành phố</label>
                            <select style="width: 100%" name="province_id" class="Province ml setupSelect2 changeLocation" data-target="District">
                                <option value="0">Chọn tỉnh / thành phố</option>
                                @foreach ($provinces as $key => $val)
                                <option value="{{ $val->code }}">{{ $val->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="login-item form-group">
                            <label for="">Quận / Huyện</label>
                            <select style="width: 100%" name="district_id" class="District ml setupSelect2 changeLocation" data-target="Ward">
                                <option value="0">Chọn quận / huyện</option>
                            </select>
                        </div>
                        <div class="login-item form-group">
                            <label for="">Phường / Xã</label>
                            <select style="width: 100%" name="ward_id" class="Ward ml setupSelect2">
                                <option value="0">Chọn phường / xã</option>
                            </select>
                        </div>
                        <div class="login-item form-group">
                            <label>Ngày sinh</label>
                            <input type="text" class="form-control setupDatePicker" name="birthday" value="">
                        </div>
                        <div class="login-item form-group">
                            <label for="address">Địa chỉ nhà</label>
                            <input class="form-control inputValidate" name="address" id="address" type="text">
                        </div>
                        <button type="submit" class="button-register" name="sbm">ĐĂNG KÍ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection