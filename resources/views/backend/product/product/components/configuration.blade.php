<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5>THÔNG SỐ KĨ THUẬT</h5>
        <div class="ibox-tools">
            <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
            </a>
        </div>
    </div>
    <div class="ibox-content configuration">
        <span class="btn btn-primary mb15 addConfigItem" style="width:100%"><i class="fa fa-plus"></i> Thêm thông số kĩ
            thuật</span>
        @php
            $nameConfiguration = old('name_configuration') ?? (isset($product) && $product->specifications !== null ? $product->specifications['name'] : []);
            $valueConfiguration = old('value_configuration') ?? (isset($product) && $product->specifications !== null  ? $product->specifications['value'] : []);
        @endphp
        @if(count($nameConfiguration) && count($valueConfiguration))
        @foreach($nameConfiguration as $key => $value)
            <div class="configuration-item flex">
                <div style="width: 30%" class="form-group">
                    <input type="text" value="{{ $value }}" placeholder="Nhập tên..."
                        name="name_configuration[]" class="form-control">
                </div>
                <div style="width: 60%" class="form-group">
                    <input type="text" value="{{ $valueConfiguration[$key] }}" placeholder="Nhập thông số..."
                        name="value_configuration[]" class="form-control">
                </div>
                <span style="width: 10%" class="btn btn-danger config-delete"><i class="fa fa-times"></i></span>
            </div>
        @endforeach
        @endif
    </div>
</div>
