<div class="overlay-content" id="formHome">
    <form method="post" action="{{route('system.theme.editor')}}">
        @csrf
        <input type="hidden" name="keyword" value="home">
        <div class="module-detail-title flex">
            <i class="fa fa-chevron-left btnCloseForm"></i>
            <h3>Trang chủ</h3>
        </div>
        <div class="module-detail-content">
            <div class="ibox-module">
                <h4 class="m0">Chọn các module</h4>
                <span>Chúng tôi cung cấp các module ở trang chủ, bạn có thể lựa chọn và sắp xếp vị trí của chúng</span>
                @for($i = 1; $i <= 7; $i++)
                <div class="form-group mt20">
                    <label class="fw500" for="">Module {{$i}}</label>
                    <select name="module.{{$i}}[module]" style="width:100%" class="ml setupSelect2">
                        <option value="none">Không dùng</option>
                        @foreach ($modules as $key => $module)
                            <option {{$home['module_'.$i]['module'] == $key ? 'selected' : ''}} value="{{$key}}">{{$module['name']}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="" class="fw500">Khoảng cách với Module dưới (px)</label>
                    <input class="form-control" type="number" name="module.{{$i}}[marginBottom]" value="{{$home['module_'.$i]['marginBottom'] ?? 0}}">
                </div>
                @endfor
            </div>
            <button class="btn btn-primary" type="submit">Lưu lại</button>
        </div>
    </form>
</div>