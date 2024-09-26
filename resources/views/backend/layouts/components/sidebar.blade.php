<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element"> <span>
                        <img class="img-avatar" alt="image" class="img-circle" src="/backend/img/empty-avatar.png">
                         </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">Nguyễn Văn Triệu</strong>
                         </span> <span class="text-muted text-xs block">Người sáng lập <b class="caret"></b></span> </span> </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="#">Cập nhật thông tin cá nhân</a></li>
                        <li><a href="#">Thay đổi mật khẩu</a></li>
                        <li><a href="#">Mail</a></li>
                        <li class="divider"></li>
                        <li><a href="{{Route('auth.logout')}}">Đăng xuất</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    NT
                </div>
            </li>
            @foreach (__('sidebar') as $key => $item)
            @php
                $nameModule = request()->segment(2);
            @endphp
            <li class="{{(isset($nameModule) && in_array($nameModule, $item['name'])) ? 'active' : ''}}">
                <a href=""><i class="{{$item['icon']}}"></i> <span class="nav-label">{{$item['title']}}</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    @foreach($item['submodule'] as $subItem)
                    <li><a href="{{$subItem['route']}}">{{$subItem['title']}}</a></li>
                    @endforeach
                </ul>
            </li>
            @endforeach

        </ul>
    </div>
</nav>