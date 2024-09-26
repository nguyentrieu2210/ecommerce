<div class="box-confirm-payment">
    <div class="row pd-l-r-30">
        <div class="col-lg-6">
            <div class="form-group">
                <label>Chọn hình thức thanh toán</label> 
                <select style="width: 100%;" name="payment[payment_method]" class="ml setupSelect2">
                    {{-- <option value="none">Chọn hình thức thanh toán</option> --}}
                    @foreach(__('filter.paymentMethod') as $key => $val)
                        <option value="{{$key}}">{{$val}}</option>
                    @endforeach
                </select>
            </div>
        </div> 
        <div class="col-lg-6 ">
            <div class="form-group">
                <label>Số tiền thanh toán</label> 
                <input disabled type="text" name="payment[amount]" value="{{formatNumberFromSql($finalTotalPrice).'đ'}}" placeholder="" class="form-control">
            </div>
        </div>
    </div>
    <div class="row pd-l-r-30">
        <div class="col-lg-6">
            <div class="form-group">
                <label>Ngày ghi nhận</label> 
                <div class="form-group">
                    <input type="text" class="setupDatetimePickerNow form-control" name="payment[recorded_at]" value="">
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label>Tham chiếu</label> 
                <input type="text" name="payment[reference_code]" value="" placeholder="Nhập mã tham chiếu" class="form-control">
            </div>
        </div>
    </div>
</div>