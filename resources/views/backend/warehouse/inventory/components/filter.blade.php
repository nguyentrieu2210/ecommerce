<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Cài đặt nâng cao</h5>
            <div class="ibox-tools">
                <a class="collapse-link">
                    <i class="fa fa-chevron-down"></i>
                </a>
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-wrench"></i>
                </a>
                <ul class="dropdown-menu dropdown-customer">
                    <li><a class="actionPublish activePublish" data-publish="2" href="#">Cho phép hoạt động</a>
                    </li>
                    <li><a class="actionPublish stopPublish" data-publish="1" href="#">Dừng hoạt động</a>
                    </li>
                </ul>
                <a class="close-link">
                    <i class="fa fa-times"></i>
                </a>
            </div>
        </div>
        <div class="ibox-content">
            <form method="GET" action="/{{ Request::path() }}">
                <div class="filter-advanced flex-space-between ">
                    <div class="filter-advanced-l">
                        <select name="limit" class="setupSelect2">
                            @for ($i = 20; $i <= 200; $i += 20)
                                <option {{ Request::query('limit') == $i ? 'selected' : '' }}
                                    value="{{ $i }}">{{ $i }} bản ghi</option>
                            @endfor
                        </select>
                    </div>
                    <div class="filter-advanced-r flex">
                        <div class="input-number-range input-group">
                            <span class="input-group-addon">Tồn kho: từ</span>
                            <input type="text" class=" form-control input-range inputNumber text-right" name="startQuantity" value="{{Request::query('startQuantity')}}">
                            <span class="input-group-addon">đến</span>
                            <input type="text" class=" form-control input-range inputNumber text-right" name="endQuantity" value="{{Request::query('endQuantity')}}">
                        </div>
                        <select name="warehouse" class="setupSelect2 ml">
                            @foreach ($warehouses as $key => $item)
                                <option {{ Request::query('warehouse') == $item->id ? 'selected' : '' }}
                                    value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <select name="createdDay" class="setupSelect2 ml">
                            @foreach (__('filter.createdAt') as $key => $val)
                                <option {{ Request::query('createdDay') == $key ? 'selected' : '' }}
                                    value="{{ $key }}">{{ $val }}</option>
                            @endforeach
                        </select>
                        <select name="inventoryStatus" class="setupSelect2 ml">
                            @foreach (__('filter.inventoryStatus') as $key => $val)
                                <option {{ Request::query('inventoryStatus') == $key ? 'selected' : '' }}
                                    value="{{ $key }}">{{ $val }}</option>
                            @endforeach
                        </select>
                        <div class="input-group ml"><input placeholder="Nhập từ khóa tìm kiếm" value="{{old('keyword') ?? Request::query('keyword')}}" name="keyword" type="text"
                                class="form-control"> <span class="input-group-btn"> <button type="submit"
                                    class="btn btn-primary"><i class="fa fa-filter"></i> Lọc
                                </button> </span></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>