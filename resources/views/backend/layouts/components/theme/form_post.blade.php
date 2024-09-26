<div class="overlay-content" id="formPost">
    <form method="post" action="{{route('system.theme.editor')}}">
        @csrf
        <input type="hidden" name="keyword" value="post">
        <div class="module-detail-title flex">
            <i class="fa fa-chevron-left btnCloseForm"></i>
            <h3>Trang bài viết</h3>
        </div>
        <div class="module-detail-content">
            <div class="ibox-module">
                <h4>Thiết lập cột tin tức bên trái</h4>
                <div class="form-group mt20">
                    <label for="" class="fw500">Tiêu đề</label>
                    <input class="form-control" type="text" name="title" value="{{$post['title']}}" placeholder="Nhập tiêu đề">
                </div>
                <div class="flex mt20">
                    <input type="color" name="colorTitle" value="{{$post['colorTitle']}}">
                    <label class="fw500 m0 ml10">Màu tiêu đề</label>
                </div>
                <div class="form-group mt20">
                    <label class="fw500" for="">Chọn Widget tin tức hiển thị</label>
                    <select name="widget" style="width:100%" class="ml setupSelect2">
                        @foreach($widgets as $widget)
                            @if($widget->model == 'post')
                                <option {{$post['widget'] == $widget->keyword ? 'selected' : ''}} value="{{$widget->keyword}}">{{$widget->name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <button class="btn btn-primary" type="submit">Lưu lại</button>
        </div>
    </form>
</div>