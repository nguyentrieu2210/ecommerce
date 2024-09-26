<div class="col-lg-12" style="margin-top: -25px">
    <div class="ibox-content">

        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th class="text-center"><input type="checkbox" class="checkAll " name="id" id=""></th>
                <th style="width: 30%">Tên sản phẩm</th>
                <th>Nhóm sản phẩm (chính)</th>
                <th>Nhóm sản phẩm (phụ)</th>
                <th class="text-center">Số phiên bản</th>
                <th class="text-center">Trạng thái</th>
                <th class="text-center">Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @foreach($products as $product)
            <tr>
                <td class="text-center"><input type="checkbox" name="id" class="check-id" value="{{$product->id}}"></td>
                <td class="flex product-info item-center">
                    <span>
                        <img class="avatar-style mr10" src="{{asset($product->image)}}" alt="">
                    </span>
                    <span class="text-link fs14">
                        {{$product->name}}
                    </span>
                </td>
                @php 
                    $product_catalogues = $product->product_catalogues;
                    $product_catalogue = null;
                    for($i = 0; $i < count($product_catalogues); $i++) {
                        if($product_catalogues[$i]->id == $product->product_catalogue_id) {
                            $product_catalogue = $product_catalogues[$i];
                            // $product_catalogues->forget($i);
                        }
                    }
                @endphp
                <td>
                    <span class="style-text">{{$product_catalogue->name}}</span>
                </td>
                <td>
                    @if(count($product_catalogues))
                        @for($i = 0; $i < count($product_catalogues); $i++)
                            @if($product_catalogues[$i]->id == $product->product_catalogue_id)
                                @continue
                            @endif
                            <span class="style-text">{{$product_catalogues[$i]->name}}</span>
                        @endfor
                    @endif
                </td>
                <td class="text-center">{{count($product->product_variants) == 0 ? 1 : count($product->product_variants)}}</td>
                <td class="text-center">
                    <input type="checkbox" {{$product->publish == 1 ? '' : 'checked'}} class="js-switch" style="display: none;">
                </td>
                <td class="text-center">
                    <a href="{{ route('product.edit', ['id' => $product->id]) }}" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                    <a href="{{route('product.delete', ['id' => $product->id])}}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        {{$products->appends(request()->query())->links("pagination::bootstrap-4")}}
    </div>
</div>
