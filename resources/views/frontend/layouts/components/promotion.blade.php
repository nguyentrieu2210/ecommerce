<div id="product-promotion-group" style="margin-bottom: {{$marginBottom}}px">
    @php
        $promotionInfo = json_decode($system['module_promotion'], true);
    @endphp
    <img class="image-title" src="{{$promotionInfo['smallImage']}}" alt="">
    <div class="background-image">
        <img src="{{$promotionInfo['bigImage']}}" alt="">
    </div>
    <style>
        #product-promotion-group #product-promotion-main {
            border-top:none;
            border: 10px solid {{$promotionInfo['colorTheme']}}; 
        }
        @media(max-width:576px) {
            #product-promotion-group #product-promotion-main {
                border: 2px solid {{$promotionInfo['colorTheme']}};
            }
        }
    </style>
    <div id="product-promotion-main">
        {{-- <div class="promotion-time" style="background: {{$promotionInfo['colorBackgroundTime']}}">
            <span>Sắp diễn ra <i class="fas fa-circle fs5"></i> 06/08</span><br>
            <span class="text-black fw520 fs17">Bắt đầu sau: <span class="time-running">00</span> : <span class="time-running">15</span> : <span class="time-running">10</span></span>
        </div> --}}
        <div class="product-main-slide">
            <div class="product-main-inner card-deck owl-carousel-product owl-carousel owl-theme">
                @foreach(setupDataProductByWidget($dataByWidgets[$promotionInfo['widget']], $promotionData) as $itemProduct)
                    @php
                        $discountPercent = round(($itemProduct->discount / $itemProduct->priceAfterTax) * 100);
                    @endphp
                    @include('frontend.layouts.components.product_item', ['itemProduct' => $itemProduct, 'isHot' => false])
                @endforeach
            </div>
        </div>
    </div>
</div>