<div class="product-main-m-img">
    <div class="big-img-holder">
        <div class="">
            @php
                $bigImage = !empty($product->album) ? $product->album[0] : '/backend/img/empty-image.png';
            @endphp
            <div class="">
                <a href="#" class="disabled-link">
                    <img class="bigImage" src="{{$bigImage}}" alt="{{$product->name}}">
                </a>
            </div>
        </div>
    </div>
    <div class="img-group">
        <ul>
            @if(!empty($product->album))
                @foreach($product->album as $albumIndex => $albumItem)
                    <li class="nav-item">
                        <button class="itemImage {{$albumIndex == 0 ? 'image-active' : ''}}">
                            <img src="{{asset($albumItem)}}" alt="">
                        </button>
                    </li>
                @endforeach
            @endif
        </ul>
    </div>
</div>