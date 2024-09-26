<div id="account-sidebar" class="col-lg-3 col-md-4 col-sm-12">
    <style>
        #account-sidebar-t > i {
            color: {{$generalSetting['colorTheme']}} !important;
        }
        #custummer #custummer-main .row #account-main table.account-infor tr button, #custummer #custummer-main .row #account-main table.account-re-password tr button {
            background-image: linear-gradient(to left, RGB(18, 103, 174), {{$generalSetting['colorTheme']}});
        }
    </style>
    <div id="account-sidebar-t">
         <i class="fas fa-user-circle"></i>
        <div class="account-sidebar-t-r">
            <span>Tài khoản</span>
            <b>{{Auth::guard('customer')->user()->name}}</b>
        </div>
    </div>
    <div id="account-sidebar-b">
        <div class="account-sidebar-b-item"><a href="{{route('account.info')}}"><i class="fas fa-user"></i>Thông tin tài khoản</a></div>
        <div class="account-sidebar-b-item"><a href="{{route('account.order')}}"><i class="fa fa-file-alt"></i>Quản lý đơn hàng</a></div>
        <div class="account-sidebar-b-item"><a href="{{route('account.repassword')}}"><i class="fas fa-lock"></i>Thay đổi mật khẩu</a></div>
        <div class="account-sidebar-b-item"><a href="{{route('logout')}}"><i class="fas fa-power-off"></i>Logout</a></div>
    </div>
</div>