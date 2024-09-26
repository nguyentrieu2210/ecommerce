<div class="overlay-content" id="formPostCatalogue">
    <form method="post" action="{{route('system.theme.editor')}}">
        @csrf
        <input type="hidden" name="keyword" value="blog">
        <div class="module-detail-title flex">
            <i class="fa fa-chevron-left btnCloseForm"></i>
            <h3>Trang Blog</h3>
        </div>
        <div class="module-detail-content">
            <div class="ibox-module">
                <h4>Thiết lập chung</h4>
                <div class="form-group mt20">
                    <label class="fw500" for="">Chọn Menu tin tức</label>
                    <select name="menu" style="width:100%" class="ml setupSelect2">
                        @foreach($menus as $menu)
                            <option {{$blog['menu'] == $menu->keyword ? 'selected' : ''}} value="{{$menu->keyword}}">{{$menu->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="ibox-module">
                <h4>Thiết lập cột tin tức bên trái</h4>
                <div class="form-group mt20">
                    <label for="" class="fw500">Tiêu đề</label>
                    <input class="form-control" type="text" name="title" value="{{$blog['title']}}" placeholder="Nhập tiêu đề">
                </div>
                <div class="form-group mt20">
                    <label for="" class="fw500">Đường dẫn cho tiêu đề</label>
                    <input class="form-control" type="text" name="canonical" value="{{$blog['canonical']}}" placeholder="Nhập đường dẫn cho tiêu đề">
                </div>
                <div class="flex mt20">
                    <input type="color" name="colorTitle" value="{{$blog['colorTitle']}}">
                    <label class="fw500 m0 ml10">Màu tiêu đề</label>
                </div>
                <div class="form-group mt20">
                    <label class="fw500" for="">Chọn Widget tin tức hiển thị</label>
                    <select name="widget" style="width:100%" class="ml setupSelect2">
                        @foreach($widgets as $widget)
                            @if($widget->model == 'post')
                                <option {{$blog['widget'] == $widget->keyword ? 'selected' : ''}} value="{{$widget->keyword}}">{{$widget->name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <button class="btn btn-primary" type="submit">Lưu lại</button>
        </div>
    </form>
</div>