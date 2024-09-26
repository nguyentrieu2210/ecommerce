<div class="overlay-content" id="formModuleProductHot">
    <form method="post" action="{{route('system.theme.editor')}}">
        @csrf
        <input type="hidden" name="keyword" value="module_product_hot">
        <div class="module-detail-title flex">
            <i class="fa fa-chevron-left btnCloseForm"></i>
            <h3>Trang chủ - Module Sản phẩm Hot</h3>
        </div>
        <div class="module-detail-content">
            <div class="ibox-module">
                <h4>Thiết lập chung</h4>
                <input type="hidden" name="name" value="Module sản phẩm Hot">
                <div class="flex mt20">
                    <input type="color" id="colorPicker" name="colorTheme" value="{{$moduleProductHot['colorTheme']}}">
                    <label class="fw500 m0 ml10" for="colorPicker">Màu sắc chủ đạo</label>
                </div>
                <div class="flex mt20">
                    <input type="color" id="colorPicker" name="colorBackground" value="{{$moduleProductHot['colorBackground']}}">
                    <label class="fw500 m0 ml10" for="colorPicker">Màu nền tiêu đề</label>
                </div>
                <div class="flex mt20">
                    <input type="color" id="colorPicker" name="colorTitle" value="{{$moduleProductHot['colorTitle']}}">
                    <label class="fw500 m0 ml10" for="colorPicker">Màu chữ tiêu đề</label>
                </div>
                <div class="form-group mt20">
                    <label for="" class="fw500">Tiêu đề</label>
                    <input class="form-control" type="text" name="title" value="{{$moduleProductHot['title']}}" placeholder="Nhập tiêu đề">
                </div>
                <div class="form-group mt20">
                    <label for="" class="fw500">Tiêu đề nút xem nhiều phía dưới</label>
                    <input class="form-control" type="text" name="titleButton" value="{{$moduleProductHot['titleButton']}}" placeholder="Nhập tiêu đề nút xem thêm">
                </div>
                <div class="form-group mt20">
                    <label for="" class="fw500">Đường dẫn nút xem nhiều phía dưới</label>
                    <input class="form-control" type="text" name="canonicalButton" value="{{$moduleProductHot['canonicalButton']}}" placeholder="Nhập đường dẫn nút xem thêm">
                </div>
                <div class="form-group mt20">
                    <label class="fw500" for="">Chọn Widget hiển thị</label>
                    <select name="widget" style="width:100%" class="ml setupSelect2">
                        @foreach($widgets as $widget)
                            @if($widget->model == 'product')
                                <option {{$moduleProductHot['widget'] == $widget->keyword ? 'selected' : ''}} value="{{$widget->keyword}}">{{$widget->name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <button class="btn btn-primary" type="submit">Lưu lại</button>
        </div>
    </form>
</div>