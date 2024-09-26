@extends('backend.layouts.layout')
@section('title', __("breadcrump.".$config['module'].".".$config['method'].".title"))
@section('content')
    @include('backend.layouts.components.breadcrump', ['config' => $config])

    <div class="row animated fadeInRight">
        <div class="col-lg-12">
            <form method="GET" action="{{route('promotion.destroy', $promotion->id)}}">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Thông tin chung</h5>
                </div>
                <div class="ibox-content">
                    <p class="fs14">
                        - Bạn đang muốn xóa chương trình khuyến mại có tên là: <span class="text-danger">{{$promotion->name}}</span>
                    </p>
                    <p class="fs14">- Lưu ý: Không thể khôi phục dữ liệu sau khi xóa. Hãy chắc chắn rằng bạn muốn thực
                        hiện chức năng này</p>
                    <div class="row">
                        <div class="col-lg-12 col-md-12">

                            <div class="form-group"><label>Tên chương trình khuyến mại </label> <input
                                    type="text" readonly value="{{ old('name') ?? (isset($promotion) ? $promotion->name : '') }}"
                                     name="name" class="form-control">
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="ibox-button">
                <button class="btn btn-primary mr10" type="submit">Xóa</button>
                <a href="{{route('promotion.index')}}" class="btn btn-white" >Quay lại</a>
            </div>
            </form>
        </div>
    </div>
@endsection
