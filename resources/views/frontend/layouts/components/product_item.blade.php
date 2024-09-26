<div class="product-item item card">
    <a class="product-item-t mb15" href="{{setupUrl($itemProduct['canonical'])}}">
        <img class="product-item-t-img" 
            src="{{asset($itemProduct['image'])}}" alt="{{$itemProduct['name']}}">
        @if(isset($isHot) && $isHot == true)
            <i></i>
        @endif
        @if($itemProduct->discount !== 0)
            <span></span>
        @endif
    </a>
    <div class="product-item-b">
        <p><a href="{{setupUrl($itemProduct['canonical'])}}">{{$itemProduct['name']}}</a></p>
        <div class="product-item-b-g">
            <div>
                <span style="color: {{json_decode($system['general_setting'], true)['colorTheme']}}; padding-bottom:{{$itemProduct->discount == 0 ? '20px' : '0'}}">{{formatNumberFromSql($itemProduct->priceAfterTax - $itemProduct->discount)}}đ</span>
                @if($itemProduct->discount !== 0)
                    <del>{{formatNumberFromSql($itemProduct->priceAfterTax)}}đ</del>
                @endif
            </div>
            @if($itemProduct->discount !== 0)
                <span class="discount">-{{$discountPercent}}%</span>
            @endif
        </div>
    </div>
</div>