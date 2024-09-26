<div id="product-hot-group" style="margin-bottom: {{$marginBottom}}px">
    @php
        $productHotInfo = json_decode($system['module_product_hot'], true);
    @endphp
    <style>
        .view-all {
            border: 1px solid {{$productHotInfo['colorTheme']}};
            color: {{$productHotInfo['colorTheme']}};
        }
        .view-all:hover {
            background: {{$productHotInfo['colorTheme']}};
        }
    </style>
    <h3 style="background: {{$productHotInfo['colorBackground']}}"><span style="color: {{$productHotInfo['colorTitle']}}">{{$productHotInfo['title']}}</span></h3>
    <div id="product-hot-main" style="border: 2px solid {{$productHotInfo['colorTheme']}}">
        <div class="product-main-slide">
            <div class="product-main-inner card-deck owl-carousel-product owl-carousel owl-theme">
                @foreach(setupDataProductByWidget($dataByWidgets[$productHotInfo['widget']], $promotionData) as $itemProduct)
                    @php
                        $discountPercent = round(($itemProduct->discount / $itemProduct->priceAfterTax) * 100);
                    @endphp
                    @include('frontend.layouts.components.product_item', ['itemProduct' => $itemProduct, 'isHot' => true])
                @endforeach
            </div>
        </div>
        <a class="view-all" href="{{setupUrl($productHotInfo['canonicalButton'])}}">{{$productHotInfo['titleButton']}}</a>
    </div>
</div>