<div class="col-lg-12" style="margin-top: -25px">
    <div class="ibox-content">

        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th class="text-center"><input type="checkbox" class="checkAll " name="id" id=""></th>
                <th class="text-center">Ảnh đại diện</th>
                <th>Tên nhóm bài viết</th>
                <th>Mô tả ngắn</th>
                <th class="text-center">Trạng thái</th>
                <th class="text-center">Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @foreach($postCatalogues as $postCatalogue)
            <tr>
                <td class="text-center"><input type="checkbox" name="id" class="check-id" value="{{$postCatalogue->id}}"></td>
                <td class="text-center"><img class="avatar-style" src="{{$postCatalogue->image ?? asset('/backend/img/empty-image.png')}}" alt=""></td>
                <td>{{$postCatalogue->name}}</td>
                <td>{!! $postCatalogue->description !!}</td>
                <td class="text-center">
                    <input type="checkbox" {{$postCatalogue->publish == 1 ? '' : 'checked'}} class="js-switch" style="display: none;">
                </td>
                <td class="text-center">
                    <a href="{{ route('postCatalogue.edit', ['id' => $postCatalogue->id]) }}" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                    <a href="{{route('postCatalogue.delete', ['id' => $postCatalogue->id])}}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        {{$postCatalogues->appends(request()->query())->links("pagination::bootstrap-4")}}
    </div>
</div>