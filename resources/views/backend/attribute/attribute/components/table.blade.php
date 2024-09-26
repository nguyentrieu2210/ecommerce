<div class="col-lg-12" style="margin-top: -25px">
    <div class="ibox-content">

        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th class="text-center"><input type="checkbox" class="checkAll " name="id" id=""></th>
                <th>Tên thuộc tính</th>
                <th>Nhóm thuộc tính (chính)</th>
                <th>Nhóm thuộc tính (phụ)</th>
                <th>Mô tả</th>
                <th class="text-center">Trạng thái</th>
                <th class="text-center">Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @foreach($attributes as $attribute)
            <tr>
                <td class="text-center"><input type="checkbox" name="id" class="check-id" value="{{$attribute->id}}"></td>
                <td>{{$attribute->name}}</td>
                @php 
                    $attribute_catalogues = $attribute->attribute_catalogues;
                    $attribute_catalogue = null;
                    for($i = 0; $i < count($attribute_catalogues); $i++) {
                        if($attribute_catalogues[$i]->id == $attribute->attribute_catalogue_id) {
                            $attribute_catalogue = $attribute_catalogues[$i];
                        }
                    }
                @endphp
                <td>
                    <span class="style-text">{{$attribute_catalogue->name}}</span>
                </td>
                <td>
                    @if(count($attribute_catalogues))
                        @for($i = 0; $i < count($attribute_catalogues); $i++)
                            @if($attribute_catalogues[$i]->id == $attribute->attribute_catalogue_id)
                                @continue
                            @endif
                            <span class="style-text">{{$attribute_catalogues[$i]->name}}</span>
                        @endfor
                    @endif
                </td>
                <td>{{$attribute->description}}</td>
                <td class="text-center">
                    <input type="checkbox" {{$attribute->publish == 1 ? '' : 'checked'}} class="js-switch" style="display: none;">
                </td>
                <td class="text-center">
                    <a href="{{ route('attribute.edit', ['id' => $attribute->id]) }}" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                    <a href="{{route('attribute.delete', ['id' => $attribute->id])}}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        {{$attributes->appends(request()->query())->links("pagination::bootstrap-4")}}
    </div>
</div>