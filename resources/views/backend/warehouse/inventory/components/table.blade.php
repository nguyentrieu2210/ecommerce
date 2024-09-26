<div class="col-lg-12" style="margin-top: -25px">
    <div class="ibox-content">

        <table class="table table-striped table-bordered table-inventory">
            <thead>
            <tr>
                <th class="text-center"><input type="checkbox" class="checkAll " name="id" id=""></th>
                <th class="text-center">Ảnh</th>
                <th>Sản phẩm</th>
                <th>SKU</th>
                <th class="text-center">Tồn kho</th>
                <th class="text-center">Có thể bán</th>
                <th class="text-center">Đang giao dịch</th>
                <th class="text-center">Đang về kho</th>
                <th class="text-center">Giá vốn</th>
                <th class="text-center">Giá bán</th>
            </tr>
            </thead>
            <tbody>
            @php
                $products = $warehouse->products;
            @endphp
            @if(count($products))
            @foreach($products as $key => $item)
            <tr>
                @if(count($item->product_variants))
                    
                    @foreach($item->product_variants as $variant)
                        @php
                            $imageVariant = '/backend/img/empty-image.png';
                            foreach ($item->albums as $album) {
                                if(in_array($album->attribute_id, explode(" ", $variant->code))) {
                                    $imageVariant = $album->image;
                                }
                            }
                        @endphp
                        @if($variant->id == $item->pivot->product_variant_id)
                        
                            <td class="text-center"><input type="checkbox" name="id" class="check-id" value="{{$variant->id}}"></td>
                            <td class="style-avatar-inventory text-center"> <img src="{{$imageVariant}}" alt=""></td>
                            <td class="inventory-name"><a href="/admin/product/{{$item->id}}/edit">{{$item->name}}</a><br><span>{{$variant->name}}</span></td>
                            <td>{{$variant->sku}}</td>
                        @endif
                    @endforeach
                @else
                    <td class="text-center"><input type="checkbox" name="id" class="check-id" value="{{$item->id}}"></td>
                    <td class="style-avatar-inventory text-center"> <img src="{{$item->image ?? '/backend/img/empty-image.png'}}" alt=""></td>
                    <td class="inventory-name"><a href="/admin/product/{{$item->id}}/edit">{{$item->name}}</a></td>
                    <td>{{$item->code}}</td>
                @endif
                <td class="text-center">{{formatNumberFromSql($item->pivot->quantity)}}</td>
                <td class="text-center">{{formatNumberFromSql($item->pivot->quantity - $item->pivot->stock)}}</td>
                <td class="text-center">{{formatNumberFromSql($item->pivot->stock)}}</td>
                <td class="text-center">{{formatNumberFromSql($item->pivot->incoming)}}</td>
                <td class="text-center">{{formatNumberFromSql($item->pivot->cost_price)}} <span class="underline">đ</span></td>
                <td class="text-center">{{formatNumberFromSql($item->price)}} <span class="underline">đ</span></td>
            </tr>
            @endforeach
            @endif
            </tbody>
        </table>
        {{-- {{$warehouse->products->appends(request()->query())->links("pagination::bootstrap-4")}} --}}
    </div>
</div>