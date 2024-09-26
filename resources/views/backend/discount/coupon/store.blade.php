@extends('backend.layouts.layout')
@section('title', __("breadcrump.".$config['module'].".".$config['method'].".title"))
@section('content')
    @include('backend.layouts.components.breadcrump', ['config' => $config])

    <div class="wrapper wrapper-content animated fadeInRight">
        @include('backend.layouts.components.errors')
        @php
            $route = $config['method'] == 'create' ? route('coupon.store') : route('coupon.update', $coupon->id);   
        @endphp
        <form method="post" id="myForm" action="{{$route}}">
            @csrf
            <div class="row">
                <div class="col-lg-8">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>MÃ KHUYẾN MẠI</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content" style="">
                            @if(isset($coupon))
                                <input type="hidden" class="oldCode" value="{{$coupon->code}}">
                            @endif
                            <div class="form-group"><label>Mã khuyến mại <span class="text-danger">(*)</span></label>
                                <input type="text" placeholder="VD: Coupon10%" value="{{old('code') ?? (isset($coupon) ? $coupon->code : '')}}" name="code" class="form-control couponCode">
                            </div>
                        </div>
                    </div>
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>KIỂU GIẢM GIÁ</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content fw510 fs14 text-black-theme" style="">
                            <div class="form-group">
                                <select name="method" class="couponMethod ml setupSelect2 w100">
                                    <option value="none">Chọn kiểu giảm giá</option>
                                    @foreach (__('config.couponMethod') as $key => $val)
                                        <option {{(isset($coupon) && $coupon->method == $key) ? 'selected' : ''}} value="{{ $key }}">{{ $val }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="box-discount-order {{(isset($coupon) && $coupon->method == 'coupon_order') ? '' : 'hidden'}}">
                                <div class="box-discount-order-title fw500">
                                    <span class="w36">Giá trị khuyến mại</span>
                                    <span class="w32 mr15"></span>
                                    <span class="w32">Giá trị giảm tối đa</span>
                                </div>
                                <div class="box-discount-order-value mt5">
                                    <div class="type-discount w36 lh34">
                                        <span data-type="value" class=" {{(!isset($coupon) || (isset($coupon) && $coupon->discount_type == 'value')) ? 'active-discount' : ''}}">Giá trị</span>
                                        <span data-type="percent" class="{{(isset($coupon) && $coupon->discount_type == 'percent') ? 'active-discount' : ''}}">%</span>
                                    </div>
                                    <div class="form-group w32 relative mr15">
                                        <input type="text" name="discount_value" value="{{isset($coupon) ? formatNumberFromSql($coupon->discount_value) : 0}}" placeholder="" class="form-control br5 pr25 text-right inputMoney">
                                        <span class="type-unit">{{(!isset($coupon) || (isset($coupon) && $coupon->discount_type == 'value')) ? 'đ' : '%'}}</span>
                                        <input type="hidden" name="discount_type" value="value">
                                    </div>
                                    <div class="form-group w32 relative">
                                        <input type="text" name="max_discount" value="{{isset($coupon) ? formatNumberFromSql($coupon->max_discount) : 0}}" placeholder="" class="form-control br5 pr25 text-right inputMoney">
                                        <span class="unit">đ</span>
                                    </div>
                                </div>
                            </div>
                            <div class="box-discount-ship {{(isset($coupon) && $coupon->method == 'coupon_ship') ? '' : 'hidden'}}">
                                <span class="fw500">Giá trị giảm tối đa</span>
                                <div class="form-group w50 relative mt5">
                                    <input type="text" name="max_discount" value="{{isset($coupon) ? formatNumberFromSql($coupon->max_discount) : 0}}" placeholder="" class="form-control br5 pr25 text-right inputMoney">
                                    <span class="unit">đ</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ibox float-e-margins iboxApplyFor {{(isset($coupon) && $coupon->method == 'coupon_ship') ? '' : 'hidden'}}">
                        <div class="ibox-title">
                            <h5>ÁP DỤNG CHO</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content text-black-theme" style="">
                            
                            <div class="apply-object">
                                <label data-model="all" class="fw510 fs14 custom-radio">
                                    <input class="applyObject allProvince fs30" type="radio" {{(!isset($coupon) || (isset($coupon) && isset($coupon->detail['model']) && $coupon->detail['model'] == 'all')) ? 'checked' : ''}} name="detail[model]" value="all">
                                    <span class="radio-mark"></span>
                                    Tất cả
                                </label><br>
                                <label data-model="province" class="fw510 fs14 custom-radio">
                                    <input class="applyObject fs30" type="radio" {{ (isset($coupon) && isset($coupon->detail['model'])  &&  $coupon->detail['model'] == 'province') ? 'checked' : ''}} name="detail[model]" value="province">
                                    <span class="radio-mark"></span>
                                    Tình/thành được chọn 
                                </label><br>
                            </div>
                            <!-- Modal để chọn nhiều-->
                            @include('backend.layouts.components.choose_multiple_modal', ['modalTitle' => 'Chọn tỉnh thành'])
                        </div>
                    </div>
                    @include('backend.layouts.components.condition_discount', ['model' => isset($coupon) ? $coupon : null])
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>NHÓM KHÁCH HÀNG</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content text-black-theme" style="">
                            @php
                                $customerCatalogue = isset($coupon) ? $coupon->detail['customerCatalogue'] : 'all';
                            @endphp
                            <div class="apply-object">
                                <label class="fw510 fs14 custom-radio">
                                    <input class="applyCustomer allProvince fs30" type="radio" {{($customerCatalogue == 'all') ? 'checked' : ''}} name="detail[customerCatalogue]" value="all">
                                    <span class="radio-mark"></span>
                                    Tất cả
                                </label><br>
                                <label class="fw510 fs14 custom-radio">
                                    <input class="applyCustomer fs30" type="radio" {{ ($customerCatalogue !== 'all') ? 'checked' : ''}} name="detail[customerCatalogue]" value="some">
                                    <span class="radio-mark"></span>
                                    Nhóm khách hàng đã lưu 
                                </label><br>
                                <div class="form-group ml40 boxChooseCustomerCatalogue {{($customerCatalogue !== 'all') ? '' : 'hidden'}}">
                                    <select style="width:100%" multiple="multiple" name="detail[customerCatalogue][id][]" class="ml setupSelect2">
                                        @foreach ($customerCatalogues as $key => $item)
                                            <option {{($customerCatalogue !== 'all' && in_array($item->id, $customerCatalogue['id'])) ? 'selected' : ''}} value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>GIỚI HẠN SỬ DỤNG</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content text-black-theme">
                            @php
                                $isUsageLimit = (isset($coupon) && $coupon->detail['limit']['usageLimit'] == 1) ? true : false;
                                $limitQuantity = $isUsageLimit ? $coupon->detail['limit']['limitQuantity'] : 1;
                            @endphp
                            <div>
                                <input type="hidden" name="detail[limit][usageLimit]" value="0">
                                <div class="checkbox-customize flex">
                                    <input {{$isUsageLimit ? 'checked' : ''}} type="checkbox" id="usage-limit" name="detail[limit][usageLimit]" value="1"><label class="fw510 fs14 text-black-theme" for="usage-limit">Giới hạn sử dụng</label>
                                </div>
                                <div class="form-group w50 box-usage-limit {{$isUsageLimit ? '' : 'hidden'}}">
                                    <input class="form-control ml30 br5" type="number" name="detail[limit][limitQuantity]" min="1" value="{{$limitQuantity}}">
                                </div>
                            </div>
                            <div>
                                <input type="hidden" name="detail[limit][singleUse]" value="0">
                                <div class="checkbox-customize flex">
                                    <input {{(isset($coupon) && $coupon->detail['limit']['singleUse'] == 1) ? 'checked' : ''}} type="checkbox" id="single-use" name="detail[limit][singleUse]" value="1"><label class="fw510 fs14 text-black-theme" for="single-use">Giới hạn mỗi khách hàng chỉ được sử dụng mã giảm giá này 1 lần</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @if(isset($coupon))
                        <input type="hidden" class="startDate" value="{{$coupon->start_date}}">
                        <input type="hidden" class="endDate" value="{{$coupon->end_date}}">
                        <input type="hidden" class="dataDetail" value="{{json_encode($coupon->detail)}}">
                    @endif
                    @include('backend.layouts.components.time', ['model' => isset($coupon) ? $coupon : null])
                </div>
            </div>
            <div class="ibox-button">
                <button class="btn btn-primary mr10" type="submit">Lưu lại</button>
                <a href="{{route('coupon.index')}}" class="btn btn-white" >Quay lại</a>
            </div>
        </form>
    </div>
@endsection
