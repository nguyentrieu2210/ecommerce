@extends('frontend.layouts.layout')
@section('content')
    @php
        $colorTheme = $generalSetting['colorTheme'];
    @endphp
    <style>
        .attribute-active {
            border: 1px solid {{$colorTheme}};
        }
        .attribute-active::after {
            background: {{$colorTheme}};
        }
        .shopping-group a:first-child {
            background: linear-gradient(to right, {{$colorTheme}}, RGB(18, 103, 174));
        }
        .image-active {
            border: 1px solid {{$colorTheme}} !important;
        }
        .shopping-group a:last-child {
            color: {{$colorTheme}};
            border: 1px solid {{$colorTheme}};
        }
    </style>
    <div id="product-page">
        <div class="container">
            @include('frontend.layouts.components.breadcrumb', ['breadcrumb' => $productCatalogues, 'nameObject' => $product->name])
            <div id="product-main">
                <!-- Product Main T -->
                <div class="product-main-t">
                    <input type="hidden" class="product" value="{{json_encode($product)}}">
                    <h3 class="productName">{{$product->name}}</h3>
                    <div class="product-main-t-m">
                        <p>SKU: <span class="productSku">{{$product->code}}</span> |
                            <span class="rateYo"></span>
                            <span>
                                | {{$ratingSummary['total_ratings']}} đánh giá
                            </span>
                        </p>
                    </div>
                </div>
                <!-- Product Main M -->
                <div class="product-main-m row">
                    <!-- Product Main Left -->
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        @include('frontend.product.components.image')
                    </div>
                    <!-- Product Main Right -->
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        @include('frontend.product.components.attribute')
                        @if(count($warehouses))
                            <div class="product-main-m-r-inf">
                                @php
                                    $price = calculatePriceAfterTax($product->tax_status, $product->output_tax, $product->price);
                                    $finalPrice = caculaterFinalPrice($product, $price);
                                @endphp
                                <input type="hidden" class="finalPrice" value="{{$finalPrice}}">
                                <input type="hidden" class="originalPrice" value="{{$price}}">
                                <h4><span class="finalPrice">{{formatNumberFromSql($finalPrice)}}</span><span class="currency">đ</span></h4>
                                @if($finalPrice !== $price)
                                    <span class="oldPrice">{{formatNumberFromSql($price) . 'đ'}}</span>
                                @endif
                                <div class="inf-top flex align-center">
                                    @php 
                                        $statusProduct = 'Đang tải...';
                                        $quantitySell = 0;
                                        if(empty($product->variant)) {
                                            $productWarehouse = $product->new_warehouses[$warehouses[0]->id];
                                            $quantitySell = $productWarehouse['quantity'] - $productWarehouse['stock'];
                                            $statusProduct = $quantitySell == 0 ? 'Hết hàng' : ('Còn hàng (' . $quantitySell . ')');
                                        }
                                    @endphp
                                    <span class="status-product {{$quantitySell == 0 ? 'text-danger' : 'text-success'}}">{!!$statusProduct!!}</span>
                                    <input type="hidden" class="quantitySell" value="{{$quantitySell}}">
                                    <div class="box-choose-warehouse">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <select class="choose-warehouse" name="warehouse_id">
                                            @foreach($warehouses as $warehouse)
                                                <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="product-main-m-r-buy">
                                <div class="shopping-group">
                                    <a class="buttonCart buyNow" href="#"><span>MUA NGAY</span><small class="fs12">Giao tận nơi không mua không sao</small></a>
                                    <a class="buttonCart addCart" href="#"><span>THÊM VÀO GIỎ</span><small class="fs12">Cho vào giỏ để chọn tiếp</small></a>
                                </div>
                                <p><i class="fa fa-phone-volume"></i>Gọi mua hàng: <span>0904.881169 - 1900.6619</span> (từ
                                    8h30 đến 21h00 hàng ngày)</p>
                            </div>
                        @else
                            <span class="text-status">SẢN PHẨM HIỆN KHÔNG HOẠT ĐỘNG</span>
                        @endif
                    </div>
                    <!-- End Product Main Right -->
                </div>
            </div>
            <div class="box-bottom row">
                <div class="col-lg-7 col-md-7 col-12 p15">
                    {{-- -----------DESCRIPTION------------ --}}
                    @include('frontend.product.components.description')
                    {{-- -----------COMMENT--------------- --}}
                    @include('frontend.product.components.comment')
                </div>
                <div class="col-lg-5 col-md-5 col-12 p15">
                    @include('frontend.product.components.parameter')
                    @include('frontend.product.components.policy')
                </div>
            </div>
            @include('frontend.product.components.related_news')
        </div>
    </div>
@endsection
