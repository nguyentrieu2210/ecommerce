@if(count($product->posts))
<div class="box-related-news row">
    <section class="blogList pb-[40px]">
        <div class="head-sec relative mb15 ml-auto mr-auto text-center">
            <h3 class="text-HeadlineLarge font-bold leading-normal">Tin tức &amp; sự kiện</h3>
            <p class="subTitle text-sm uppercase text-gray-500">Giúp bạn cập nhật thông tin mua sắm &amp; sự kiện</p>
        </div>
        <div class="listBlog flex flex-wrap">
            @foreach($product->posts as $key => $postItem)
                <div class="blog-item">
                    <a href="{{setupUrl($postItem->canonical)}}">
                        <div class="blog-item-image">
                            <img src="{{asset($postItem->image)}}" alt="">
                        </div>
                        <div class="blog-item-info">
                            <h2 class="mb-1.5 line-clamp-2 overflow-hidden font-bold leading-tight text-dark-400 text-sm ">{{$postItem->name}}</h2>
                            <span><i class="far fa-clock"></i> {{formatStringToCarbon($postItem->created_at)->diffForHumans()}}</span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </section>
</div>
@endif