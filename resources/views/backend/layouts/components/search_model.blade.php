<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5>CẤU HÌNH NỘI DUNG</h5>
        <div class="ibox-tools">
            <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
            </a>
        </div>
    </div>
    <div class="ibox-content">
        <div class="choose-model">
            <label>Chọn Module</label><br>
            <div class="form-group">
                <select style="width: 100%;" name="model" class="ml setupSelect2 w50 selectModel">
                    @foreach($modules as $key => $item)
                        <option {{$key == (old('model') ?? (isset($widget) ? $widget->model : '')) ? 'selected' : ''}} value="{{$key}}">{{$item}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <input type="hidden" name="model_id" value="{{old('model_id') ?? (isset($widget) ? json_encode($widget->model_id) : '')}}">
            <div class="form-group search-model">
                <input type="text"
                    value="{{ old('keyword_search') }}"
                    placeholder="Nhập ít nhất 2 kí tự để tìm kiếm..." name="keyword_search" class="form-control searchItem">
                    <i class="fa fa-search"></i>
                <div class="search-model-render">
                    {{--  --}}
                </div>  
            </div>
            <div class="list-search">
                <p>Danh sách đã chọn</p>
                @php 
                    $itemIds = json_decode(old('model_id')) ?? (count($models) ? $models->pluck('id') : []);
                    $itemNames = old('model_name') ?? (count($models) ? $models->pluck('name') : []);
                    $itemImages = old('model_image') ?? (count($models) ? $models->pluck('image') : []);
                @endphp
                @if(count($itemIds) && count($itemNames) && count($itemImages))
                    @foreach($itemIds as $key => $val)
                        <div class="list-search-item flex-space-between" data-id="{{$val}}">
                            <div class="list-search-item-l flex">
                                <div class="box-image">
                                    <img src="{{$itemImages[$key]}}" alt="">
                                </div>
                                <span>{{$itemNames[$key]}}</span>
                            </div>
                            <input type="hidden" name="model_name[]" value="{{$itemNames[$key]}}">
                            <input type="hidden" name="model_image[]" value="{{$itemImages[$key]}}">
                            <i class="fa fa-times icon-removed"></i>
                        </div>
                    @endforeach
                @endif
            </div>
    </div>
</div>