<div class="col-lg-12" style="margin-top: -25px">
    <div class="ibox-content">

        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th class="text-center"><input type="checkbox" class="checkAll " name="id" id=""></th>
                <th>Tên quyền</th>
                <th>Canonical</th>
                <th class="text-center">Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @foreach($permissions as $permission)
            <tr>
                <td class="text-center"><input type="checkbox" name="id" class="check-id" value="{{$permission->id}}"></td>
                <td>{{$permission->name}}</td>
                <td>{{$permission->canonical}}</td>
                <td class="text-center">
                    <a href="{{ route('permission.edit', ['id' => $permission->id]) }}" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                    <a href="{{route('permission.delete', ['id' => $permission->id])}}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        {{$permissions->appends(request()->query())->links("pagination::bootstrap-4")}}
    </div>
</div>