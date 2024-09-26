<div id="footer">
    <!-- Footer Top -->
    <div id="footer-top" style="background: {{$footer['row1']['colorBackground']}};">
        <div class="container">
            <h3 style="color: {{$footer['row1']['colorText']}}"><i class="fa fa-paper-plane"></i><span> {{$footer['row1']['title']}}</span></h3>
            <div id="email">
                <form action="" method="post">
                    <input type="text" placeholder="Email của bạn">
                </form>
                <i class="fa fa-envelope" onclick="" style="color: {{$footer['row1']['colorBackground']}}"></i>
            </div>
        </div>
    </div>
    <!-- Footer Main -->
    <!-- Footer Main Top -->
    <div id="footer-main" style="background: {{$footer['colorBackground']}}">
        @php
            $colorTextRow2 = $footer['row2']['colorText'];
            $colorIcon = $footer['colorIcon'];
        @endphp
        <div class="container">
            <div class="footer-main-t">
                <a class="footer-main-t-left">
                    <i style="color:{{$colorIcon}}" class="fas fa-truck"></i>
                    <div class="footer-main-t-r" style="color: {{$colorTextRow2}}">
                        <b > {{$footer['row2']['column1']['title']}}</b>
                        <span>{{$footer['row2']['column1']['content']}}</span>
                    </div>
                </a>
                <a class="footer-main-t-main">
                    <i style="color:{{$colorIcon}}" class="fa fa-wallet"></i>
                    <div class="footer-main-t-r" style="color: {{$colorTextRow2}}">
                        <b> {{$footer['row2']['column2']['title']}}</b>
                        <span>{{$footer['row2']['column2']['content']}}</span>
                    </div>
                </a>
                <a class="footer-main-t-right">
                    <i style="color:{{$colorIcon}}" class="fas fa-exchange-alt"></i>
                    <div class="footer-main-t-r" style="color: {{$colorTextRow2}}">
                        <b> {{$footer['row2']['column3']['title']}}</b>
                        <span>{{$footer['row2']['column3']['title']}}</span>
                    </div>
                </a>
            </div>
        </div>
        <!-- Footer Main Bottom -->
        <style>
            .footer-main-b a {
                color: {{$footer['row3']['colorText']}};
            }
            .footer-main-b li:hover a {
                color: {{$generalSetting['colorTheme']}};
            }
            @media(max-width:576px){
                .footer-main-b-it-1-t li:nth-child(2) span, .footer-main-b-it-1-b li:nth-child(2) span {
                    color: {{$footer['row3']['colorText']}};
                }
                .footer-main-b ul li a, .footer-main-b ul li {
                    color: {{$footer['row3']['colorText']}};
                }
            }
        </style>
        <div class="footer-main-b" style="color: {{$footer['row3']['colorText']}} !important">
            <div class="container">
                <div class="row">
                    <!-- 1 -->
                    <div class="footer-main-b-it-1 col-lg-3 col-md-6 col-sm-12">
                        <h3><a href=""><img style="max-height: 50px" src="{{$system['homepage_logo']}}" alt=""></a></h3>
                        <h4 style="color: {{$footer['row3']['colorTitle']}}">{{$footer['row3']['column1']['title']}}</h4>
                        <ul class="general-info" style="color: #fff">
                            <div class="footer-main-b-it-1-t">
                                <li> <span>Tổng đài bán hàng</span> (8:30-21:00)</li>
                                <li><i style="color: {{$colorIcon}}" class="red fa fa-volume-control-phone"></i><span>{{$system['contact_sell_phone']}}</span> - <span>{{$system['contact_hotline']}}</span></li>
                                <li><i style="color: {{$colorIcon}}" class="red fa fa-envelope"></i>{{$system['contact_email']}}
                                </li>
                            </div>
                            <div class="footer-main-b-it-1-b">
                                <li><span>Tổng đài CSKH</span> (8:30-22:00) </li>
                                <li><i style="color: {{$colorIcon}}" class="red fa fa-fax"></i><span>{{$system['contact_technical_phone']}}</span>
                                </li>
                                <li><i style="color: {{$colorIcon}}" class="red fa fa-envelope"></i>{{$system['contact_email']}}</li>
                            </div>
                        </ul>
                        <div class="footer-main-b-media">
                            <a href="{{$system['social_facebook']}}"><i class="fab fa-facebook"></i></a>
                            <a href="{{$system['social_youtube']}}"><i class="fab fa-youtube"></i></a>
                            <a href="{{$system['social_tiktok']}}"><i class="fab fa-tiktok"></i></a>
                            <a href="{{$system['social_twitter']}}"><i class="fab fa-twitter"></i></a>
                            <a href="{{$system['social_instagram']}}"><i class="fab fa-instagram" style="color: #DA2549"></i></a>
                        </div>
                    </div>
                    @php
                        $menuSetup = setupData($menus);
                        $menuColum2 = $menuSetup[$footer['row3']['column2']['menu']];
                        $menuColum3 = $menuSetup[$footer['row3']['column3']['menu']];
                    @endphp
                    <!-- 2 -->
                    <div class="footer-main-b-it-2 col-lg-3 col-md-6 col-sm-12">
                        <ul>
                            <li>
                                <h4 style="color: {{$footer['row3']['colorTitle']}}">{{$footer['row3']['column2']['title']}}</h4>
                            </li>
                            @if(count($menuColum2->links))
                                @foreach($menuColum2->links->toArray() as $menuItem)
                                    <li><a href="{{setupUrl($menuItem['canonical'])}}">{{$menuItem['name']}}</a></li>
                                @endforeach
                            @endif
                            <li style="background: {{$colorIcon}}"><a href="#"><i class="fa fa-map mr-1"></i>Hệ thống</a></li>
                        </ul>
                    </div>
                    <!-- 3 -->
                    <div class="footer-main-b-it-3 col-lg-3 col-md-6 col-sm-12">
                        <ul>
                            <li>
                                <h4 style="color: {{$footer['row3']['colorTitle']}}">{{$footer['row3']['column3']['title']}}</h4>
                            </li>
                            @if(count($menuColum3->links))
                                @foreach($menuColum3->links->toArray() as $menuItem)
                                    <li><a href="{{setupUrl($menuItem['canonical'])}}">{{$menuItem['name']}}</a></li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                    <!-- 4 -->
                    <div class="footer-main-b-it-4 col-lg-3 col-md-6 col-sm-12">
                        <h4>Fanpage Facebook</h4>
                        <div class="link-follow"><a href="{{$system['social_facebook']}}"><img class="img-fluid"
                                    src="{{$system['social_facebook']}}" alt=""></a></div>
                        <h4 class="method-pay">Phương thức thanh toán</h4>
                        <div class="group-icon">
                            <i class="fab fa-cc-visa"></i>
                            <i class="fab fa-paypal"></i>
                            <i class="fas fa-credit-card"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer Bottom  -->
    <div id="footer-bottom" style="background: {{$footer['row4']['colorBackground']}}; border-top: 1px solid #666">
        <div class="container" style="color: {{$footer['row4']['colorText']}};">
            {{$system['homepage_copyright']}}. Văn phòng giao dịch: {{$system['contact_address']}}
            <br>
            GP số 48A/GP-TTĐT do sở TTTT TP HN cấp ngày 02/07/2024. Địa chỉ công ty: {{$system['contact_office']}}
        </div>
    </div>
</div>