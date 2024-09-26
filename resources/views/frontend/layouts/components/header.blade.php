    <!-- Global Banner  -->
    <div id="global-banner" style="background: {{$header['backgroundBanner']}}">
        <a href="#"><img class="img-fluid" src="{{$header['image']}}" alt=""></a>
    </div>
    <!-- Header Main  -->
    <div id="header-main">
        <div class="container">
            <div class="row">
                <div id="logo"  class="col-lg-3 col-md-3 col-sm-3 col-3">
                    <h1><a href="/"><img class="img-fluid" src="{{$system['homepage_logo']}}" alt=""></a>
                    </h1>
                </div>
                <div id="search" class="col-lg-5 col-md-6 col-sm-6 col-6">
                    <form method="post">
                        <input type="text" name="search" placeholder="Tìm kiếm">
                        <button style="background: {{json_decode($system['general_setting'], true)['colorTheme']}}" type="submit" name="sbm"><i class="fa fa-search"></i></button>
                    </form>
                </div>
                <div id="header-main-right" class="col-lg-4 col-md-3 col-sm-3 col-3">
                    <div id="follow">
                        <a href="#">Theo dõi<br>đơn hàng</a>
                    </div>
                    <div id="user">
                        @if(Auth::guard('customer')->check())
                            <a href="{{route('account.info')}}">
                                <span class="fw500">Xin chào</span><br>
                                <span>{{Auth::guard('customer')->user()->name}}</span>
                            </a>
                        @else
                            <a href="{{setupUrl('dang-nhap')}}">Đăng nhập</a>
                            <a href="{{setupUrl('dang-ki')}}">Đăng ký</a>
                        @endif
                    </div>
                    <a id = "cart" href="./cart.html"><span>0</span></a>
                    <div id="header-mobile-right">
                        <label for="nav-mobile-input" id="menu-mobile-open">
                            <i class="fa fa-bars"></i>
                        </label>
                        <!-- <a id = "cart" href="#"><span>0</span></a> -->
                        <!--NAV Mobile-->
                        <input type="checkbox" name="" hidden class="menu-mobile-input" id="nav-mobile-input">
                        <div id="menu-mobile">
                            <div id="menu-mobile-t">
                                <label id="menu-mobile-close" for="nav-mobile-input">
                                    <i class="fa fa-times"></i>
                                    <span>Đóng</span>
                                </label>
                                <div id="menu-mobile-list">
                                    @foreach($categories as $category)
                                        <div class="menu-mobile-list-item"><a href="{{setupUrl($category->canonical)}}">{{$category->name}}</a></div>
                                    @endforeach
                                </div>
                            </div>
                            <div id="menu-mobile-login">
                                <div id="account">
                                    @if(Auth::guard('customer')->check())
                                        <a href="{{setupUrl('thong-tin-tai-khoan')}}">
                                            <span class="fw500">Xin chào </span>
                                            <span>{{Auth::guard('customer')->user()->name}}</span>
                                        </a>
                                    @else
                                        <a href="{{setupUrl('dang-nhap')}}">Đăng nhập</a>
                                        <a href="{{setupUrl('dang-ki')}}">Đăng ký</a>
                                    @endif
                                </div>
                            </div>
                            <div id="menu-mobile-b">
                                <div class="menu-mobile-b-t">
                                    <a href="">Theo dõi đơn hàng</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>