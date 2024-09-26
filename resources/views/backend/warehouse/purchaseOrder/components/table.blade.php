<div class="col-lg-12" style="margin-top: -25px">
    <div class="ibox-content">

        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                {{-- <th class="text-center"><input type="checkbox" class="checkAll " name="id" id=""></th> --}}
                <th>Mã đơn</th>
                <th class="text-center">Ngày tạo</th>
                <th>Chi nhánh</th>
                <th>Nhà cung cấp</th>
                <th>Trạng thái</th>
                <th>Nhân viên tạo</th>
                <th class="text-center">Số lượng đặt</th>
                <th class="text-right">Giá trị đơn</th>
            </tr>
            </thead>
            <tbody class="text-black-theme">
            @foreach($purchaseOrders as $purchaseOrder)
            <tr>
                {{-- <td class="text-center"><input type="checkbox" name="id" class="check-id" value="{{$purchaseOrder->id}}"></td> --}}
                <td><a class="text-primary fw510" href="{{ route('purchaseOrder.edit', ['id' => $purchaseOrder->id]) }}">{{$purchaseOrder->code}}</a></td>
                <td class="text-center">{{formatDateFromSql($purchaseOrder->created_at)}}</td>
                <td>{{$purchaseOrder->warehouses->name}}</td>
                <td>{{$purchaseOrder->suppliers->name}}</td>
                <td><span class="{{__('config.statusPurchaseOrder')[$purchaseOrder->status]}}">{{__('filter.purchaseOrder')[$purchaseOrder->status]}}</span></td>
                <td >
                    @php
                        $phone = $purchaseOrder->users->phone !== null ? '-'.$purchaseOrder->users->phone : '';
                    @endphp
                    {{$purchaseOrder->users->name}}{{$phone}}
                </td>
                <td class="text-center">{{formatNumberFromSql($purchaseOrder->quantity_total)}}</td>
                <td class="text-right fw510">{{formatNumberFromSql($purchaseOrder->price_total)}}đ</td>
            </tr>
            @endforeach
            </tbody>
        </table>
        {{$purchaseOrders->appends(request()->query())->links("pagination::bootstrap-4")}}
    </div>
</div>