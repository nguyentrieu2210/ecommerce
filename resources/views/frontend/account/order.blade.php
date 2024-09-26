@extends('frontend.layouts.layout')
@section('content')
 <div id="custummer">
    @include('frontend.account.components.breadcrumb', ['title' => 'Quản lý đơn hàng'])
    <div id="custummer-main">
        <div class="container">
            <div class="row">
                @include('frontend.account.components.sidebar')
                <div id="account-main" class="col-lg-9 col-md-12 col-sm-12">
                    <h3>Đơn hàng của tôi</h3>
                    <table class="account-order">
                        <tr>
                            <td class="colum-1">Mã đơn hàng</td>
                            <td class="colum-2">Ngày mua</td>
                            <td class="colum-3">Sản phẩm</td>
                            <td class="colum-4">Tổng tiền</td>
                            <td class="colum-5">Trạng thái đơn hàng</td>
                        </tr>
                    </table>
                    <table>
                        <!-- td -->
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> 
@endsection
