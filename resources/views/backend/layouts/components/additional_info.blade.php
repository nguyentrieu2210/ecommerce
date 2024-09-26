<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5 class="ibox-title-customize">THÔNG TIN BỔ SUNG</h5>
        <div class="ibox-tools">
            <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
            </a>
        </div>
    </div>
    <div class="ibox-content">
        @if((isset($config['type']) && $config['type'] == 'createByPurchaseOrder') || (isset($model) && $model->purchase_order_id !== null))
            <div class="box-infor-purchase-order">
                <span class="text-black-theme fw510 fs14 mb5 inline-block">Mã đơn đặt hàng nhập</span><br>
                <span class="fw510"><a class="text-primary confirmLink" href="/admin/purchaseOrder/{{$model->purchase_order_id ?? $model->id}}/edit">{{$model->code}}</a></span>
            </div>
        @endif
        <div class="form-group">
            <label for="">Nhân viên phụ trách</label>
            <select {{$statusDisabled}} style="width: 100%;" name="user_id" class="ml setupSelect2">
                @foreach($users as $item)
                    <option {{(isset($model) && $model->user_id == $item->id) ? 'selected' : ''}} value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="">Ngày nhập dự kiến</label>
            <div class="form-group">
                <input {{$statusDisabled}} type="text" class="setupDatetimePicker form-control" name="expected_day" value="{{(isset($model) && $model->expected_day !== null) ? formatDateTimeFromSql($model->expected_day) : ''}}">
            </div>
        </div>
        {{-- <div class="form-group">
            <label>Mã đơn </label> 
            <input type="text" name="code" value="{{old('code')}}" placeholder="Nhập mã đơn" class="form-control">
        </div> --}}
        <div class="form-group">
            <label>Tham chiếu</label> 
            <input type="text" name="reference_code" value="{{isset($model) ? $model->reference_code : ''}}" placeholder="Nhập mã tham chiếu" class="form-control">
        </div>
    </div>
</div>