<div class="col-lg-12" style="margin-top: -25px">
    <div class="ibox-content">

        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th class="text-center"><input type="checkbox" class="checkAll " name="id" id=""></th>
                <th>Mã NCC</th>
                <th>Tên NCC</th>
                <th>Email</th>
                <th class="text-center">Số điện thoại</th>
                <th class="text-center">Trạng thái</th>
                <th class="text-center">Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @foreach($suppliers as $supplier)
            <tr>
                <td class="text-center"><input type="checkbox" name="id" class="check-id" value="{{$supplier->id}}"></td>
                <td>{{$supplier->code}}</td>
                <td>{{$supplier->name}}</td>
                <td>{{$supplier->email}}</td>
                <td class="text-center">{{$supplier->phone}}</td>
                <td class="text-center">
                    <input type="checkbox" {{$supplier->publish == 1 ? '' : 'checked'}} class="js-switch" style="display: none;">
                </td>
                <td class="text-center">
                    <a href="{{ route('supplier.edit', ['id' => $supplier->id]) }}" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                    <a href="{{route('supplier.delete', ['id' => $supplier->id])}}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        {{$suppliers->appends(request()->query())->links("pagination::bootstrap-4")}}
    </div>
</div>