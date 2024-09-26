<div class="overlay-content" id="formHeader">
    <form method="post" action="{{route('system.theme.editor')}}">
        @csrf
        <input type="hidden" name="keyword" value="header">
        <div class="module-detail-title flex">
            <i class="fa fa-chevron-left btnCloseForm"></i>
            <h3>Đầu trang</h3>
        </div>
        <div class="module-detail-content">
            <div class="ibox-module">
                <h4>Tùy chọn hiển thị Banner Đầu trang</h4>
                <input type="hidden" name="useBanner" value="false">
                <div class="checkbox-customize">
                    <input {{$header['useBanner'] == 'true' ? "checked" : ""}} type="checkbox" id="useMainBanner" name="useBanner" value="true">
                    <label for="useMainBanner">Sử dụng Banner đầu trang</label>
                </div>
                <div class="flex mt20">
                    <input type="color" id="colorPicker" name="backgroundBanner" value="{{$header['backgroundBanner']}}">
                    <label class="fw500 m0 ml10" for="colorPicker">Màu nền</label>
                </div>
                <div class="box-choose-image">
                    <div class="flex m0">
                        <div class="box-choose-image-left">
                            <img src="{{$header['image']}}" alt="">
                            <input type="hidden" name="image" value="{{$header['image']}}">
                        </div>
                        <div class="box-choose-image-right">
                            <svg data-click="noImage" class="upload-image next-icon next-icon--color-blue next-icon--size-16"> <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#next-import"></use> </svg>
                            <span data-click="noImage" class="upload-image">Thay thế</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ibox-module">
                <h4>Tùy chỉnh menu trang chủ</h4>
                <div class="flex mt20">
                    <input type="color" id="colorPicker" name="backgroundMenu" value="{{$header['backgroundMenu']}}">
                    <label class="fw500 m0 ml10" for="colorPicker">Màu nền</label>
                </div>
                <div class="flex mt20">
                    <input type="color" id="colorPicker" name="colorMenu" value="{{$header['colorMenu']}}">
                    <label class="fw500 m0 ml10" for="colorPicker">Màu chữ</label>
                </div>
                <div class="form-group mt20">
                    <label class="fw500" for="">Chọn Menu hiển thị</label>
                    <select name="menu" style="width:100%" class="ml setupSelect2">
                        @foreach($menus as $menu)
                            <option {{$header['menu'] == $menu->keyword ? 'selected' : ''}} value="{{$menu->keyword}}">{{$menu->name}}</option>
                        @endforeach
                    </select>
                </div>
                
            </div>
            <div class="ibox-module">
                <h4>Thiết lập Slider</h4>
                <div class="form-group mt20">
                    <label class="fw500" for="">Chọn Slide hiển thị</label>
                    <select name="slide" style="width:100%" class="ml setupSelect2">
                        @foreach($slides as $slide)
                            <option {{$header['slide'] == $slide->keyword ? 'selected' : ''}} value="{{$slide->keyword}}">{{$slide->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button class="btn btn-primary" type="submit">Lưu lại</button>
        </div>
    </form>
</div>