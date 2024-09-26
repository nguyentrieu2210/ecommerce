@extends('backend.layouts.layout')
@section('title', __("breadcrump.".$config['module'].".".$config['method'].".title"))
@section('content')
    @include('backend.layouts.components.breadcrump', ['config' => $config])

    <div class="wrapper wrapper-content animated fadeInRight">
        @include('backend.layouts.components.errors')
        @php
            $route = $config['method'] == 'create' ? route('promotion.store') : route('promotion.update', $promotion->id);   
        @endphp
        <form method="post" id="myForm" action="{{$route}}">
            @csrf
            <div class="row">
                <div class="col-lg-8">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>CHƯƠNG TRÌNH KHUYẾN MẠI</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content" style="">
                            <div class="form-group"><label>Tên chương trình khuyến mại <span class="text-danger">(*)</span></label>
                                <input type="text" placeholder="VD: Chương trình khuyến mại T6" value="{{old('name') ?? (isset($promotion) ? $promotion->name : '')}}" name="name" class="form-control promotionName">
                            </div>
                        </div>
                    </div>
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>GIÁ TRỊ GIẢM GIÁ</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content fw510 fs14 text-black-theme" style="">
                            <div class="flex">
                                <span class="inline-block w20 text-left lh34">Giảm giá:</span>
                                <div class="type-discount w25 lh34">
                                    <span data-type="value" class=" {{(!isset($promotion) || (isset($promotion) && $promotion->discount_type == 'value')) ? 'active-discount' : ''}}">Giá trị</span>
                                    <span data-type="percent" class="{{(isset($promotion) && $promotion->discount_type == 'percent') ? 'active-discount' : ''}}">%</span>
                                </div>
                                <div class="form-group w58 relative">
                                    <input type="text" name="discount_value" value="{{isset($promotion) ? formatNumberFromSql($promotion->discount_value) : 0}}" placeholder="" class="form-control br5 pr25 text-right amountDiscountModal inputMoney">
                                    <span class="type-unit">{{(!isset($promotion) || (isset($promotion) && $promotion->discount_type == 'value')) ? 'đ' : '%'}}</span>
                                    <input type="hidden" name="discount_type" value="{{$promotion->discount_type ?? 'value'}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>ÁP DỤNG CHO</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content text-black-theme" style="">
                            @if(isset($promotion))
                                <input type="hidden" class="startDate" value="{{$promotion->start_date}}">
                                <input type="hidden" class="endDate" value="{{$promotion->end_date}}">
                                <input type="hidden" class="dataDetail" value="{{json_encode($promotion->detail)}}">
                            @endif
                            <div class="apply-object">
                                <input type="hidden" name="model" value="{{$promotion->model ?? 'all'}}">
                                <label data-model="all" class="fw510 fs14 custom-radio">
                                    <input class="applyObject allProduct" type="radio" {{(!isset($promotion) || (isset($promotion) && $promotion->detail['model'] == 'all')) ? 'checked' : ''}} name="detail[model]" value="all" style="font-size: 30px">
                                    <span class="radio-mark"></span>
                                    Tất cả sản phẩm
                                </label><br>
                                <label data-model="product" class="fw510 fs14 custom-radio">
                                    <input class="applyObject" type="radio" {{ (isset($promotion) && $promotion->detail['model'] == 'product') ? 'checked' : ''}} name="detail[model]" value="product" style="font-size: 30px">
                                    <span class="radio-mark"></span>
                                    Sản phẩm
                                </label><br>
                                
                                <label data-model="productCatalogue" class="fw510 fs14 custom-radio">
                                    <input class="applyObject" type="radio" {{ (isset($promotion) && $promotion->detail['model'] == 'productCatalogue') ? 'checked' : ''}} name="detail[model]" value="productCatalogue">
                                    <span class="radio-mark"></span>
                                    Danh mục sản phẩm
                                </label>
                            </div>
                            <!-- Modal để chọn nhiều sản phẩm-->
                            <div class="modal fade chooseMultiple modal-customize" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title">
                                        Chọn sản phẩm
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group search-multiple-model">
                                            <i class="fa fa-search"></i>
                                            <input type="text" value="" placeholder="Tìm kiếm theo tên" class="form-control inputSearchModelModal">
                                        </div>
                                    </div>
                                    <div class="render-list-multiple-model">
                                    </div>
                                    <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-cancel" data-dismiss="modal">Hủy</button>
                                    <button type="button" class="btn btn-primary btnApplyNewListModel">Áp dụng</button>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                </div>
                <div class="col-lg-4">
                    @include('backend.layouts.components.time', ['model' => isset($promotion) ? $promotion : null])
                </div>
            </div>
            <div class="ibox-button">
                <button class="btn btn-primary mr10" type="submit">Lưu lại</button>
                <a href="{{route('promotion.index')}}" class="btn btn-white" >Quay lại</a>
            </div>
        </form>
    </div>
@endsection
