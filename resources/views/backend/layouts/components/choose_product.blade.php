<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5 class="ibox-title-customize">
            @if($config['module'] == 'receiveInventory' && $config['method'] == 'edit')
                
                <span class="flex" style="align-items: center">
                    @if($model->status_receive_inventory == 'pending')
                        <svg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'><g clip-path='url(#clip0_5014_9212)'><circle cx='12' cy='12' r='12' fill='#FFDF9B'/><circle cx='12' cy='12' r='7' fill='white' stroke='#E49C06' stroke-width='1.5' stroke-dasharray='2 2'/></g><defs><clipPath id='clip0_5014_9212'><rect width='24' height='24' fill='white'/></clipPath></defs></svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><g clip-path="url(#clip0_3088_51546)"><circle cx="12" cy="12" r="10" fill="white" stroke="#CFF6E7" stroke-width="4"/><path fill-rule="evenodd" clip-rule="evenodd" d="M4 12C4 7.584 7.584 4 12 4C16.416 4 20 7.584 20 12C20 16.416 16.416 20 12 20C7.584 20 4 16.416 4 12ZM10.4 13.736L15.672 8.46401L16.8 9.60001L10.4 16L7.2 12.8L8.328 11.672L10.4 13.736Z" fill="#0DB473"/></g><defs><clipPath id="clip0_3088_51546"><rect width="24" height="24" fill="white"/></clipPath></defs></svg>
                    @endif
                    <span class="uppercase">{{__('filter.statusReceive')[$model->status_receive_inventory]}}</span>
                </span>
            @else
                SẢN PHẨM
            @endif
        </h5>
        <div class="ibox-tools">
            <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
            </a>
        </div>
    </div>
    <div class="ibox-content" style="">
        <div class="choose-model flex w100">
            <div class="form-group search-model">
                <i class="fa fa-search {{$statusDisabled == 'disabled' ? 'hidden' : ''}}"></i>
                <input type="text" name="" class="inputSearchProduct form-control w100 {{$statusDisabled == 'disabled' ? 'hidden' : ''}}" value="" placeholder="Tìm kiếm theo tên, mã SKU, Barcode,...(F3)">
            </div>
            {{-- <span class="btn choose-multiple-model"  data-toggle="modal" data-target=".chooseMultiple">Chọn nhiều</span> --}}
            <!-- Modal để chọn nhiều sản phẩm-->
            {{-- <div class="modal fade chooseMultiple modal-customize" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">
                        Chọn nhiều sản phẩm
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group search-multiple-model">
                            <i class="fa fa-search"></i>
                            <input type="text" name="" value="" placeholder="Tìm kiếm theo tên, mã SKU, Barcode,...(F3)" class="form-control">
                        </div>
                    </div>
                    <div class="render-list-multiple-model">
                        @for($i = 0; $i < 5; $i++)
                        <div class="item-multiple-model">
                            <div class="render-item-multiple-model flex-space-between">
                                <div class="render-item-model-l flex">
                                    <div class="check-multiple-model"><input type="checkbox" class="check-id" value=""></div>
                                    <div class="style-avatar-product text-center">
                                        <img src="/backend/img/empty-image.png" alt="">
                                    </div>
                                    <div class="render-item-model-info-r">
                                        <span class=" fs14 fw550">Sữa rửa mặt Hazeline</span>
                                        <br><span class="sku-product fs12">SKU:SKU24-1</span>
                                    </div>
                                </div>
                                <div class="render-item-model-r h50 text-right">
                                    <p class="fw510">0<span class="currency">đ</span></p>
                                    <span>Có thể bán: <span class="text-primary fw510">300</span></span>
                                </div>
                            </div>
                        </div>
                        @endfor
                        <div class="item-multiple-model">
                            <div class="render-item-multiple-model flex-space-between">
                                <div class="render-item-model-l flex">
                                    <div class="check-multiple-model"><input type="checkbox" class="check-id" value=""></div>
                                    <div class="style-avatar-product text-center">
                                        <img src="/backend/img/empty-image.png" alt="">
                                    </div>
                                    <div class="render-item-model-info-r">
                                        <span class=" fs14 fw550">Sữa rửa mặt Hazeline</span>
                                        <br><span class="sku-product fs12">SKU:SKU24-1</span>
                                    </div>
                                </div>
                                <div class="render-item-model-r h50 text-right"></div>
                            </div>
                            <div class="list-variant">
                                <div class="render-item-variant-model flex-space-between">
                                    <div class="render-item-model-l flex">
                                        <div class="check-variant-model"><input type="checkbox" class="check-id" value=""></div>
                                        <div class="render-item-variant-model-info-r">
                                            <br><span class=" fs14 fw510">50 mil</span>
                                            <br><span class="sku-product fs12">SKU:SKU24-1</span>
                                        </div>
                                    </div>
                                    <div class="render-item-model-r h50 text-right">
                                        <p class="fw510">0<span class="currency">đ</span></p>
                                        <span>Có thể bán: <span class="text-primary fw510">300</span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-cancel" data-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary">Áp dụng</button>
                    </div>
                </div>
                </div>
            </div> --}}
            {{-- Hiển thị danh sách chọn từng sản phẩm --}}
            <div class="render-list-model hidden">
                {{-- <div class="render-item-model flex-space-between">
                    <div class="render-item-model-l flex">
                        <div class="style-avatar-product text-center">
                            <img src="/backend/img/empty-image.png" alt="">
                        </div>
                        <div class="render-item-model-info-r">
                            <span class="text-primary fs14 fw550">Sữa rửa mặt Hazeline</span>
                            <br><span class="name-variant fs14">50 mil</span>
                            <br><span class="sku-product fs12">SKU:SKU24-1</span>
                        </div>
                    </div>
                    <div class="render-item-model-r h50 text-right">
                           <p class="fw510">0<span class="currency">đ</span></p>
                        <span>Có thể bán: <span class="text-primary fw510">300</span></span>
                    </div>
                </div> --}}
            </div>
        </div>
        @php
            $countProduct = 0;
            if(isset($model)) {
                $countProduct = count($model->products);
            }
        @endphp
        <div class="display-model">
            {{-- danh sách sản phẩm trống --}}
            <div class="empty-model {{$countProduct > 0 ? 'hidden' : ''}}">
                <div class="box-empty-model ">
                    <img src="/backend/img/empty-product.png" alt="">
                </div>
                <p>Bạn chưa thêm sản phẩm nào</p>
                <span class="btn btnAddProduct">Thêm sản phẩm</span>
            </div>
            {{-- Danh sách sản phẩm được chọn --}}
            <div class="list-model {{$countProduct ? '' : 'hidden'}}">
                <div class="list-model-title">
                    <div class="title-model flex w100">
                        <span class="w40 text-left">Sản phẩm</span>
                        <span class="text-center w15">Số lượng</span>
                        <span class="text-right w15">Đơn giá</span>
                        <span class="text-right w20">Thành tiền</span>
                    </div>
                </div>
                <div class="list-model-choosed">
                    @if($countProduct)
                    @foreach($model->products as $key => $itemProduct)
                        @php
                            $productId = $itemProduct->pivot->product_id;
                            $variantId = $itemProduct->pivot->product_variant_id;
                            $itemId = $productId . ($variantId !== null ? ('-' . $variantId) : ''); 
                            $canonical = '/admin/product/' . $productId . '/edit';
                            $newCostPrice = $itemProduct->pivot->cost_price;
                            $oldCostPrice = $newCostPrice;
                            $quantity = $itemProduct->pivot->quantity;
                            $finalQuantity = null;
                            if($itemProduct->pivot->discount_value !== null) {
                                $discountValue = (float)$itemProduct->pivot->discount_value;
                                $oldCostPrice = (float)$newCostPrice + ($itemProduct->pivot->discount_type == 'percent' ? (float)$newCostPrice / (100 - $discountValue) * $discountValue : $discountValue);
                            }
                            $detail = setupDetailToRenderProduct($itemProduct->pivot);
                            if(isset($config['type']) && $config['type'] == 'createByPurchaseOrder') {
                                $finalQuantity = $quantity - $itemProduct->pivot->quantity_received - $itemProduct->pivot->quantity_rejected;
                            }
                        @endphp
                        @if($finalQuantity == null || $finalQuantity > 0) 
                            <div class="item-model flex" data-itemId="{{$itemId}}" data-id="{{$productId}}" data-variantId="{{$itemProduct->pivot->product_variant_id}}">
                                <div class="item-model-info flex w40">
                                    <input type="hidden" class="cost_price" name="products[{{$itemId}}][cost_price]" value="{{$itemProduct->pivot->cost_price}}"> {{-- Giá vốn (sau khi đã được chiết,vv...) --}}
                                    <input type="hidden" class="discount_value" name="products[{{$itemId}}][discount_value]" value="{{$itemProduct->pivot->discount_value}}">
                                    <input type="hidden" class="discount_type" name="products[{{$itemId}}][discount_type]" value="{{$itemProduct->pivot->discount_type}}">
                                    <textarea name="products[{{$itemId}}][detail]" class="hidden">{{json_encode($detail)}}</textarea>
                                    <div class="style-avatar-product text-center">
                                        <img src="{{$itemProduct->pivot->image}}" alt="">
                                    </div>
                                    <div class="item-model-info-r text-left">
                                        <a href="{{$canonical}}" class="text-primary confirmLink">{{$itemProduct->pivot->name}}</a>
                                        @if($itemProduct->pivot->name_variant !== null) 
                                            <span class="name-variant fs14">{{$itemProduct->pivot->name_variant}}</span>
                                        @endif
                                        <span class="sku-product fs12">{{$itemProduct->pivot->sku}}</span>
                                    </div>
                                </div>
                                @if($statusDisabled == 'disabled')
                                    <span class="text-center w15">
                                    @if($config['module'] == 'purchaseOrder')
                                        <span >{{$quantity}}</span><br>
                                        @if($model->status == 'partial_processed' || $model->status == 'fully_processed')
                                            <span class="quantity-active">
                                                <span class="text-theme quantity-received" data-toggle="tooltip" data-placement="top" title="Số lượng đã nhập">{{$itemProduct->pivot->quantity_received}}</span>/<span class="text-danger quantity-rejected"  data-toggle="tooltip" data-placement="top" title="Số lượng đã từ chối">{{$itemProduct->pivot->quantity_rejected}}</span>
                                            </span>
                                        @endif
                                    @elseif($config['module'] == 'receiveInventory')
                                        <span >{{$quantity}}</span><br>
                                        @if(isset($model) && $model->purchase_order_id !== null && $itemProduct->pivot->quantity_rejected > 0)
                                            <span class="text-danger quantity-received">{{$itemProduct->pivot->quantity_rejected}}</span>
                                        @endif
                                    @endif  
                                    </span>
                                @else
                                    @if((isset($config['type']) && $config['type'] == 'createByPurchaseOrder'))
                                        <span class="w15">
                                            <span class="text-primary changeQuantityReceive item-model-quantity">{{$finalQuantity}}</span><br>
                                            <span class="text-danger item-model-quantity-reject hidden">0</span>
                                        </span>
                                        <input type="hidden" class="quantity_receive" name="products[{{$itemId}}][quantity]" value="{{$quantity}}"> 
                                        <input type="hidden" class="quantity_reject" name="products[{{$itemId}}][quantity_rejected]" value=""> 
                                        <input type="hidden" class="rejection_reason" name="products[{{$itemId}}][rejection_reason]" value=""> 
                                    @else
                                        <input type="number" name="products[{{$itemId}}][quantity]" value="{{$quantity}}" min="1" placeholder="" class="form-control item-model-quantity w15">
                                    @endif
                                @endif
                                <span class="cost-price w15 text-right {{$statusDisabled == 'disabled' ? '' : 'addDiscount text-primary'}}">
                                    <span class="costPrice">{{formatNumberFromSql($newCostPrice)}}</span><span class="currency">đ</span><br>
                                    <span class="oldCostPrice {{$newCostPrice == $oldCostPrice ? 'hidden' : ''}}">{{formatNumberFromSql($oldCostPrice)}}</span>
                                </span>
                                <span class=" w20 text-right"><span class="subTotal">{{formatNumberFromSql($newCostPrice * $quantity)}}</span><span class="currency">đ</span></span>
                                <span class="w10 text-center delete-item-model {{($statusDisabled == 'disabled' || (isset($config['type']) && $config['type'] == 'createByPurchaseOrder')) ? 'hidden' : ''}}"><i class="fa fa-trash"></i></span>
                            </div>
                        @endif
                    @endforeach
                    @endif
                </div>
                @if($config['module'] == 'receiveInventory')
                    @if($config['method'] == 'create')
                        <div class="confirmReceiveInventory w100 text-left">
                            <input type="hidden" name="status_receive_inventory" value="pending">
                            <div class="checkbox-confirm-receive flex">
                                <input {{(isset($config['type']) && $config['type'] == 'createByPurchaseOrder') ? 'checked disabled' : ''}} type="checkbox" id="confirmReceive" name="status_receive_inventory" value="received"><label class="fw500" for="confirmReceive">Nhập kho khi tạo đơn</label>
                            </div>
                            @if(isset($config['type']) && $config['type'] == 'createByPurchaseOrder')
                                <input type="hidden" name="status_receive_inventory" value="received">
                            @endif
                        </div>
                    @elseif($model->status_receive_inventory == 'pending')
                        <div class="btnConfirmReceiveInventory">
                            <span class="btn btn-primary">Nhập kho</span>
                        </div>
                    @endif
                @endif
            </div>
            {{-- Modal điều chỉnh số lượng nhập --}}
            <div class="modal fade modal-customize changeQuantityReceiveModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Điều chỉnh số lượng nhập</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body new-text">
                        <div class="alert-error hidden" style="margin:0; padding: 0; width:100%">
                            <div class="alert-error-box flex" style="align-items:center">
                                <i class="fa fa-exclamation-circle"></i>
                                <span class="error fs14 fw500 text-left" style="font-style: normal">Nếu số lượng sản phẩm nhập và từ chối đều bằng 0, hệ thống sẽ tự động loại bỏ sản phẩm khỏi danh sách nhập</span>
                            </div>
                        </div>
                        <div class="flex">
                            <span class="inline-block w36 text-left lh34">Số lượng sản phẩm nhập:</span>
                            <div class="form-group w32 relative">
                                <input type="text" value="" placeholder="" class="form-control br5 text-right quantityReceive inputQuantityModal inputMoney">
                            </div>
                        </div>
                        <div class="flex">
                            <span class="inline-block w36 text-left lh34">Số lượng sản phẩm từ chối:</span>
                            <div class="form-group w32 relative">
                                <input type="text" value="" placeholder="" class="form-control br5 text-right quantityReject inputQuantityModal inputMoney">
                            </div>
                        </div>
                        <div class="form-group text-left">
                            <span class="font-black-theme fw510" >Lý do từ chối</span>
                            <input type="text" value="" placeholder="Vui lòng nhập" class="form-control mt5 br5 rejectReason">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                        <button type="button" class="btn btn-primary btnApplyChangeQuantityModal">Áp dụng</button>
                    </div>
                </div>
                </div>
            </div>
            {{-- Modal để điều chỉnh giá sản phẩm --}}
            <div class="modal fade modal-customize changeCostPrice" tabindex="-1" aria-hidden="true" data-target="">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Điều chỉnh giá sản phẩm</h5>
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
                            <span class="inline-block w20 text-left lh34">Đơn giá:</span>
                            <div class="form-group w80 relative">
                                <input type="text" value="" placeholder="" class="costPriceModal form-control br5 pr25 text-right inputMoney">
                                <span class="type-unit currency">đ</span>
                            </div>
                        </div>
                        <div class="flex chooseTypeDiscount">
                            <span class="inline-block w20 text-left lh34">Loại giảm giá:</span>
                            <div class="type-discount w25 lh34">
                                <span data-type="value" class="active-discount value">Giá trị</span>
                                <span data-type="percent" class="percent">%</span>
                            </div>
                            <div class="form-group w58 relative">
                                <input type="text" value="" placeholder="" class="form-control br5 pr25 text-right inputMoney amountDiscount">
                                <span class="type-unit">đ</span>
                            </div>
                        </div>
                        <div class="flex-space-between">
                            <span>Giá sau giảm:</span>
                            <span><span class="discountCostPrice">0</span><span class="currency">đ</span></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                        <button type="button" class="btn btn-primary btnApplyDiscount">Áp dụng</button>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>