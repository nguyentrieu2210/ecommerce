<div class="overlay-content" id="formGeneralSetting">
    <form method="post" action="{{route('system.theme.editor')}}">
        @csrf
        <input type="hidden" name="keyword" value="general_setting">
        <div class="module-detail-title flex">
            <i class="fa fa-chevron-left btnCloseForm"></i>
            <h3>Thiết lập chung</h3>
        </div>
        <div class="module-detail-content">
            <div class="ibox-module">
                <div class="flex mt20">
                    <input type="color" name="colorTheme" value="{{$generalSetting['colorTheme']}}">
                    <label class="fw500 m0 ml10">Màu Theme</label>
                </div>
                <div class="flex mt20">
                    <input type="color" name="colorBackground" value="{{$generalSetting['colorBackground']}}">
                    <label class="fw500 m0 ml10">Màu nền</label>
                </div>
            </div>
            <button class="btn btn-primary" type="submit">Lưu lại</button>
        </div>
    </form>
</div>