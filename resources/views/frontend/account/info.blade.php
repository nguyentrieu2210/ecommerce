@extends('frontend.layouts.layout')
@section('content')
 <div id="custummer">
    @include('frontend.account.components.breadcrumb', ['title' => 'Thông tin tài khoản'])
    <div id="custummer-main">
        <div class="container">
            <div class="row">
                @include('frontend.account.components.sidebar')
                <div id="account-main" class="col-lg-9 col-md-8 col-sm-12">
                    <h3>Cập nhật thông tin cá nhân</h3>
                    @php
                        $account = Auth::guard('customer')->user();
                    @endphp
                    <form class="updateInfoAccount customize-alert-error" method="POST" action="{{route('update.info', $account->id)}}">
                        @csrf
                        <table class = "account-infor">
                            <tr>
                                <td><label for="name">Họ tên <span class="text-danger">*</span></label></td>
                                <td><input type="text" name="name" id="name" class="form-control inputValidate" value="{{$account->name}}"></td>
                            </tr>
                            <tr>
                                <td><label for="email">Email <span class="text-danger">*</span></label></td>
                                <td><input type="text" name="email" id="email" class="form-control inputValidate" value="{{$account->email}}"></td>
                            </tr>
                            <tr>
                                <td><label for="">Tỉnh / Thành phố</label></td>
                                <td>
                                    <div class="form-group mb8">
                                    <select style="width: 100%" name="province_id" class="Province ml setupSelect2 changeLocation" data-target="District">
                                        <option value="0">Chọn tỉnh / thành phố</option>
                                        @foreach ($provinces as $key => $val)
                                        <option {{ $account->province_id == $val->code ? 'selected' : '' }}
                                            value="{{ $val->code }}">{{ $val->name }}</option>
                                        @endforeach
                                    </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="">Quận / Huyện</label></td>
                                <td>
                                    <div class="form-group mb8">
                                        <select style="width: 100%" name="district_id" class="District ml setupSelect2 changeLocation" data-target="Ward">
                                            <option value="0">Chọn quận / huyện</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="">Phường / Xã</label></td>
                                <td>
                                    <div class="form-group mb8">
                                        <select style="width: 100%" name="ward_id" class="Ward ml setupSelect2">
                                            <option value="0">Chọn phường / xã</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="address">Địa chỉ nhà</label></td>
                                <td><input type="text" name="address" id="address" class="form-control" value="{{$account->address}}"></td>       
                            </tr>
                            <tr>
                                <td><label>Ngày sinh</label></td>
                                <td>
                                    <div class="form-group mb8">
                                        <div class="form-group">
                                            <input type="text" class="form-control setupDatePicker" name="birthday" value="{{$account->birthday !== null ? formatDateFromSql(formatStringToCarbon($account->birthday)) : ''}}">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="phone">Điện thoại di động <span class="text-danger">*</span></label></td>
                                <td><input type="text" name="phone" id="phone" class="form-control inputValidate" value="{{$account->phone}}"></td>       
                            </tr>
                            <tr>
                                <td></td>
                                <td><button type="submit updateInfoCustomer">THAY ĐỔI</button></td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 
<input type="hidden" class="oldDistrictId" value="{{$account->district_id ?? ''}}">
<input type="hidden" class="oldWardId" value="{{$account->ward_id ?? ''}}">
@endsection
