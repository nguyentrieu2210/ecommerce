<div class="col-lg-12" style="margin-top: -25px">
    <div class="ibox-content">

        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th class="text-center"><input type="checkbox" class="checkAll " name="id" id=""></th>
                <th>Tên Widget</th>
                <th class="text-center">Từ khóa</th>
                <th class="text-center">Loại</th>
                <th class="text-center">Trạng thái</th>
                <th class="text-center">Thao tác</th>
            </tr>
            </thead>
            <tbody class="text-black-theme fw510">
            @foreach($widgets as $widget)
            <tr>
                <td class="text-center"><input type="checkbox" name="id" class="check-id" value="{{$widget->id}}"></td>
                <td>{{$widget->name}}</td>
                <td class="text-center">{{$widget->keyword}}</td>
                <td class="text-center">{{__('config.model')[$widget->model]}}</td>
                <td class="text-center">
                    <input type="checkbox" {{$widget->publish == 1 ? '' : 'checked'}} class="js-switch" style="display: none;">
                </td>
                <td class="text-center">
                    <a href="{{ route('widget.edit', ['id' => $widget->id]) }}" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                    <a href="{{route('widget.delete', ['id' => $widget->id])}}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        {{$widgets->appends(request()->query())->links("pagination::bootstrap-4")}}
    </div>
</div>