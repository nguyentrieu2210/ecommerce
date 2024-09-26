<div class="col-lg-12" style="margin-top: -25px">
    <div class="ibox-content">

        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th class="text-center"><input type="checkbox" class="checkAll " name="id" id=""></th>
                <th>Tên nhóm</th>
                <th>Mô tả</th>
                <th class="text-center">Số thành viên</th>
                <th class="text-center">Trạng thái</th>
                <th class="text-center">Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @foreach($userCatalogues as $userCatalogue)
            <tr>
                <td class="text-center"><input type="checkbox" name="id" class="check-id" value="{{$userCatalogue->id}}"></td>
                <td>{{$userCatalogue->name}}</td>
                <td>{{$userCatalogue->description}}</td>
                <td class="text-center">{{$userCatalogue->users->count()}}</td>
                <td class="text-center">
                    <input type="checkbox" {{$userCatalogue->publish == 1 ? '' : 'checked'}} class="js-switch" style="display: none;">
                </td>
                <td class="text-center">
                    <a href="{{ route('userCatalogue.edit', ['id' => $userCatalogue->id]) }}" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                    <a href="{{route('userCatalogue.delete', ['id' => $userCatalogue->id])}}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        {{$userCatalogues->appends(request()->query())->links("pagination::bootstrap-4")}}
    </div>
</div>