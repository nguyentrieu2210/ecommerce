<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5 class="ibox-title-customize">CHI NHÁNH NHẬP</h5>
        <div class="ibox-tools">
            <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
            </a>
        </div>
    </div>
    <div class="ibox-content" style="position: relative;">
        <div class="form-group">
            @php
                if($config['module'] == 'purchaseOrder') {
                    $statusWarehouse = (isset($model) && $model->status !== 'pending') ? 'disabled' : '';
                }else{
                    $statusWarehouse = $config['method'] == 'edit' ? 'disabled' : '';
                }
            @endphp
            <select {{$statusWarehouse}} style="width: 100%;" name="warehouse_id" class="ml setupSelect2">
                @foreach($warehouses as $item)
                    <option {{(isset($model) && $model->warehouse_id == $item->id) ? 'selected' : ''}} value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>