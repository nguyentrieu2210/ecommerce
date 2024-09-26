@php
    $queryString = request()->getQueryString();
@endphp
<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Cài đặt nâng cao</h5>
            <div class="ibox-tools">
                <a class="collapse-link">
                    <i class="fa fa-chevron-down"></i>
                </a>
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
                        <div class="input-group ml"><input placeholder="Nhập từ khóa tìm kiếm" value="{{old('keyword') ?? Request::query('keyword')}}" name="keyword" type="text"
                                class="form-control"> <span class="input-group-btn"> <button type="submit"
                                    class="btn btn-primary"><i class="fa fa-filter"></i> Lọc
                                </button> </span></div>
                        <a href="{{route('permission.create')}}" class="btn btn-w-m btn-success ml"><i class="fa fa-plus"></i> Thêm
                            mới quyền</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>