<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin-Ecommerce | Login</title>
    @include('backend.layouts.components.head')

</head>

<body class="gray-bg">

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>

                <h1 class="logo-name">E+</h1>

            </div>
            <h3>Admin-Ecommerce</h3>
            @include('backend.layouts.components.errors')

            <form method="post" action="/admin/login">
                @csrf
                <div class="form-group">
                    <input name="email" type="text" class="form-control" placeholder="Email" value="{{old('email')}}">
                </div>
                <div class="form-group">
                    <input name="password" type="password" class="form-control" placeholder="Mật khẩu">
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">Đăng nhập</button>

                <a href=""><small>Quên mật khẩu?</small></a>
            </form>
        </div>
    </div>

    @include('backend.layouts.components.script')

</body>

</html>
