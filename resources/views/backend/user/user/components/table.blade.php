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
                <th class="text-center">Nhóm thành viên</th>
                <th class="text-center">Trạng thái</th>
                <th class="text-center">Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
            <tr>
                <td class="text-center"><input type="checkbox" name="id" class="check-id" value="{{$user->id}}"></td>
                <td>{{$user->name}}</td>
                <td>{{$user->email}}</td>
                <td class="text-center">{{$user->phone}}</td>
                <td>{{$user->address}}</td>
                <td class="text-center">{{$user->user_catalogues->name}}</td>
                <td class="text-center">
                    <input type="checkbox" {{$user->publish == 1 ? '' : 'checked'}} class="js-switch" style="display: none;">
                </td>
                <td class="text-center">
                    <a href="{{ route('user.edit', ['id' => $user->id]) }}" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                    <a href="{{route('user.delete', ['id' => $user->id])}}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        {{$users->appends(request()->query())->links("pagination::bootstrap-4")}}
    </div>
</div>