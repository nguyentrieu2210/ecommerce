<div class="col-lg-12" style="margin-top: -25px">
    <div class="ibox-content">

        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th class="text-center"><input type="checkbox" class="checkAll " name="id" id=""></th>
                <th>Tên nguồn khách</th>
                <th class="text-center">Ảnh đại diện</th>
                <th>Mô tả</th>
                <th class="text-center">Số khách hàng</th>
                <th class="text-center">Trạng thái</th>
                <th class="text-center">Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @foreach($sources as $source)
            <tr>
                <td class="text-center"><input type="checkbox" name="id" class="check-id" value="{{$source->id}}"></td>
                <td>{{$source->name}}</td>
                <td class="text-center"><img class="style-avatar" src="{{$source->image}}" alt=""></td>
                <td>{{$source->description}}</td>
                <td class="text-center">{{$source->customers->count()}}</td>
                <td class="text-center">
                    <input type="checkbox" {{$source->publish == 1 ? '' : 'checked'}} class="js-switch" style="display: none;">
                </td>
                <td class="text-center">
                    <a href="{{ route('source.edit', ['id' => $source->id]) }}" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                    <a href="{{route('source.delete', ['id' => $source->id])}}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        {{$sources->appends(request()->query())->links("pagination::bootstrap-4")}}
    </div>
</div>