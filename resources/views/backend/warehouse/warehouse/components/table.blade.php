<div class="col-lg-12" style="margin-top: -25px">
    <div class="ibox-content">

        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th class="text-center"><input type="checkbox" class="checkAll " name="id" id=""></th>
                <th>Tên chi nhánh</th>
                <th>Mã chi nhánh</th>
                <th>Người quản lý</th>
                <th>Địa chỉ</th>
                <th class="text-center">Số điện thoại</th>
                <th class="text-center">Trạng thái</th>
                <th class="text-center">Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @foreach($warehouses as $warehouse)
            <tr>
                <td class="text-center"><input type="checkbox" name="id" class="check-id" value="{{$warehouse->id}}"></td>
                <td>{{$warehouse->name}}</td>
                <td>{{$warehouse->code}}</td>
                <td>{{$warehouse->users->name}}</td>
                <td>{{$warehouse->address}}</td>
                <td class="text-center">{{$warehouse->phone}}</td>
                <td class="text-center">
                    <input type="checkbox" {{$warehouse->publish == 1 ? '' : 'checked'}} class="js-switch" style="display: none;">
                </td>
                <td class="text-center">
                    <a href="{{ route('warehouse.edit', ['id' => $warehouse->id]) }}" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                    <a href="{{route('warehouse.delete', ['id' => $warehouse->id])}}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        {{$warehouses->appends(request()->query())->links("pagination::bootstrap-4")}}
    </div>
</div>