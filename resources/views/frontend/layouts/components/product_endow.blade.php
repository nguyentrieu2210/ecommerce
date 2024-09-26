<div id="product-endow" style="margin-bottom: {{$marginBottom}}px">
    @php
        $productEndowInfo = json_decode($system['module_product_endow'], true);
    @endphp
    <h3><span style="color: {{$productEndowInfo['colorTitle']}}">{{$productEndowInfo['title']}}</span></h3>
    <div class="product-endow-main">
        <div class="product-main-inner card-deck owl-carousel-product owl-carousel owl-theme">
            @foreach(setupDataProductByWidget($dataByWidgets[$productEndowInfo['widget']], $promotionData) as $itemProduct)
                @php
                    $productEndowDiscountPercent = round(($itemProduct->discount / $itemProduct->priceAfterTax) * 100);
                @endphp
                <div class="product-endow-item product-item item card">
                    <a href="#">
                        <a class="product-item-t" href="{{setupUrl($itemProduct['canonical'])}}">
                            <img class="product-item-t-img w100"
                                src="{{asset($itemProduct['image'])}}" alt="{{$itemProduct['name']}}" alt="">
                            @if($itemProduct->discount !== 0)
                                <p style="background: {{$productEndowInfo['colorBackgroundDiscount']}}; color: {{$productEndowInfo['colorTextDiscount']}}">-{{$productEndowDiscountPercent}}%</p>
                            @endif
                        </a>
                        <div class="product-item-b">
                            <p><a href="{{setupUrl($itemProduct['canonical'])}}">{{$itemProduct['name']}}</a></p>
                            <div class="product-item-b-g">
                                <div class="product-item-b-g-e">
                                    <span style="color: {{json_decode($system['general_setting'], true)['colorTheme']}}">{{formatNumberFromSql($itemProduct->priceAfterTax - $itemProduct->discount)}}đ</span>
                                    @if($itemProduct->discount !== 0)
                                        <del>{{formatNumberFromSql($itemProduct->priceAfterTax)}}đ</del>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>