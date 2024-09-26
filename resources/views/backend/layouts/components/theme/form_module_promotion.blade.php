<div class="overlay-content" id="formModulePromotion">
    <form method="post" action="{{route('system.theme.editor')}}">
        @csrf
        <input type="hidden" name="keyword" value="module_promotion">
        <div class="module-detail-title flex">
            <i class="fa fa-chevron-left btnCloseForm"></i>
            <h3>Trang chủ - Giờ vàng giá sốc</h3>
        </div>
        <div class="module-detail-content">
            <div class="ibox-module">
                <h4>Thiết lập chung</h4>
                <input type="hidden" name="name" value="Module giờ vàng giá sốc">
                <div class="flex mt20">
                    <input type="color" id="colorPicker" name="colorTheme" value="{{$modulePromotion['colorTheme']}}">
                    <label class="fw500 m0 ml10" for="colorPicker">Màu sắc chủ đạo</label>
                </div>
                <div class="flex mt20">
                    <input type="color" id="colorPicker" name="colorBackgroundTime" value="{{$modulePromotion['colorBackgroundTime']}}">
                    <label class="fw500 m0 ml10" for="colorPicker">Màu nền ô thời gian đếm ngược</label>
                </div>
                <div class="form-group mt20">
                    <label class="fw500" for="">Chọn Widget hiển thị</label>
                    <select name="widget" style="width:100%" class="ml setupSelect2">
                        @foreach($widgets as $widget)
                            @if($widget->model == 'product')
                                <option {{$modulePromotion['widget'] == $widget->keyword ? 'selected' : ''}} value="{{$widget->keyword}}">{{$widget->name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="ibox-module">
                <h4>Ảnh nền tiêu đề Module</h4>
                <span class="mt20 inline-block">Ảnh nền nhỏ phía trên</span>
                <div class="box-choose-image">
                    <div class="flex m0">
                        <div class="box-choose-image-left">
                            <img src="{{$modulePromotion['smallImage']}}" alt="">
                            <input type="hidden" name="smallImage" value="{{$modulePromotion['smallImage']}}">
                        </div>
                        <div class="box-choose-image-right">
                            <svg data-click="noImage" class="upload-image next-icon next-icon--color-blue next-icon--size-16"> <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#next-import"></use> </svg>
                            <span data-click="noImage" class="upload-image">Thay thế</span>
                        </div>
         
                    </div>
                </div>
                <span class="mt20 inline-block">Ảnh nền lớn phía dưới</span>
                <div class="box-choose-image">
                    <div class="flex m0">
                        <div class="box-choose-image-left">
                            <img src="{{$modulePromotion['bigImage']}}" alt="">
                            <input type="hidden" name="bigImage" value="{{$modulePromotion['bigImage']}}">
                        </div>
                        <div class="box-choose-image-right">
                            <svg data-click="noImage" class="upload-image next-icon next-icon--color-blue next-icon--size-16"> <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#next-import"></use> </svg>
                            <span data-click="noImage" class="upload-image">Thay thế</span>
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary" type="submit">Lưu lại</button>
        </div>
    </form>
</div>