<div class="col-lg-12" style="margin-top: -25px">
    <div class="ibox-content">

        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th class="text-center"><input type="checkbox" class="checkAll " name="id" id=""></th>
                <th>Mã khuyến mại</th>
                <th>Loại khuyến mại</th>
                <th class="text-center">Ngày bắt đầu</th>
                <th class="text-center">Ngày kết thúc</th>
                <th class="text-center">Trạng thái</th>
                <th class="text-center">Thao tác</th>
            </tr>
            </thead>
            <tbody class="fw510 fs14 text-black-theme">
            @foreach($coupons as $coupon)
            @php
                $endDate = $coupon->end_date !== null ? formatDateTimeFromSql($coupon->end_date) : '---';
                $isExpired = false;
                if($coupon->end_date !== null && compareCurrentTime($coupon->end_date) == 'earlier') {
                    $isExpired = true;
                }
                $isNotStart = false;
                if(compareCurrentTime($coupon->start_date) == 'later') {
                    $isNotStart = true;
                }
            @endphp
            <tr>
                <td class="text-center"><input type="checkbox" name="id" class="check-id" value="{{$coupon->id}}"></td>
                <td><span class="text-success">{{$coupon->code}}</span>
                    @if($isExpired)
                        <span class="text-danger">-Hết hạn</span>
                    @endif
                    @if($isNotStart)
                        <span class="text-theme">-Sắp diễn ra</span>
                    @endif

                </td>
                <td>{{__('config.couponMethod')[$coupon->method]}}</td>
                <td class="text-center">{{formatDateTimeFromSql($coupon->start_date)}}</td>
                <td class="text-center">{{$endDate}}</td>
                <td class="text-center">
                    <input type="checkbox" {{$coupon->publish == 1 ? '' : 'checked'}} class="js-switch" style="display: none;">
                </td>
                <td class="text-center">
                    <a href="{{ route('coupon.edit', ['id' => $coupon->id]) }}" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                    <a href="{{route('coupon.delete', ['id' => $coupon->id])}}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        {{$coupons->appends(request()->query())->links("pagination::bootstrap-4")}}
    </div>
</div>