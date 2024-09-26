@extends('frontend.layouts.layout')
@section('content')
<div id="custummer">
    @include('frontend.account.components.breadcrumb', ['title' => 'Thay đổi mật khẩu'])
    <div id="custummer-main">
        <div class="container">
            <div class="row">
                @include('frontend.account.components.sidebar')
                <div id="account-main" class="col-lg-9 col-md-8 col-sm-12">
                    <h3>Thay đổi mật khẩu</h3>
                    <input type="hidden" class="passwordAccount" value="{{Auth::guard('customer')->user()->password}}">
                    <form method="POST" class="formRePassword customize-alert-error" action="{{route('update.password', Auth::guard('customer')->user()->id)}}">
                    @csrf
                    <table class = "account-re-password">
                        <tr>
                            <td><label for="password">Mật khẩu cũ</label></td>
                            <td class="box-input-password">
                                <input type="password" name="password" id="password" class="form-control inputValidate" placeholder="Nhập lại mật khẩu cũ">
                                <i class="fas fa-eye"></i>
                            </td>

                        </tr>
                        <tr>
                            <td><label for="new_password">Mật khẩu mới</label></td>
                            <td class="box-input-password">
                                <input type="password" name="new_password" id="new_password" class="form-control inputValidate" placeholder="Nhập mật khẩu mới" value="{{old('new_password')}}">
                                <i class="fas fa-eye"></i>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="re_password">Nhập lại</label></td>
                            <td class="box-input-password">
                                <input type="password" name="re_password" id="re_password" class="form-control inputValidate" placeholder="Nhập lại mật khẩu mới" value="">
                                <i class="fas fa-eye"></i>
                            </td>       
                        </tr>
                        <tr>
                            <td></td>
                            <td><button type="submit">CẬP NHẬT</button></td>
                        </tr>
                    </table>
                </form>
                </div>
            </div>
        </div>
    </div>
</div> 
@endsection
