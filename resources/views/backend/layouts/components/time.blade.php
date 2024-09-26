<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5>THỜI GIAN</h5>
        <div class="ibox-tools">
            <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
            </a>
        </div>
    </div>
    <div class="ibox-content" style="">
        @if(isset($model))
            <input type="hidden" class="startDate" value="{{$model->start_date}}">
            <input type="hidden" class="endDate" value="{{$model->end_date}}">
        @endif
        <div class="form-group">
            <label for="">Ngày bắt đầu</label>
            <div class="form-group">
                <input type="text" class="setupStartDate form-control" name="start_date" value="{{isset($model) ? formatDateTimeFromSql($model->start_date) : ''}}">
            </div>
        </div>
        <div class="form-group">
            <label for="">Ngày kết thúc</label>
            <div class="form-group">
                <input disabled type="text" class="setupEndDate form-control" name="end_date" value="{{(isset($model) && $model->end_date !== null) ? formatDateTimeFromSql($model->end_date) : ''}}">
            </div>
        </div>
        <input type="hidden" name="never_end_date" value="0">
        <div class="checkbox-never-end-date flex">
            <input {{(!isset($model) || (isset($model) && $model->never_end_date == 1)) ? 'checked' : ''}} type="checkbox" id="neverEndDate" name="never_end_date" value="1"><label class="fw510 fs14 text-black-theme" for="neverEndDate">Không có ngày kết thúc</label>
        </div>
    </div>
</div>