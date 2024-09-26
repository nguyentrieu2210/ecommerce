<div class="overlay-content" id="formModuleMultipleProduct">
    <form method="post" action="{{route('system.theme.editor')}}">
        @csrf
        <input type="hidden" name="keyword" value="module_multiple_product">
        <div class="module-detail-title flex">
            <i class="fa fa-chevron-left btnCloseForm"></i>
            <h3>Trang chủ - Module Tất cả sản phẩm</h3>
            <input type="hidden" name="name" value="Module tất cả sản phẩm">
        </div>
        <div class="module-detail-content">
            @for($i = 1; $i <= 5; $i++)
            <div class="ibox-module">
                <h4 class="m0">Thiết lập Tab sản phẩm {{$i}}</h4>
                <span>Nếu không dùng Tab này thì để trống ô nhập tiêu đề Tab</span>
                <div class="form-group mt20">
                    <label for="" class="fw500">Tiêu đề tab {{$i}}</label>
                    <input class="form-control" type="text" name="tab{{$i}}[title]" value="{{$moduleMultipleProduct['tab'.$i]['title']}}" placeholder="Nhập tiêu đề">
                </div>
                <div class="form-group mt20">
                    <label class="fw500" for="">Chọn Widget sản phẩm</label>
                    <select name="tab{{$i}}[widget]" style="width:100%" class="ml setupSelect2">
                        <option value="none">Chọn Widget</option>
                        @foreach($widgets as $widget)
                            @if($widget->model == 'product')
                                <option {{$moduleMultipleProduct['tab'.$i]['widget'] == $widget->keyword ? 'selected' : ''}} value="{{$widget->keyword}}">{{$widget->name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            @endfor
            <button class="btn btn-primary" type="submit">Lưu lại</button>
        </div>
    </form>
</div>