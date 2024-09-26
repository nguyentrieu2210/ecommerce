@if(!empty($product->specifications))
<p class="parameter__title info__specification">Thông số kĩ thuật</p>
<div class="parameter">
    <ul class="parameter__list active">
        @foreach($product->specifications['name'] as $index => $val)
            <li data-index="0" data-prop="0">
                <p class="lileft">{{$val}}</p>
                <div class="liright">
                    <span class="comma">{{$product->specifications['value'][$index]}}</span>
                </div>
            </li>
        @endforeach
    </ul>
</div>
@endif