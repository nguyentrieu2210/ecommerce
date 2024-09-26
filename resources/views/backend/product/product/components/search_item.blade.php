<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5>TIN TỨC CÓ LIÊN QUAN</h5>
        <div class="ibox-tools">
            <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
            </a>
        </div>
    </div>
    <div class="ibox-content">
        <input type="hidden" name="post_id" value="{{old('post_id')}}">
        <label class="fs15 fw550" for="">Tìm kiếm bài viết</label>
            <div class="form-group search-model">
                
                <input type="text"
                    value="{{ old('model_id') }}"
                    placeholder="Nhập ít nhất 2 kí tự để tìm kiếm..." name="model_id" class="form-control searchItem">
                    <i class="fa fa-search"></i>
                <div class="search-model-render">
                    {{--  --}}
                </div>  
            </div>
            <div class="list-search">
                <p>Danh sách bài viết đã chọn</p>
                @php 
                    $itemIds = json_decode(old('post_id')) ?? (count($models) ? $models->pluck('id') : []);
                    $itemNames = old('post_name') ?? (count($models) ? $models->pluck('name') : []);
                    $itemImages = old('post_image') ?? (count($models) ? $models->pluck('image') : []);
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
                            <input type="hidden" name="post_name[]" value="{{$itemNames[$key]}}">
                            <input type="hidden" name="post_image[]" value="{{$itemImages[$key]}}">
                            <i class="fa fa-times icon-removed"></i>
                        </div>
                    @endforeach
                @endif
            </div>
    </div>
</div>