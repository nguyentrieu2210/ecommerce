@php
    $productSingleWarehouses = [];
    if($config['method'] == 'edit' && count($product->product_variants) == 0) {
        foreach ($product->warehouses as $key => $item) {
            $productSingleWarehouses[$item->pivot->warehouse_id] = $item->pivot;
        }
    }
@endphp
<div class="ibox float-e-margins  {{(isset($product) && count($product->product_variants)) ? 'hidden' : ''}}">
    <div class="ibox-title">
        <h5>KHỞI TẠO GIÁ TRỊ KHO HÀNG</h5>
        <div class="ibox-tools">
            <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
            </a>
        </div>
    </div>
    <div class="ibox-content">
        <p>Khu vực ghi nhận số lượng tồn kho ban đầu của mỗi sản phẩm và Giá vốn của sản phẩm tại các Kho hàng</p>
        <table class="table fs15">
            <thead>
                <tr>
                    <th class="fw550">Chi nhánh</th>
                    <th class="fw550">Tồn kho ban đầu</th>
                    <th class="fw550">Giá vốn <span class="underline">(đ)</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach($warehouses as $key => $val)
                <tr style="border:none !important">
                    <td>{{$val->name}}</td>
                    <td>
                            <input type="text"
                                value="{{ old('quantity')[$val->id] ?? (count($productSingleWarehouses) ? formatNumberFromSql($productSingleWarehouses[$val->id]['quantity']) : "0") }}"
                                placeholder="" name="quantity[{{$val->id}}]" class="form-control text-right inputMoney">
                        </div>
                    </td>
                    <td>
                            <input type="text"
                                value="{{ old('cost_price')[$val->id] ?? (count($productSingleWarehouses) ? formatNumberFromSql($productSingleWarehouses[$val->id]['cost_price']) : "0")}}"
                                placeholder="" name="cost_price[{{$val->id}}]" class="form-control text-right inputMoney">
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>