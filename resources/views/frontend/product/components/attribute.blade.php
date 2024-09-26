@if(count($product->product_variants))
<div class="box-attribute" data-albumById="{{$product->album_by_attribute_catalogue}}">
    <input type="hidden" class="newAlbums" value="{{json_encode($product->new_albums)}}">
    @foreach($product->attribute as $key => $attributeCatalogueItem)
    <div class="attribute-item {{$key == $product->album_by_attribute_catalogue ? 'hasAlbum' : ''}}"  data-id="{{$key}}">
        <p class="attribute-name">{{$product->attribute_catalogue[$key]}}</p>
        <div class="list-attribute-value flex flex-wrap">
            @foreach($attributeCatalogueItem as $attributeId => $attributeName)
                <div class="attribute-value-item flex align-center" data-id="{{$attributeId}}">
                    @if($key == $product->album_by_attribute_catalogue)
                        <div class="image-container">
                            <img src="{{$product->new_albums[$attributeId]['image']}}" alt="">
                        </div>
                    @endif
                    <span>{{$attributeName}}</span>
                </div>
            @endforeach
        </div>
    </div>
    @endforeach
</div>
@endif