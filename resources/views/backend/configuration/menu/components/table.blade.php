<div class="col-lg-12" style="margin-top: -25px">
    <div class="ibox-content">

        <table class="table table-striped table-bordered fs14">
            <thead>
            <tr>
                <th class="text-center"><input type="checkbox" class="checkAll " name="id" id=""></th>
                <th>Tên Menu</th>
                <th class="text-center">Từ khóa</th>
                <th>Liên kết</th>
                <th class="text-center">Trạng thái</th>
                <th class="text-center">Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @foreach($menus as $menu)
            <tr>
                <td class="text-center"><input type="checkbox" name="id" class="check-id" value="{{$menu->id}}"></td>
                <td>{{$menu->name}}</td>
                <td class="text-center">{{$menu->keyword}}</td>
                <td>
                    @foreach($menu->links as $key => $link) 
                        @if($link->level == 0)
                            <span class="label label-primary br5 fs12 fw500 mb5 inline-block">{{$link->name}}</span>
                        @endif
                    @endforeach
                </td>
                <td class="text-center">
                    <input type="checkbox" {{$menu->publish == 1 ? '' : 'checked'}} class="js-switch" style="display: none;">
                </td>
                <td class="text-center">
                    <a href="{{ route('menu.edit', ['id' => $menu->id]) }}" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                    <a href="{{route('menu.delete', ['id' => $menu->id])}}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        {{$menus->appends(request()->query())->links("pagination::bootstrap-4")}}
    </div>
</div>