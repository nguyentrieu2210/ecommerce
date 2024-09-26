@extends('backend.layouts.layout')
@section('title', __("breadcrump.".$config['module'].".".$config['method'].".title"))
@section('content')
    @include('backend.layouts.components.breadcrump', ['config' => $config])

    <div class="wrapper wrapper-content animated fadeInRight">
        @include('backend.layouts.components.errors')
        @php
            $route = $config['method'] == 'create' ? route('purchaseOrder.store') : route('purchaseOrder.update', $purchaseOrder->id);   
            $statusDisabled = (!isset($purchaseOrder) || (isset($purchaseOrder) && ($purchaseOrder->status == 'pending' || $purchaseOrder->status == 'pending_processing'))) ? '' : 'disabled';
        @endphp
        <form id="myForm" method="post" action="{{$route}}">
            @csrf
            {{-- COMPONENT COMFIRM EXIT --}}
            @include('backend.layouts.components.confirm_exit')
            <div class="row">
                <input type="hidden" class="statusModel" value="{{isset($purchaseOrder) ? $purchaseOrder->status : ''}}">
                @if(isset($purchaseOrder))
                <input type="hidden" class="purchaseOrderId" value="{{$purchaseOrder->id}}">
                <input type="hidden" name="code" value="{{$purchaseOrder->code}}">
                <div class="flex-space-between col-lg-12 box-title-supplier">
                    <div class="flex" style="align-items:center">
                        <span class="btn btn-back"><a href="{{route('purchaseOrder.index')}}"><i class="fa fa-arrow-left"></i></a></span>
                        <h5 class="name-model">{{$purchaseOrder->code}}</h5>
                        <span class="created-at">{{formatDateTimeFromSql($purchaseOrder->created_at)}}</span>
                        <span class="{{__('config.statusPurchaseOrder')[$purchaseOrder->status]}}">{{__('filter.purchaseOrder')[$purchaseOrder->status]}}</span>
                    </div>
                    <div>
                        @if($purchaseOrder->status == 'pending')
                            <span class="fw510 text-black-theme btn-action btn-action-confirm"><i class="fa fa-check-circle fs17 text-theme"></i> Duyệt đơn</span>
                        @endif
                        @if($purchaseOrder->status == 'pending' || $purchaseOrder->status == 'pending_processing')
                            <span class="fw510 text-black-theme btn-action btn-action-cancel"><i class="fa fa-times-circle fs17 text-danger"></i> Hủy đơn</span>
                        @endif
                        @if($purchaseOrder->status == 'pending_processing' || $purchaseOrder->status == 'partial_processed')
                            <span class="fw510 text-black-theme btn-action btn-action-receive"><i class="fa fa-archive fs17 text-success"></i> <a href="/admin/receiveInventory/create?purchase_order_id={{$purchaseOrder->id}}">Nhập hàng</a></span>
                        @endif
                    </div>
                </div>
                @endif
                @php
                    $model = isset($purchaseOrder) ? $purchaseOrder : null;
                @endphp
                <div class="col-lg-8">
                    {{-- COMPONENT SEARCH PRODUCT --}}
                    @include('backend.layouts.components.choose_product', ['model' => $model])
                    {{-- COMPONENT PAYMENT --}}
                    @include('backend.layouts.components.payment', ['model' => $model])
                    {{-- COMPONENT HISTORY --}}
                    @if(isset($purchaseOrder))
                        @include('backend.layouts.components.history')
                    @endif
             
                </div>
                <div class="col-lg-4">
                    @include('backend.layouts.components.choose_supplier', ['model' => $model])
                    @include('backend.layouts.components.choose_warehouse' , ['model' => $model])
                    @include('backend.layouts.components.additional_info', ['model' => $model])
                  
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5 class="ibox-title-customize">GHI CHÚ</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content" style="position: relative;">
                            <div class="form-group">
                                <textarea style="height: 70px" class="form-control" name="description" id="" cols="10"
                                rows="10">{{ isset($purchaseOrder) ? $purchaseOrder->description : '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ibox-button">
                <button class="btn btn-primary mr10 btnSubmit" {{$statusDisabled}} type="submit">{{isset($purchaseOrder) ?  'Lưu' : 'Tạo đơn đặt hàng'}}</button>
                <a href="{{route('supplier.index')}}" class="btn btn-white" >Quay lại</a>
            </div>
        </form>
    </div>
@endsection
