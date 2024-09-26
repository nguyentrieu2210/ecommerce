<div class="overlay-content" id="formFooter">
    <form method="post" action="{{route('system.theme.editor')}}">
        @csrf
        <input type="hidden" name="keyword" value="footer">
        <div class="module-detail-title flex">
            <i class="fa fa-chevron-left btnCloseForm"></i>
            <h3>Chân trang</h3>
        </div>
        <div class="module-detail-content">
            <div class="ibox-module">
                <h4>Thiết lập Email Marketing</h4>
                <div class="flex mt20">
                    <input type="color" name="row1[colorBackground]" value="{{$footer['row1']['colorBackground']}}">
                    <label class="fw500 m0 ml10" for="colorPicker">Màu nền</label>
                </div>
                <div class="flex mt20">
                    <input type="color" name="row1[colorText]" value="{{$footer['row1']['colorText']}}">
                    <label class="fw500 m0 ml10" for="colorPicker">Màu chữ</label>
                </div>
                <div class="form-group mt20">
                    <label for="" class="fw500">Tiêu đề</label>
                    <input class="form-control" type="text" name="row1[title]" value="{{$footer['row1']['title']}}" placeholder="Nhập tiêu đề">
                </div>
                <div class="form-group mt20">
                    <label for="" class="fw500">Link action Mailchimp</label>
                    <input class="form-control" type="text" name="row1[link]" value="{{$footer['row1']['link']}}" placeholder="Nhập tiêu đề">
                    <p class="text-shadow mt5">Ví dụ: https://gmail.us2.list-manage.com/subscribe/post-json?u=ef7f65e3be67e30ff1c4</p>
                    <p class="mt15">Link đăng ký mailchimp: https://login.mailchimp.com/signup/</p>
                </div>
            </div>
            <div class="ibox-module">
                <h4>Thiết lập chung cho hàng 2, 3 chân trang</h4>
                <div class="flex mt20">
                    <input type="color" name="colorBackground" value="{{$footer['colorBackground']}}">
                    <label class="fw500 m0 ml10" for="colorPicker">Màu nền</label>
                </div>
                <div class="flex mt20">
                    <input type="color" name="colorIcon" value="{{$footer['colorIcon']}}">
                    <label class="fw500 m0 ml10" for="colorPicker">Màu Icon</label>
                </div>
            </div>
            <div class="ibox-module">
                <h4>Thiết lập hàng 2 chân trang</h4>
                <div class="flex mt20">
                    <input type="color" name="row2[colorText]" value="{{$footer['row2']['colorText']}}">
                    <label class="fw500 m0 ml10" for="colorPicker">Màu chữ</label>
                </div>
                @for($i = 1; $i <=3; $i++)
                <div class="form-group mt20">
                    <label for="" class="fw500">Tiêu đề cột {{$i}}</label>
                    <input class="form-control" type="text" name="row2[column{{$i}}][title]" value="{{$footer['row2']['column'.$i]['title']}}" placeholder="Nhập tiêu đề">
                </div>
                <div class="form-group">
                    <label for="" class="fw500">Nội dung cột {{$i}}</label>
                    <textarea name="row2[column{{$i}}][content]" cols="30" rows="2">{{$footer['row2']['column'.$i]['content']}}</textarea>
                </div>
                @endfor
     
            </div>
            <div class="ibox-module">
                <h4>Thiết lập hàng 3 chân trang</h4>
                <div class="flex mt20">
                    <input type="color" name="row3[colorTitle]" value="{{$footer['row3']['colorTitle']}}">
                    <label class="fw500 m0 ml10" for="colorPicker">Màu chữ tiêu đề</label>
                </div>
                <div class="flex mt20">
                    <input type="color" name="row3[colorText]" value="{{$footer['row3']['colorText']}}">
                    <label class="fw500 m0 ml10" for="colorPicker">Màu chữ</label>
                </div>
                <div class="form-group mt20">
                    <label for="" class="fw500">Tiêu đề cột 1</label>
                    <input class="form-control" type="text" name="row3[column1][title]" value="{{$footer['row3']['column1']['title']}}" placeholder="Nhập tiêu đề">
                </div>
                <p class="text-shadow mt15">Bạn có thể quay lại phần <a href={{route('system.index')}}>Cấu hình chung</a> để thiết lập thêm thông tin cho cột 1, cột 4 và hàng cuối của chân trang</p>
            </div>
            <div class="ibox-module">
                <h4>Thiết lập hàng 3 - cột 2 chân trang</h4>
                <div class="form-group mt20">
                    <label for="" class="fw500">Tiêu đề</label>
                    <input class="form-control" type="text" name="row3[column2][title]" value="{{$footer['row3']['column2']['title']}}" placeholder="Nhập tiêu đề">
                </div>
                <div class="form-group mt20">
                    <label class="fw500" for="">Chọn Menu hiển thị</label>
                    <select name="row3[column2][menu]" style="width:100%" class="ml setupSelect2">
                        @foreach($menus as $menu)
                            <option {{$footer['row3']['column2']['menu'] == $menu->keyword ? 'selected' : ''}} value="{{$menu->keyword}}">{{$menu->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="ibox-module">
                <h4>Thiết lập hàng 3 - cột 3 chân trang</h4>
                <div class="form-group mt20">
                    <label for="" class="fw500">Tiêu đề</label>
                    <input class="form-control" type="text" name="row3[column3][title]" value="{{$footer['row3']['column3']['title']}}" placeholder="Nhập tiêu đề">
                </div>
                <div class="form-group mt20">
                    <label class="fw500" for="">Chọn Menu hiển thị</label>
                    <select name="row3[column3][menu]" style="width:100%" class="ml setupSelect2">
                        @foreach($menus as $menu)
                            <option {{$footer['row3']['column3']['menu'] == $menu->keyword ? 'selected' : ''}} value="{{$menu->keyword}}">{{$menu->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="ibox-module">
                <h4>Thiết lập hàng 4 chân trang</h4>
                <div class="flex mt20">
                    <input type="color" name="row4[colorText]" value="{{$footer['row4']['colorText']}}">
                    <label class="fw500 m0 ml10" for="colorPicker">Màu chữ</label>
                </div>
                <div class="flex mt20">
                    <input type="color" name="row4[colorBackground]" value="{{$footer['row4']['colorBackground']}}">
                    <label class="fw500 m0 ml10" for="colorPicker">Màu nền</label>
                </div>
            </div>
            <button class="btn btn-primary" type="submit">Lưu lại</button>
        </div>
    </form>
</div>