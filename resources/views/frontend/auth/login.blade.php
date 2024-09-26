@extends('frontend.layouts.layout')
@section('content')
<div id="login">
    <div class="container">
        <div id="global-login">
            <a href="/">Trang chủ</a> / <b>Đăng nhập</b>
        </div>
        <div class="row">
            <div id="login-main" class="col-lg-8 col-md-10 col-sm-12 mx-auto">
                <div class="login-main-t">
                    <a href="{{setupUrl('dang-ki')}}">ĐĂNG KÝ</a>
                    <a href="{{setupUrl('dang-ki')}}">ĐĂNG NHẬP</a>
                </div>
                <div class="login-main-m">
                    <form class="formLogin" action="{{route('login')}}" method="post">
                        @csrf
                        <div class="login-item form-group">
                            <label for="email">Email <span>*</span></label>
                            <input id="email" type="text" name="email" class="form-control inputValidate">
                        </div>
                        <div class="login-item form-group ">
                            <label for="password">Password <span>*</span></label>
                            <div class="box-input-password">
                                <input type="password" id="password" name="password" class="form-control inputValidate">
                                <i class="fas fa-eye"></i>
                            </div>
                        </div>
                        <a class="forget" href="">Quên mật khẩu?</a>
                        <button type="submit" class="sbmLogin">ĐĂNG NHẬP</button>
                    </form>
                    <span class="or" >Hoặc</span>
                </div>
                <div class="login-main-b">
                    <a href="{{ url('auth/facebook') }}">
                        <i class="fab fa-facebook-square"></i>
                        ĐĂNG NHẬP BẰNG FACEBOOK
                    </a>
                    <a href="{{ url('auth/google') }}">
                        <i class="fab fa-google"></i>
                        ĐĂNG NHẬP BẰNG GOOGLE
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
