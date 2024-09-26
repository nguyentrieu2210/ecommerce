@extends('backend.layouts.layout')
@section('title', __("breadcrump.".$config['module'].".".$config['method'].".title"))
@section('content')
    @include('backend.layouts.components.breadcrump', ['config' => $config])

    <div class="row animated fadeInRight">
        <div class="col-lg-12">
            <form method="GET" action="{{route('user.destroy', $user->id)}}">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Thông tin chung</h5>
                </div>
                <div class="ibox-content">
                    <p class="fs14">
                        - Bạn đang muốn xóa thành viên có email là: <span class="text-danger">{{$user->email}}</span>
                    </p>
                    <p class="fs14">- Lưu ý: Không thể khôi phục thành viên sau khi xóa. Hãy chắc chắn rằng bạn muốn thực
                        hiện chức năng này</p>
                    <div class="row">
                        <div class="col-lg-6 col-md-6">

                            <div class="form-group"><label>Email <span class="text-danger">(*)</span></label> <input
                                    type="text" readonly value="{{ old('email') ?? (isset($user) ? $user->email : '') }}"
                                    placeholder="Nhập Email..." name="email" class="form-control">
                            </div>

                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group"><label>Họ tên <span class="text-danger">(*)</span></label>
                                <input readonly type="text" placeholder="Nhập họ tên"
                                    value="{{ old('name') ?? (isset($user) ? $user->name : '') }}" name="name"
                                    class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ibox-button">
                <button class="btn btn-primary mr10" type="submit">Xóa</button>
                <a href="{{route('user.index')}}" class="btn btn-white" >Quay lại</a>
            </div>
            </form>
        </div>
    </div>
@endsection
