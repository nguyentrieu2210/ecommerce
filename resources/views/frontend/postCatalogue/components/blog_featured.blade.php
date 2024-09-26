<div class="row newslist latest">
    <div class="col-lg-8 col-md-8 col-12 left-one">
        @if(count($featuredBlog) > 0)
        <a href="{{setupUrl($featuredBlog[0]['canonical'])}}" previewlistener="true">
            <div class="tempvideo">
                <img width="100" height="70"
                    src="{{asset($featuredBlog[0]['image'])}}">
            </div>
            <h3 class="titlecom">
                {{$featuredBlog[0]['name']}}
            </h3>
            <figure>
                {!!$featuredBlog[0]['description']!!}
            </figure>
            <div class="timepost margin">
                <div class="user-info"><span>{{$featuredBlog[0]['users']['name']}}</span></div>
                <span>{{formatStringToCarbon($featuredBlog[0]['created_at'])->diffForHumans()}}</span>
            </div>
        </a>
        @endif
    </div>
    @php
        $featuredBlogLeft = array_slice($featuredBlog, 1);
    @endphp
    @if(count($featuredBlogLeft))
        <div class="col-lg-4 col-md-4 col-12 right-one">
            @foreach($featuredBlogLeft as $itemBlog)
                @include('frontend.postCatalogue.components.news_item', ['itemBlog' => $itemBlog])
            @endforeach
        </div>
    @endif
</div>