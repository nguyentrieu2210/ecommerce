<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5>ĐIỀU KIỆN ÁP DỤNG</h5>
        <div class="ibox-tools">
            <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
            </a>
        </div>
    </div>
    <div class="ibox-content text-black-theme" style="">
        @php
            $conditionObject = isset($model) ? $model->detail['condition']['object'] : 'none';
            $minimumValue = ($conditionObject == 'minimumValue') ? formatNumberFromSql($model->detail['condition']['minimumValue']) : 1;
            $minimumQuantity = ($conditionObject == 'minimumQuantity') ? formatNumberFromSql($model->detail['condition']['minimumQuantity']) : 2;
        @endphp
        <div class="condition-object">
            <label class="fw510 fs14 custom-radio">
                <input type="radio" class="applyCondition fs30" {{$conditionObject == 'none' ? 'checked' : ''}} name="detail[condition][object]" value="none">
                <span class="radio-mark"></span>
                Không có
            </label><br>
            <label class="fw510 fs14 custom-radio">
                <input type="radio" class="applyCondition fs30" {{$conditionObject == 'minimumValue' ? 'checked' : ''}} name="detail[condition][object]" value="minimumValue">
                <span class="radio-mark"></span>
                Tổng giá trị sản phẩm tối thiểu trong đơn (<span class="currency">đ</span>)
            </label><br>
            <div class="form-group w50 boxMinimumValue {{$conditionObject == 'minimumValue' ? '' : 'hidden'}}">
                <input class="form-control ml40 br5 inputMoney text-right" type="text" name="detail[condition][minimumValue]" value="{{$minimumValue}}">
            </div>
            <label class="fw510 fs14 custom-radio">
                <input type="radio" class="applyCondition" name="detail[condition][object]" {{($conditionObject == 'minimumQuantity') ? 'checked' : ''}} value="minimumQuantity">
                <span class="radio-mark"></span>
                Tổng số lượng sản phẩm tối thiểu trong đơn
            </label>
            <div class="form-group w50 boxMinimumQuantity {{($conditionObject == 'minimumQuantity') ? '' : 'hidden'}}">
                <input class="form-control ml40 br5" type="number" name="detail[condition][minimumQuantity]" min="2" value="{{$minimumQuantity}}">
            </div>
        </div>
    </div>
</div>
