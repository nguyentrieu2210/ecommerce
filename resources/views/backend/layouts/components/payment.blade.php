<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5 class="ibox-title-customize">
            @if($config['module'] == 'receiveInventory' && $config['method'] == 'edit')
                
                <span class="flex" style="align-items: center">
                    @if($model->status_payment == 'pending')
                        <svg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'><g clip-path='url(#clip0_5014_9212)'><circle cx='12' cy='12' r='12' fill='#FFDF9B'/><circle cx='12' cy='12' r='7' fill='white' stroke='#E49C06' stroke-width='1.5' stroke-dasharray='2 2'/></g><defs><clipPath id='clip0_5014_9212'><rect width='24' height='24' fill='white'/></clipPath></defs></svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><g clip-path="url(#clip0_3088_51546)"><circle cx="12" cy="12" r="10" fill="white" stroke="#CFF6E7" stroke-width="4"/><path fill-rule="evenodd" clip-rule="evenodd" d="M4 12C4 7.584 7.584 4 12 4C16.416 4 20 7.584 20 12C20 16.416 16.416 20 12 20C7.584 20 4 16.416 4 12ZM10.4 13.736L15.672 8.46401L16.8 9.60001L10.4 16L7.2 12.8L8.328 11.672L10.4 13.736Z" fill="#0DB473"/></g><defs><clipPath id="clip0_3088_51546"><rect width="24" height="24" fill="white"/></clipPath></defs></svg>
                    @endif
                    <span class="uppercase">{{__('filter.statusPayment')[$model->status_payment]}}</span>
                </span>
            @else
                THANH TOÁN
            @endif
        </h5>
        <div class="ibox-tools">
            <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
            </a>
        </div>
    </div>
    <div class="ibox-content ibox-content-payment" style="font-size:14px; color:rgb(15, 24, 36);">
        <input type="hidden" name="discount_value" class="discount-value-payment" value="{{isset($model) ? $model->discount_value : ''}}">
        <input type="hidden" name="discount_type" class="discount-type-payment" value="{{isset($model) ? $model->discount_type : ''}}">
        <input type="hidden" name="price_total" class="price-total-payment" value="{{isset($model) ? $model->price_total : ''}}">
        <input type="hidden" name="quantity_total" class="quantity-total-payment" value="{{isset($model) ? $model->quantity_total : ''}}">
        <div class="flex-space-between">
            <span>Tổng tiền (<span class="totalItemPayment text-primary">{{isset($model) ? $model->quantity_total : 0}}</span> sản phẩm)</span>
            <span><span class="totalPricePayment">{{isset($model) ? formatNumberFromSql($model->price_total) : 0}}</span><span class="currency">đ</span></span>
        </div>
        <div class="flex-space-between discountPayment">
            @php
                if(isset($model) && $model->discount_type !== null && $model->discount_type == 'percent') {
                    $percentDiscount = $model->discount_value . '%';
                }else {
                    $percentDiscount = '-----';
                }
                $discountAmount = 0;
                if(isset($model) && $model->discount_type !== null) {
                    $discountAmount = $model->discount_type == 'percent' ? $model->discount_value * $model->price_total /100 : $model->discount_value;
                }
                $model = isset($model) ? $model : null;
                $finalTotalPrice = calculateFinalTotalCost($model);
            @endphp
            <span><span class="discount-payment mr20">Chiết khấu đơn (F6) </span><span class="displayDiscountPayment">{{$percentDiscount}}</span></span>
            <span>-<span class="amountDiscountPayment">{{formatNumberFromSql($discountAmount)}}</span><span class="currency">đ</span></span>
        </div>
        @if($config['module'] == 'receiveInventory')
        <input type="hidden" name="import_fee" value="{{isset($model) ? $model->import_fee : ''}}">
        <div class="feePayment flex">
            <div class="fee-payment w20">Chi phí nhập hàng (F7) </div>
            <div class="w80 list-fee-payment text-right">
                @php
                    $listFee = (isset($model) && $model['import_fee'] !== null) ? json_decode($model['import_fee']) : [];
                @endphp
                @if(count($listFee))
                    @foreach($listFee as $index => $itemFee)
                    <div class="flex-space-between">
                        <span>{{$itemFee->name}}</span>
                        <span><span class="valueFeePayment">{{formatNumberFromSql($itemFee->value)}}</span><span class="currency">đ</span></span>
                    </div>
                    @endforeach
                @else
                    <span>0<span class="currency">đ</span></span>
                @endif
            </div>
        </div>
        @endif
        <div class="flex-space-between text-customize">
            <span>Tiền cần trả NCC</span>
            <span><span class="finalTotalPayment">{{formatNumberFromSql($finalTotalPrice)}}</span><span class="currency">đ</span></span>
        </div>
        @if($config['module'] == 'receiveInventory' && $config['method'] == 'create')
        @php
            $statusPayment = $config['method'] == 'edit' ? $model->status_payment : 'pending';
        @endphp
        <div class="choose-status-payment">
            <label class="fw500 custom-radio">
                <input type="radio" name="status_payment" {{$statusPayment == 'paid' ? 'checked' : ''}} value="paid" style="font-size: 30px">
                <span class="radio-mark"></span>
                Đã thanh toán
            </label><br>
            <div class="box-paid {{$statusPayment == 'pending' ? 'hidden' : ''}}">
                @include('backend.layouts.components.box_confirm_payment', ['finalTotalPrice' => $finalTotalPrice])
            </div>
        
            <label class="fw500 custom-radio">
                <input type="radio" name="status_payment" {{$statusPayment == 'pending' ? 'checked' : ''}} value="pending">
                <span class="radio-mark"></span>
                Thanh toán sau
            </label>
        </div>
        @endif
        @if($config['module'] == 'receiveInventory' && isset($model) && $model->status_payment == 'pending' )
            <div class="btnConfirmPayment text-right">
                <span data-toggle="modal" data-target=".confirmPaymentModal" class="btn btn-primary">Xác nhận thanh toán</span>
            </div>
            {{-- Modal xác nhận thanh toán --}}
            <div class="modal fade modal-customize confirmPaymentModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Xác nhận thanh toán</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body new-text">
                        @include('backend.layouts.components.box_confirm_payment', ['finalTotalPrice' => $finalTotalPrice])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                        <button type="button" class="btn btn-primary btnConfirmPaid">Áp dụng</button>
                    </div>
                </div>
                </div>
            </div>
        @endif
    </div>
    {{-- Modal để thêm chiết khấu --}}
    <div class="modal fade modal-customize addDiscountPayment" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Thêm chiết khấu</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body new-text">
                <div class="alert-error hidden" style="margin:0; padding: 0; width:100%">
                    <div class="alert-error-box flex" style="align-items:center">
                        <i class="fa fa-exclamation-circle"></i>
                        <span class="error fs14 fw500" style="font-style: normal"></span>
                    </div>
                </div>
                <div class="flex">
                    <span class="inline-block w20 text-left lh34">Loại giảm giá:</span>
                    <div class="type-discount-payment w25 lh34">
                        <span data-type="value" class="active-discount-payment">Giá trị</span>
                        <span data-type="percent">%</span>
                    </div>
                    <div class="form-group w58 relative">
                        <input type="text" value="" placeholder="" class="form-control br5 pr25 text-right amountDiscountPaymentModal">
                        <span class="type-unit-payment">đ</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btnDeleteDiscountPayment" data-dismiss="modal">Xóa</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary btnApplyDiscountPayment">Áp dụng</button>
            </div>
        </div>
        </div>
    </div>
    {{-- Modal để thêm phí nhập hàng --}}
    <div class="modal fade modal-customize addFeeReceivePayment" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">Thêm chi phí nhập hàng</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body new-text">
                    <div class="alert-error hidden" style="margin:0; padding: 0; width:100%">
                        <div class="alert-error-box flex" style="align-items:center">
                            <i class="fa fa-exclamation-circle"></i>
                            <span class="error fs14 fw500" style="font-style: normal"></span>
                        </div>
                    </div>
                    <div class="title-fee-modal flex">
                        <span class="w60">Tên chi phí<span class="text-danger">*</span></span>
                        <span class="w40">Giá trị</span>
                    </div>
                    
                    <div class="list-fee-modal">
                        <div class="item-fee-modal first flex mt10">
                            <div class="form-group w60 pd-r20">
                                <input type="text" value="" placeholder="Nhập tên chi phí" class="name-fee-item form-control br5">
                            </div>
                            <div class="form-group w32 relative">
                                <input type="text" value="" placeholder="" class="value-fee-item form-control pd-r20 br5 text-right inputMoney">
                                <span class="type-fee-modal">đ</span>
                            </div>
                            <span class="w8 text-right fs20"><i class="fa fa-trash deleteItemFeeModal"></i></span>
                        </div>
                        <div class="list-fee-modal-extra">

                        </div>
                    </div>
                    <div class="add-fee-modal">
                        <i class="fa fa-plus-circle"></i> <span>Thêm chi phí</span>
                    </div>
                    <div class="total-fee-modal flex-space-between">
                        <span>Tổng chi phí:</span>
                        <span><span class="total-fee-modal-display">0</span><span class="currency">đ</span></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary btnApplyFeePayment">Áp dụng</button>
                </div>
            </div>
        </div>
    </div>

</div>