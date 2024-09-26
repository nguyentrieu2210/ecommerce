@extends('backend.layouts.layout')
@section('title', __("breadcrump.".$config['module'].".".$config['method'].".title"))
@section('content')
    @include('backend.layouts.components.breadcrump', ['config' => $config])

    <div class="wrapper wrapper-content animated fadeInRight">
        @include('backend.layouts.components.errors')
        @php
            $route = $config['method'] == 'create' ? route('receiveInventory.store') : route('receiveInventory.update', $receiveInventory->id);   
            $statusDisabled = ($config['method'] == 'create' || ($config['method'] == 'edit' && $receiveInventory->status_payment == 'pending' && $receiveInventory->status_receive_inventory == 'pending')) ? '' : 'disabled';
        @endphp
        <form id="myForm" method="post" action="{{$route}}">
            @csrf
            {{-- COMPONENT COMFIRM EXIT --}}
            @include('backend.layouts.components.confirm_exit')
            <div class="row">
                <input type="hidden" class="statusModel" value="{{isset($receiveInventory) ? $receiveInventory->status_payment : ''}}">
                <input type="hidden" class="statusDisabled" value="{{$statusDisabled}}">
                @if(isset($receiveInventory))
                    <input type="hidden" class="receiveInventoryId" value="{{$receiveInventory->id}}">
                    @if(isset($config['type']) && $config['type'] == 'createByPurchaseOrder')
                        <input type="hidden" name="purchase_order_id" value="{{$receiveInventory->id}}">
                    @else   
                        <input type="hidden" name="code" value="{{$receiveInventory->code}}">
                    @endif
                    @if(isset($config['type']) && $config['type'] == 'createByPurchaseOrder')
                        <div class="flex col-lg-12 mb15">
                            <span class="btn btn-back"><a href="{{route('receiveInventory.index')}}"><i class="fa fa-arrow-left"></i></a></span>
                            <h5 class="name-model">Tạo đơn nhập từ đơn đặt {{$receiveInventory->code}}</h5>
                        </div>
                    @else
                        <div class="flex-space-between wrap col-lg-12 box-title-supplier">
                            <div class="flex-wrap" style="align-items:center">
                                <span class="btn btn-back"><a href="{{route('receiveInventory.index')}}"><i class="fa fa-arrow-left"></i></a></span>
                                <h5 class="name-model">{{$receiveInventory->code}}</h5>
                                <span class="created-at">{{formatDateTimeFromSql($receiveInventory->created_at)}}</span>
                                @php
                                    $statusReceiveInventory = ($receiveInventory->status_receive_inventory == 'received' && $receiveInventory->status_payment == 'paid') ? 'complete' : 'pending';
                                @endphp
                                <span class="{{__('config.statusReceiveInventory')[$statusReceiveInventory]}}">{{__('filter.statusReceiveInventory')[$statusReceiveInventory]}}</span>
                            </div>
                            <div>
                                @if($receiveInventory->status == 'pending' || $receiveInventory->status == 'pending_processing')
                                    <span class="fw510 text-black-theme btn-action btn-action-cancel"><i class="fa fa-times-circle fs17 text-danger"></i> Hủy đơn</span>
                                @endif
                                @if($receiveInventory->status == 'pending_processing' && $receiveInventory->status == 'partial_processed')
                                    <span class="fw510 text-black-theme btn-action btn-action-receive"><i class="fa fa-archive fs17 text-success"></i> Hoàn trả</span>
                                @endif
                            </div>
                        </div>
                    @endif
                @endif
                @php
                    $model = isset($receiveInventory) ? $receiveInventory : null;
                @endphp
                <div class="col-lg-8">
                    {{-- COMPONENT SEARCH PRODUCT --}}
                    @include('backend.layouts.components.choose_product', ['model' => $model ])
                    {{-- COMPONENT PAYMENT --}}
                    @include('backend.layouts.components.payment', ['model' => $model])
                    {{-- COMPONENT HISTORY --}}
                    @if($config['method'] == 'edit')
                        @include('backend.layouts.components.history')
                    @endif
             
                </div>
                <div class="col-lg-4">
                    @include('backend.layouts.components.choose_supplier', ['model' => $model])
                    @include('backend.layouts.components.choose_warehouse', ['model' => $model ])
                    @include('backend.layouts.components.additional_info', ['model' => $model ])
                  
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
                                rows="10">{{ isset($receiveInventory) ? $receiveInventory->description : '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ibox-button">
                <button {{$statusDisabled}} class="btn btn-primary mr10 btnSubmit" type="submit">{{isset($receiveInventory) ?  'Lưu' : 'Tạo đơn đặt hàng'}}</button>
                <a href="{{route('supplier.index')}}" class="btn btn-white" >Quay lại</a>
            </div>
        </form>
    </div>
@endsection
