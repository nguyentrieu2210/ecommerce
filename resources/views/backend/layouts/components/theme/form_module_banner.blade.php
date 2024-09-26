<div class="overlay-content" id="formModuleBanner">
    <form method="post" action="{{route('system.theme.editor')}}">
        @csrf
        <input type="hidden" name="keyword" value="module_banner">
        <div class="module-detail-title flex">
            <i class="fa fa-chevron-left btnCloseForm"></i>
            <h3>Trang chủ - Module Banner</h3>
        </div>
        <div class="module-detail-content">
            <div class="ibox-module">
                <h4>Thiết lập Banner</h4>
                <input type="hidden" name="name" value="Module Banner">
                <div class="form-group mt20">
                    <label class="fw500" for="">Chọn Slide hiển thị</label>
                    <select name="slide" style="width:100%" class="ml setupSelect2">
                        @foreach($slides as $slide)
                            <option {{$moduleBanner['slide'] == $slide->keyword ? 'selected' : ''}}  value="{{$slide->keyword}}">{{$slide->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button class="btn btn-primary" type="submit">Lưu lại</button>
        </div>
    </form>
</div>