<div class="col-lg-12" style="margin-top: -25px">
    <div class="ibox-content">

        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th class="text-center"><input type="checkbox" class="checkAll " name="id" id=""></th>
                <th class="text-center">Ảnh đại diện</th>
                <th>Tên nhóm sản phẩm</th>
                <th>Mô tả ngắn</th>
                <th class="text-center">Trạng thái</th>
                <th class="text-center">Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @foreach($productCatalogues as $productCatalogue)
            <tr>
                <td class="text-center"><input type="checkbox" name="id" class="check-id" value="{{$productCatalogue->id}}"></td>
                <td class="text-center"><img class="avatar-style" src="{{$productCatalogue->image ?? asset('/backend/img/empty-image.png')}}" alt=""></td>
                <td>{{$productCatalogue->name}}</td>
                <td>{!! $productCatalogue->description !!}</td>
                <td class="text-center">
                    <input type="checkbox" {{$productCatalogue->publish == 1 ? '' : 'checked'}} class="js-switch" style="display: none;">
                </td>
                <td class="text-center">
                    <a href="{{ route('productCatalogue.edit', ['id' => $productCatalogue->id]) }}" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                    <a href="{{route('productCatalogue.delete', ['id' => $productCatalogue->id])}}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        {{$productCatalogues->appends(request()->query())->links("pagination::bootstrap-4")}}
    </div>
</div>