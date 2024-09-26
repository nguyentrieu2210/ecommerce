<div class="col-lg-12" style="margin-top: -25px">
    <div class="ibox-content">

        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th class="text-center"><input type="checkbox" class="checkAll " name="id" id=""></th>
                <th>Tên</th>
                <th>Email</th>
                <th class="text-center">Số điện thoại</th>
                <th>Địa chỉ</th>
                <th class="text-center">Nhóm khách hàng</th>
                <th class="text-center">Trạng thái</th>
                <th class="text-center">Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @foreach($customers as $customer)
            <tr>
                <td class="text-center"><input type="checkbox" name="id" class="check-id" value="{{$customer->id}}"></td>
                <td>{{$customer->name}}</td>
                <td>{{$customer->email}}</td>
                <td class="text-center">{{$customer->phone}}</td>
                <td>{{$customer->address}}</td>
                <td class="text-center">{{$customer->customer_catalogues->name}}</td>
                <td class="text-center">
                    <input type="checkbox" {{$customer->publish == 1 ? '' : 'checked'}} class="js-switch" style="display: none;">
                </td>
                <td class="text-center">
                    <a href="{{ route('customer.edit', ['id' => $customer->id]) }}" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                    <a href="{{route('customer.delete', ['id' => $customer->id])}}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        {{$customers->appends(request()->query())->links("pagination::bootstrap-4")}}
    </div>
</div>