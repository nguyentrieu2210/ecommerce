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
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-wrench"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
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
                        <select name="publish" class="setupSelect2 ml">
                            @foreach (__('filter.publish') as $key => $val)
                                <option {{ Request::query('publish') == $key ? 'selected' : '' }}
                                    value="{{ $key }}">{{ $val }}</option>
                            @endforeach
                        </select>
                        <div class="input-group ml"><input placeholder="Nhập từ khóa tìm kiếm" value="{{old('keyword') ?? Request::query('keyword')}}" name="keyword" type="text"
                                class="form-control"> <span class="input-group-btn"> <button type="submit"
                                    class="btn btn-primary"><i class="fa fa-filter"></i> Lọc
                                </button> </span></div>
                        <a href="{{route('userCatalogue.permission')}}" class="btn btn-w-m btn-warning ml"><i class="fa fa-key"></i> Phân quyền</a>    
                        <a href="{{route('userCatalogue.create')}}" class="btn btn-w-m btn-success ml"><i class="fa fa-plus"></i> Thêm
                            mới nhóm thành viên</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>