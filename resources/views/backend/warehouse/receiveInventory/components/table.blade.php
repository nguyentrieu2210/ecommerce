<div class="col-lg-12" style="margin-top: -25px">
    <div class="ibox-content">

        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                {{-- <th class="text-center"><input type="checkbox" class="checkAll " name="id" id=""></th> --}}
                <th>Mã đơn nhập</th>
                <th class="text-center">Ngày tạo</th>
                <th>Chi nhánh nhập</th>
                <th>Trạng thái</th>
                <th>Trạng thái nhập</th>
                <th>Nhà cung cấp</th>
                <th>Nhân viên tạo</th>
                <th class="text-center">Số lượng đặt</th>
                <th class="text-right">Giá trị đơn</th>
            </tr>
            </thead>
            <tbody class="text-black-theme">
            @foreach($receiveInventories as $receiveInventory)
            <tr>
                {{-- <td class="text-center"><input type="checkbox" name="id" class="check-id" value="{{$receiveInventory->id}}"></td> --}}
                <td><a class="text-primary fw510" href="{{ route('receiveInventory.edit', ['id' => $receiveInventory->id]) }}">{{$receiveInventory->code}}</a></td>
                <td class="text-center">{{formatDateFromSql($receiveInventory->created_at)}}</td>
                <td>{{$receiveInventory->warehouses->name}}</td>
                <td><span class="{{__('config.statusPayment')[$receiveInventory->status_payment]}}">{{__('filter.statusPayment')[$receiveInventory->status_payment]}}</span></td>
                <td><span class="{{__('config.statusReceive')[$receiveInventory->status_receive_inventory]}}">{{__('filter.statusReceive')[$receiveInventory->status_receive_inventory]}}</span></td>
                <td>{{$receiveInventory->suppliers->name}}</td>
                <td >
                    @php
                        $phone = $receiveInventory->users->phone !== null ? '-'.$receiveInventory->users->phone : '';
                    @endphp
                    {{$receiveInventory->users->name}}{{$phone}}
                </td>
                <td class="text-center">{{formatNumberFromSql($receiveInventory->quantity_total)}}</td>
                <td class="text-right fw510">{{formatNumberFromSql(calculateFinalTotalCost($receiveInventory))}}đ</td>
            </tr>
            @endforeach
            </tbody>
        </table>
        {{$receiveInventories->appends(request()->query())->links("pagination::bootstrap-4")}}
    </div>
</div>