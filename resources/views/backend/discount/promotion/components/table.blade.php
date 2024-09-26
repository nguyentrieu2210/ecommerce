<div class="col-lg-12" style="margin-top: -25px">
    <div class="ibox-content">

        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th class="text-center"><input type="checkbox" class="checkAll " name="id" id=""></th>
                <th>Khuyến mại</th>
                <th>Loại khuyến mại</th>
                <th class="text-center">Ngày bắt đầu</th>
                <th class="text-center">Ngày kết thúc</th>
                <th class="text-center">Trạng thái</th>
                <th class="text-center">Thao tác</th>
            </tr>
            </thead>
            <tbody class="fw510 fs14 text-black-theme">
            @foreach($promotions as $promotion)
            @php
                $endDate = $promotion->end_date !== null ? formatDateTimeFromSql($promotion->end_date) : '---';
                $isExpired = false;
                if($promotion->end_date !== null && compareCurrentTime($promotion->end_date) == 'earlier') {
                    $isExpired = true;
                }
                $isNotStart = false;
                if(compareCurrentTime($promotion->start_date) == 'later') {
                    $isNotStart = true;
                }
            @endphp
            <tr>
                <td class="text-center"><input type="checkbox" name="id" class="check-id" value="{{$promotion->id}}"></td>
                <td><span class="text-success">{{$promotion->name}}</span>
                    @if($isExpired)
                        <span class="text-danger">-Hết hạn</span>
                    @endif
                    @if($isNotStart)
                        <span class="text-theme">-Sắp diễn ra</span>
                    @endif

                </td>
                <td>{{__('config.promotionMethod')[$promotion->method]}}</td>
                <td class="text-center">{{formatDateTimeFromSql($promotion->start_date)}}</td>
                <td class="text-center">{{$endDate}}</td>
                <td class="text-center">
                    <input type="checkbox" {{$promotion->publish == 1 ? '' : 'checked'}} class="js-switch" style="display: none;">
                </td>
                <td class="text-center">
                    <a href="{{ route('promotion.edit', ['id' => $promotion->id]) }}" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                    <a href="{{route('promotion.delete', ['id' => $promotion->id])}}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        {{$promotions->appends(request()->query())->links("pagination::bootstrap-4")}}
    </div>
</div>