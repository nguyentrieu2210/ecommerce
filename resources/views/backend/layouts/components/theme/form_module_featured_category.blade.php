<div class="overlay-content" id="formModuleFeaturedCategory">
    <form method="post" action="{{route('system.theme.editor')}}">
        @csrf
        <input type="hidden" name="keyword" value="module_featured_category">
        <div class="module-detail-title flex">
            <i class="fa fa-chevron-left btnCloseForm"></i>
            <h3>Trang chủ - Module Danh mục nổi bật</h3>
        </div>
        <div class="module-detail-content">
            <div class="ibox-module">
                <h4>Thiết lập chung</h4>
                <input type="hidden" name="name" value="Module danh mục nổi bật">
                <div class="flex mt20">
                    <input type="color" id="colorPicker" name="colorBackground" value="{{$moduleFeaturedCategory['colorBackground']}}">
                    <label class="fw500 m0 ml10" for="colorPicker">Màu nền</label>
                </div>
                <div class="flex mt20">
                    <input type="color" id="colorPicker" name="colorTitle" value="{{$moduleFeaturedCategory['colorTitle']}}">
                    <label class="fw500 m0 ml10" for="colorPicker">Màu chữ tiêu đề</label>
                </div>
                <div class="flex mt20">
                    <input type="color" id="colorPicker" name="colorText" value="{{$moduleFeaturedCategory['colorText']}}">
                    <label class="fw500 m0 ml10" for="colorPicker">Màu chữ</label>
                </div>
                <div class="form-group mt20">
                    <label for="" class="fw500">Tiêu đề</label>
                    <input class="form-control" type="text" name="title" value="{{$moduleFeaturedCategory['title']}}" placeholder="Nhập tiêu đề">
                </div>
                <div class="form-group mt20">
                    <label class="fw500" for="">Chọn danh mục hiển thị</label>
                    <select name="widget" style="width:100%" class="ml setupSelect2">
                        @foreach($widgets as $widget)
                            @if($widget->model == 'productCatalogue')
                                <option {{$moduleFeaturedCategory['widget'] == $widget->keyword ? 'selected' : ''}} value="{{$widget->keyword}}">{{$widget->name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <button class="btn btn-primary" type="submit">Lưu lại</button>
        </div>
    </form>
</div>