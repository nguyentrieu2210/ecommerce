<div class="col-lg-12" style="margin-top: -25px">
    <div class="ibox-content">

        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th class="text-center"><input type="checkbox" class="checkAll " name="id" id=""></th>
                <th>Tên nhóm</th>
                <th>Mô tả</th>
                <th class="text-center">Số khách hàng</th>
                <th class="text-center">Trạng thái</th>
                <th class="text-center">Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @foreach($customerCatalogues as $customerCatalogue)
            <tr>
                <td class="text-center"><input type="checkbox" name="id" class="check-id" value="{{$customerCatalogue->id}}"></td>
                <td>{{$customerCatalogue->name}}</td>
                <td>{{$customerCatalogue->description}}</td>
                <td class="text-center">{{$customerCatalogue->customers->count()}}</td>
                <td class="text-center">
                    <input type="checkbox" {{$customerCatalogue->publish == 1 ? '' : 'checked'}} class="js-switch" style="display: none;">
                </td>
                <td class="text-center">
                    <a href="{{ route('customerCatalogue.edit', ['id' => $customerCatalogue->id]) }}" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                    <a href="{{route('customerCatalogue.delete', ['id' => $customerCatalogue->id])}}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        {{$customerCatalogues->appends(request()->query())->links("pagination::bootstrap-4")}}
    </div>
</div>