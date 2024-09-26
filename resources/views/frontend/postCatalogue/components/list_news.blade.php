<ul class="newslist" id="mainlist">
    @if(count($newsList))
        @foreach($newsList as $itemBlog)
            <li data-id="">
                @include('frontend.postCatalogue.components.news_item', ['itemBlog' => $itemBlog])
            </li>
        @endforeach
    @endif
</ul>
<input type="hidden" class="quantityPerpage" value="{{count($postCatalogue->posts)}}">
<input type="hidden" class="currentPage" value="1">
<input type="hidden" data-subRelation="users" data-model="postCatalogue" data-relation="posts" class="modelId" value="{{$postCatalogue->id}}">
@if(count($newsList) >= 2)
    <a href="#" class="viewmore">Xem thêm <b></b></a>
@endif