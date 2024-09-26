<div class="col-lg-12" style="margin-top: -25px">
    <div class="ibox-content">

        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th class="text-center"><input type="checkbox" class="checkAll " name="id" id=""></th>
                <th>Tên Slide</th>
                <th class="text-center">Từ khóa</th>
                <th class="text-center">Trạng thái</th>
                <th class="text-center">Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @foreach($slides as $slide)
            <tr>
                <td class="text-center"><input type="checkbox" name="id" class="check-id" value="{{$slide->id}}"></td>
                <td>{{$slide->name}}</td>
                <td class="text-center">{{$slide->keyword}}</td>
                <td class="text-center">
                    <input type="checkbox" {{$slide->publish == 1 ? '' : 'checked'}} class="js-switch" style="display: none;">
                </td>
                <td class="text-center">
                    <a href="{{ route('slide.edit', ['id' => $slide->id]) }}" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                    <a href="{{route('slide.delete', ['id' => $slide->id])}}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        {{$slides->appends(request()->query())->links("pagination::bootstrap-4")}}
    </div>
</div>