@php
    $attributeCatalogueIds = old('attribute_catalogue_id')  ?? ((isset($product) && $product->attribute_catalogue !== null) ? $product->attribute_catalogue : [0]);
    $attributeIds = old('attribute_id') ?? [];
    $dataAttributes = old('data_attribute');
@endphp
@if($config['method'] == 'create')
<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5>SẢN PHẨM CÓ NHIỀU PHIÊN BẢN</h5>
        <div class="ibox-tools">
            <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
            </a>
        </div>
    </div>
    @if(isset($attributeCatalogues))
    <div class="ibox-content">
        <p>Thêm các thuộc tính giúp cho sản phẩm có nhiều lựa chọn. Ví dụ: màu sắc, kích thước, chất liệu,..</p>
        <div class="product-variant">
            <div class="product-variant-top flex mb10">
                <span class="mr25">Chọn thuộc tính</span>
                <span>Chọn giá trị của thuộc tính (Nhập 2 từ để tìm kiếm)</span>
            </div>
            <input type="hidden" class="attributecatalogues" value="{{json_encode($attributeCatalogues)}}">
            <div class="product-variant-middle">
                    <div class="product-variant-item flex" data-order="0">
                        <div class="variant-item-1 form-group mr25">
                            <select style="width:100%" name="attribute_catalogue_id[]" class="ml setupSelect2 attributeCatalogueItem">
                                <option value="0">[Chọn nhóm thuộc tính]</option>
                                @foreach ($attributeCatalogues as $key => $val)
                                    <option {{ $attributeCatalogueIds[0] == $val->id ? 'selected' : '' }}
                                        value="{{ $val->id }}">{{ $val->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="variant-item-2 form-group mr10">
                            <select {{$attributeCatalogueIds[0] == "0" ? "disabled" : ""}}  style="width:100%" multiple="multiple" name="attribute_id[0][]"
                                class="ml setupSelect2 attributeItem">
                                @if(isset($dataAttributes[0]) && count(json_decode($dataAttributes[0])))
                                        @foreach(json_decode($dataAttributes[0]) as $indexAttr => $itemAttr)
                                            <option {{ (isset($attributeIds[0]) && in_array($itemAttr->id, $attributeIds[0])) ? 'selected' : ''}} value="{{$itemAttr->id}}">{{$itemAttr->name}}</option>
                                        @endforeach
                                    @endif
                            </select>
                            <input type="hidden" name="data_attribute[0]" value="{{$dataAttributes[0] ?? ''}}">
                        </div>
                        <div class="variant-item-3">
                        </div>
                    </div>
                @if(count($attributeCatalogueIds) > 1) 
                    @foreach($attributeCatalogueIds as $key => $valId)
                    @if($key == 0) @continue @endif
                        <div class="product-variant-item flex" data-order="{{$key}}">
                            <div class="variant-item-1 form-group mr25">
                                <select style="width:100%" name="attribute_catalogue_id[]" class="ml setupSelect2 attributeCatalogueItem">
                                    <option value="0">[Chọn nhóm thuộc tính]</option>
                                    @foreach ($attributeCatalogues as $index => $val)
                                        <option {{ $valId == $val->id ? 'selected' : '' }}
                                            value="{{ $val->id }}">{{ $val->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="variant-item-2 form-group mr10">
                                <select {{$valId == "0" ? "disabled" : ""}} style="width:100%" multiple="multiple" name="attribute_id[{{$key}}][]"
                                    class="ml setupSelect2 attributeItem">
                                    @if(isset($dataAttributes[$key]) && count(json_decode($dataAttributes[$key])))
                                        @foreach(json_decode($dataAttributes[$key]) as $indexAttr => $itemAttr)
                                            <option {{ (isset($attributeIds[$key]) && in_array($itemAttr->id, $attributeIds[$key])) ? 'selected' : ''}} value="{{$itemAttr->id}}">{{$itemAttr->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <input type="hidden" name="data_attribute[{{$key}}]" value="{{$dataAttributes[$key] ?? ''}}">
                            </div>
                            <div class="variant-item-3">
                                <button style="height: 35px" class="deleteAttributeCatalogue btn btn-danger w100"><i
                                        class="fa fa-trash"></i></button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <span class="btn btn-primary variant-item-1 addAttribute">Thêm thuộc tính</span>
        </div>
    </div>
    @endif
</div>
@endif
{{-- DANH SÁCH PHIÊN BẢN --}}
<div class="ibox float-e-margins iboxVariantProduct ">
    <div class="ibox-title">
        <h5>DANH SÁCH PHIÊN BẢN</h5>

        <div class="ibox-tools">
            <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
            </a>
        </div>
    </div>
    <div class="ibox-content">
        <input type="hidden" value="{{json_encode($warehouses)}}" class="warehouses">
        <table class="table toggle-arrow-tiny">
            <thead style="background: #1BB394; color: #fff">
                <tr>
                    <td>Tên phiên bản</td>
                    <td>SKU</td>
                    <td>Giá bán lẻ</td>
                    <td>Giá nhập</td>
                    
                </tr>
            </thead>
            <tbody class="listVariantRender">
                @php
                    $countProductVariant = (old('variant') !== null) ? count(old('variant')["sku"]) : ((isset($product) && count($product->product_variants)) ? count($product->product_variants) : 0);
                    $productWarehouses = [];
                @endphp
                @if($countProductVariant > 0)
                @for($i = 0; $i < $countProductVariant; $i++)   
                @php
                    $name = (old('variant') !== null) ? old('variant')['name'][$i] : $product->product_variants[$i]['name'];
                    $sku = (old('variant') !== null) ? old('variant')['sku'][$i] : $product->product_variants[$i]['sku'];
                    $price = (old('variant') !== null) ? old('variant')['price'][$i] : formatNumberFromSql($product->product_variants[$i]['price']);
                    $cost = (old('variant') !== null) ? old('variant')['cost'][$i] : formatNumberFromSql($product->product_variants[$i]['cost']);
                    $code = (old('variant') !== null) ? old('variant')['code'][$i] : ('-' . str_replace(' ', '-', $product->product_variants[$i]['code']));
                    $barcode = (old('variant') !== null) ? old('variant')['barcode'][$i] : $product->product_variants[$i]['barcode'];
                    $weight = (old('variant') !== null) ? old('variant')['weight'][$i] : $product->product_variants[$i]['weight'];
                    $mass = (old('variant') !== null) ? old('variant')['mass'][$i] : $product->product_variants[$i]['mass'];
                    if($config['method'] == 'edit') {
                        $product_variant_id = $product->product_variants[$i]['id'];
                        foreach ($product->warehouses as $key => $item) {
                            if($product_variant_id == $item->pivot->product_variant_id) {
                                $productWarehouses[$product_variant_id][$item->pivot->warehouse_id] = $item->pivot;
                            }
                        }
                    }
                @endphp
                
                <div class="variantItem">
                    <tr class="variant-item-render">
                        <td><span class="actionAlbum" style="display:inline-block;margin-right:10px"><i class="fa fa-plus"></i></span>{{$name}}</td>
                        <input type="hidden" name="variant[name][]" value="{{$name}}">
                        <td class="infoSku">{{$sku}}</td>
                        <td class="infoPrice">{{$price}}</td>
                        <td class="infoCost">{{$cost}}</td>
                    </tr>
                    <tr>
                    <td colspan="4" class="variant-item-detail hidden">
                        <div class="configuration-variant-t flex-space-between">
                            <h5>CẬP NHẬT THÔNG TIN PHIÊN BẢN</h5>
                        </div>
                        <div class="flex-space-between">
                            <div class="form-group w46">
                                <label class="fw550 fs14" for="">SKU</label>
                                <input readonly data-target="infoSku" type="text" value="{{$sku}}"
                                    placeholder="" name="variant[sku][]" class="changeInfo form-control br20">
                                <input class="codeVariant" type="hidden" name="variant[code][]" value="{{$code}}">
                            </div>
                            <div class="form-group w46">
                                <label class="fw550 fs14" for="">Barcode</label>
                                <input type="text"
                                    value="{{$barcode}}"
                                    placeholder="Nhập tay mã vạch hoặc dùng máy quét mã vạch..." name="variant[barcode][]"
                                    class="form-control br20">
                            </div>
                        </div>
                        <div class="flex-space-between">
                            <div class="form-group w32">
                                <label class="fw550 fs14" for="">Giá bán lẻ <span class="underline">(đ)</span></label>
                                <input data-target="infoPrice" type="text" value="{{$price}}" placeholder="" name="variant[price][]"
                                    class="changeInfo inputMoney text-right form-control br20">
                            </div>
                            <div class="form-group w32">
                                <label class="fw550 fs14" for="">Giá nhập <span class="underline">(đ)</span></label>
                                <input data-target="infoCost" type="text" value="{{$cost}}" placeholder="" name="variant[cost][]"
                                    class="changeInfo inputMoney text-right form-control br20">
                            </div>
                            <div class="w32 variant-weight">
                                <label class="fw550 fs14" for="">Khối lượng</label>
                                <div class="flex">
                                    <input style="border-radius: 20px 0 0 20px" type="text"
                                        value="{{$weight}}" placeholder="" name="variant[weight][]"
                                        class="form-control mr10 text-right inputMoney">
                                    <select style="width:30%" name="variant[mass][]" class="ml setupSelect2 massVariant">
                                        <option {{$mass == "0" ? 'selected' : ''}} value="0">g</option>
                                        <option {{$mass == "1" ? 'selected' : ''}}  value="1">kg</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="config-warehouse-variant">
                            <div class="config-warehouse-variant-t flex-space-between fw550 fs14">
                                <span class="w15"></span>
                                <span class="w36">Tồn ban đầu</span>
                                <span class="w36 mr10">Giá vốn <span class="underline">(đ)</span></span>
                            </div>
                            @foreach($warehouses as $indexWarehouse => $itemWarehouse)
                                <div class="config-warehouse-variant-item flex-space-between">
                                    <span class=" fw550 fs14 w15">{{$itemWarehouse->name}}</span>
                                    <div class="form-group w36">
                                        <input type="text" value="{{old('variant')['quantity'][$i][$itemWarehouse->id] ?? (count($productWarehouses) ? formatNumberFromSql($productWarehouses[$product_variant_id][$itemWarehouse->id]['quantity']) : 0)}}" placeholder=""
                                            name="variant[quantity][{{$i}}][{{$itemWarehouse->id}}]" class="inputMoney form-control br5 text-right ">
                                    </div>
                                    <div class="form-group w36 mr10">
                                        <input type="text" value="{{old('variant')['cost_price'][$i][$itemWarehouse->id] ?? (count($productWarehouses) ? formatNumberFromSql($productWarehouses[$product_variant_id][$itemWarehouse->id]['cost_price']) : 0)}}" placeholder=""
                                            name="variant[cost_price][{{$i}}][{{$itemWarehouse->id}}]" class="inputMoney form-control br5 text-right">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </td>
                    </tr>
                </div>
                @endfor
                @endif
            </tbody>
        </table>

    </div>
</div>
{{-- TẠO ALBUM ẢNH CHO SẢN PHẨM CÓ NHIỀU PHIÊN BẢN --}}
<div class="ibox float-e-margins iboxVariantAlbum ">
    <div class="ibox-title">
        <h5>TẠO ALBUM ẢNH CHO PHIÊN BẢN</h5>

        <div class="ibox-tools">
            <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
            </a>
        </div>
    </div>
    <div class="ibox-content">
        <p>Chọn nhóm thuộc tính (dựa trên các nhóm thuộc tính và các thuộc tính đã được chọn từ bước trên) để tạo ra
            danh sách Album ảnh tương ứng</p>
        <div class="form-group">
            <select name="album_variant[attribute_catalogue_id]" style="width:100%" class="ml setupSelect2 attributeCatalogueAlbum">
                <option value="0">[Chọn nhóm thuộc tính]</option>
                @if(count($attributeCatalogueIds))
                    @foreach($attributeCatalogues as $key => $item)
                        @if(in_array($item->id, $attributeCatalogueIds))
                        @php
                            $attributeCatalogueIdSelected = old('album_variant') !== null ? old('album_variant')['attribute_catalogue_id'] : ((isset($product) && count($product->albums)) ? $product->albums[0]->attribute_catalogue_id : 0);
                        @endphp
                        <option {{$item->id == $attributeCatalogueIdSelected ? 'selected' : ''}} value="{{$item->id}}">{{$item->name}}</option>
                        @endif
                    @endforeach
                @endif
            </select>
        </div>
        <table class="table album-variant toggle-arrow-tiny">
            <thead style="background: #1BB394; color: #fff">
                <tr>
                    <td>Ảnh đại diện</td>
                    <td>Tên thuộc tính</td>
                </tr>
            </thead>
            @php
                $countAlbumVariant = isset(old('album_variant')['attribute_id']) ? count(old('album_variant')['attribute_id']) : (isset($product) && count($product->albums) ? count($product->albums) : 0);
            @endphp
            <tbody>
                @if($countAlbumVariant)
                    @for($i = 0; $i < $countAlbumVariant; $i++)
                    <div class="variantItemAlbum album" data-attributeId="{{old('album_variant')['attribute_id'][$i] ?? $product->albums[$i]['attribute_id']}}">
                        <tr class="variantAlbumInfor">
                            <td>
                                <span class="actionAlbum"><i class="fa fa-plus"></i></span>
                                <img data-type="Images" class="image-variant upload-image" src="{{old('album_variant')['image'][$i] ?? ($product->albums[$i]['image'] ?? '/backend/img/empty-image1.png')}}"
                                    alt="">
                                    <input type="hidden" name="album_variant[image][]"
                                        value="{{old('album_variant')['image'][$i] ?? (isset($product) ? $product->albums[$i]['image'] : "")}}">
                            </td>
                            <td>{{old('album_variant')['name'][$i] ?? (isset($product) ? $product->albums[$i]['attribute_name'] : "") }}</td>
                            <input type="hidden" name="album_variant[name][]" value="{{old('album_variant')['name'][$i] ?? $product->albums[$i]['attribute_name']}}">
                        </tr>
                        @php
                            $attributeId = old('album_variant')['attribute_id'][$i] ?? $product->albums[$i]['attribute_id'];
                            $albumVariantItems = (isset(old('album_variant')['album']) && isset(old('album_variant')['album'][$attributeId])) ? old('album_variant')['album'][$attributeId] : ((isset($product) && $product->albums[$i]['album'] !== null) ? $product->albums[$i]['album'] : []);
                        @endphp
                        <tr class="">
                            <td class="variantAlbumDetail hidden" colspan="3">
                                <input type="hidden" name="album_variant[attribute_id][]" value="{{$attributeId}}">
                                <div class="listAlbum {{count($albumVariantItems) ? '' : 'hidden'}}">
                                    <div class="sortable lightBoxGallery album-style">
                                        @if(count($albumVariantItems))
                                            @foreach($albumVariantItems as $indexAlbum => $itemAlbum)
                                            <span class="item-album"><a href="{{$itemAlbum}}" title="Album" data-gallery="">
                                                <img src="{{asset($itemAlbum)}}">
                                                <input type="hidden" name="album_variant[album][{{$attributeId}}][]" value="{{$itemAlbum}}">
                                            </a>
                                            <button data-variant="1" class="btn-primary delete-image"><i class="fa fa-trash"></i></button></span>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                <div class="emptyAlbum {{count($albumVariantItems) ? 'hidden' : ''}}" data-attributeId="{{$attributeId}}">
                                    <div class="form-group">
                                        <span class="triggerImage empty-image hidden">click</span>
                                        <div class="empty-image" data-variant="1">
                                            <img data-type="Images" class="image-style "
                                                src="/userfiles/image/general/empty-image.png"
                                                alt="">
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </div>
                    @endfor
                @endif
            </tbody>
        </table>
    </div>
</div>
