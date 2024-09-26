<div class="overlay-content" id="formModuleNews">
    <form method="post" action="{{route('system.theme.editor')}}">
        @csrf
        <input type="hidden" name="keyword" value="module_news">
        <div class="module-detail-title flex">
            <i class="fa fa-chevron-left btnCloseForm"></i>
            <h3>Trang chủ - Module Tin tức</h3>
        </div>
        <div class="module-detail-content">
            <div class="ibox-module">
                <h4>Thiết lập cột tin tức bên phải</h4>
                <input type="hidden" name="name" value="Module tin tức">
                <div class="flex mt20">
                    <input type="color" id="colorPicker" name="colorBackground" value="{{$moduleNews['colorBackground']}}">
                    <label class="fw500 m0 ml10" for="colorPicker">Màu nền</label>
                </div>
                <div class="flex mt20">
                    <input type="color" id="colorPicker" name="colorTitleLeft" value="{{$moduleNews['colorTitleLeft']}}">
                    <label class="fw500 m0 ml10" for="colorPicker">Màu chữ tiêu đề góc trái</label>
                </div>
                <div class="form-group mt20">
                    <label for="" class="fw500">Tiêu đề góc trái</label>
                    <input class="form-control" type="text" name="titleLeft" value="{{$moduleNews['titleLeft']}}" placeholder="Nhập tiêu đề">
                </div>
                <div class="flex mt20">
                    <input type="color" id="colorPicker" name="colorTitleRight" value="{{$moduleNews['colorTitleRight']}}">
                    <label class="fw500 m0 ml10" for="colorPicker">Màu chữ tiêu đề góc phải</label>
                </div>
                <div class="form-group mt20">
                    <label for="" class="fw500">Tiêu đề góc phải</label>
                    <input class="form-control" type="text" name="titleRight" value="{{$moduleNews['titleRight']}}" placeholder="Nhập tiêu đề">
                </div>
                <div class="form-group mt20">
                    <label for="" class="fw500">Đường dẫn cho tiêu đề góc phải</label>
                    <input class="form-control" type="text" name="canonicalTitleRight" value="{{$moduleNews['canonicalTitleRight']}}" placeholder="Nhập đường dẫn">
                </div>
                <div class="flex mt20">
                    <input type="color" id="colorPicker" name="colorText" value="{{$moduleNews['colorText']}}">
                    <label class="fw500 m0 ml10" for="colorPicker">Màu chữ</label>
                </div>
                <div class="form-group mt20">
                    <label class="fw500" for="">Chọn tin tức hiển thị</label>
                    <select name="widget" style="width:100%" class="ml setupSelect2">
                        @foreach($widgets as $widget)
                            @if($widget->model == 'post')
                                <option {{$moduleNews['widget'] == $widget->keyword ? 'selected' : ''}} value="{{$widget->keyword}}">{{$widget->name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="ibox-module">
                <h4>Thiết lập Banner bên trái</h4>
                <div class="box-choose-image">
                    <div class="flex m0">
                        <div class="box-choose-image-left">
                            <img src="{{$moduleNews['banner']}}" alt="">
                            <input type="hidden" name="banner" value="{{$moduleNews['banner']}}">
                        </div>
                        <div class="box-choose-image-right">
                            <svg data-click="noImage" class="upload-image next-icon next-icon--color-blue next-icon--size-16"> <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#next-import"></use> </svg>
                            <span data-click="noImage" class="upload-image">Thay thế</span>
                        </div>
                    </div>
                </div>
                <div class="form-group mt20">
                    <label for="" class="fw500">Đường dẫn</label>
                    <input class="form-control" type="text" name="canonical" value="{{$moduleNews['canonical']}}" placeholder="Nhập đường dẫn">
                </div>
            </div>
            <button class="btn btn-primary" type="submit">Lưu lại</button>
        </div>
    </form>
</div>