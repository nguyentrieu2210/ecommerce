<div class="ibox float-e-margins album">
    <div class="ibox-title">
        <h5>ALBUM ẢNH</h5>
        <span class="choose-image">(Click vào <strong class="uploadAlbum strong-text">đây</strong> để
            chọn ảnh)</span>
        <div class="ibox-tools">
            <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
            </a>
        </div>
    </div>
    @php 
        $gallery = old('album') ?? ($model->album ?? []);
    @endphp
    <div class="ibox-content listAlbum {{count($gallery) ? '' : 'hidden'}}">
        <div class="sortable lightBoxGallery album-style">
            
            @if(isset($gallery) && count($gallery))
                @foreach($gallery as $key => $item)
                <span class="item-album">
                    <a href="{{$item}}" title="Album" data-gallery="">
                        <img src="{{$item}}">
                        <input type="hidden" name="album[]" value="{{$item}}">
                    </a>
                    <button data-variant="0" class="btn-primary delete-image"><i class="fa fa-trash"></i></button>
                </span>
                @endforeach
            @endif
        </div>
    </div>
    <div class="ibox-content emptyAlbum {{count($gallery) ? 'hidden' : ''}}">
        <div class="form-group">
            <span class="triggerImage empty-image hidden">click</span>
            <div class="empty-image">
                <img data-type="Images" class="image-style "
                    src="{{ asset('/backend/img/empty-image1.png') }}" alt="">
            </div>
        </div>
    </div>
</div>