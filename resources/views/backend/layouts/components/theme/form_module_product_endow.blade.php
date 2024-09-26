<div class="overlay-content" id="formModuleProductEndow">
    <form method="post" action="{{route('system.theme.editor')}}">
        @csrf
        <input type="hidden" name="keyword" value="module_product_endow">
        <div class="module-detail-title flex">
            <i class="fa fa-chevron-left btnCloseForm"></i>
            <h3>Trang chủ - Module Ưu đãi dành cho bạn</h3>
        </div>
        <div class="module-detail-content">
            <div class="ibox-module">
                <h4>Thiết lập tiêu đề module</h4>
                <input type="hidden" name="name" value="Module ưu đãi dành cho bạn">
                <div class="flex mt20">
                    <input type="color" id="colorPicker" name="colorTitle" value="{{$moduleProductEndow['colorTitle']}}">
                    <label class="fw500 m0 ml10" for="colorPicker">Màu chữ tiêu đề</label>
                </div>
                <div class="form-group mt20">
                    <label for="" class="fw500">Tiêu đề</label>
                    <input class="form-control" type="text" name="title" value="{{$moduleProductEndow['title']}}" placeholder="Nhập tiêu đề">
                </div>
                <div class="form-group mt20">
                    <label class="fw500" for="">Chọn Widget hiển thị</label>
                    <select name="widget" style="width:100%" class="ml setupSelect2">
                        @foreach($widgets as $widget)
                            @if($widget->model == 'product')
                                <option {{$moduleProductEndow['widget'] == $widget->keyword ? 'selected' : ''}} value="{{$widget->keyword}}">{{$widget->name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="ibox-module">
                <h4>Tùy chỉnh sản phẩm module</h4>
                <div class="flex mt20">
                    <input type="color" id="colorPicker" name="colorBackgroundDiscount" value="{{$moduleProductEndow['colorBackgroundDiscount']}}">
                    <label class="fw500 m0 ml10" for="colorPicker">Màu nền ô giảm giá góc phải sản phẩm</label>
                </div>
                <div class="flex mt20">
                    <input type="color" id="colorPicker" name="colorTextDiscount" value="{{$moduleProductEndow['colorTextDiscount']}}">
                    <label class="fw500 m0 ml10" for="colorPicker">Màu chữ ô giảm giá góc phải sản phẩm</label>
                </div>
            </div>
            <button class="btn btn-primary" type="submit">Lưu lại</button>
        </div>
    </form>
</div>