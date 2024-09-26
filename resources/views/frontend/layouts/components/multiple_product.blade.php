<div id="products" style="margin-bottom: {{$marginBottom}}px">
    @php
        $productMultipleInfo = json_decode($system['module_multiple_product'], true);
        $colorTheme = json_decode($system['general_setting'], true)['colorTheme'];
        unset($productMultipleInfo['name']);
    @endphp
    <style>
        #products button.product-button.active, 
        #products button.product-button:hover{
            color: #fff;
            background: {{$colorTheme}};
            border: 1px solid {{$colorTheme}};
        }
    </style>
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        @foreach($productMultipleInfo as $key => $itemTab)
        <li class="nav-item" role="presentation">
            <button class="product-button nav-link {{$key == 'tab1' ? 'active' : ''}}" data-target="{{$key}}">{{$itemTab['title']}}</button>
        </li>
        @endforeach
    </ul>
    <div class="tab-content" id="pills-multiple-product">
        @foreach($productMultipleInfo as $key => $itemTab)
        <div class="tab-pane {{$key == 'tab1' ? ' show active' : 'fade'}}" id="{{$key}}">
            <div class="product-main-slide">
                <div class="product-main-inner card-deck owl-carousel-multiple owl-carousel owl-theme">
                    @foreach(setupDataProductByWidget($dataByWidgets[$itemTab['widget']], $promotionData) as $itemProduct)
                        @php
                            $discountPercent = round(($itemProduct->discount / $itemProduct->priceAfterTax) * 100);
                        @endphp
                        @include('frontend.layouts.components.product_item', ['itemProduct' => $itemProduct, 'isHot' => false])
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>