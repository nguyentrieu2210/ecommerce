@extends('frontend.layouts.layout')
@section('content')
    <div id="homepage">
        @php
            $blog = json_decode($system['blog'], true);
            $postInfo = json_decode($system['post'], true);
            $menuBlog = setupData($menus)[$blog['menu']];
            $featuredNews = $dataByWidgets[$postInfo['widget']];
        @endphp
        <div class="container">
            @include('frontend.layouts.components.breadcrumb', ['breadcrumb' => $postCatalogues, 'nameObject' => 'Bài viết'])
            @include('frontend.layouts.components.blog_menu', ['canonical' => $post->canonical])
            <div class="post-detail">
                <h1 class="titledetail">{{ $post->name }}</h1>
                <div class="author-info flex">
                    <span><img src="{{ asset($post->users->image) }}" alt=""></span>
                    <span class="author-name">{{ $post->users->name }}</span>
                    <span class="dot"></span>
                    <span class="time-post">{{ $post->created_at->diffForHumans() }}</span>
                </div>
                {!! $post->content !!}
            </div>
            @if(count($featuredNews) > 1)
            <div class="bottom">
                <h5 class="titlerelate" style="color: {{$postInfo['colorTitle']}}">{{$postInfo['title']}}</h5>
                <ul class="newsrelate">
                    @foreach($featuredNews as $news)
                        {{-- @if($news->id !== $post->id) --}}
                            <li>
                                <a href="{{setupUrl($news->canonical)}}" class="linkimg"
                                    previewlistener="true">
                                    <div class="tempvideo">
                                        <img width="100"
                                            src="{{asset($news->image)}}">
                                    </div>
                                    <h3>{{$news->name}}
                                    </h3>
                                    <span class="timepost">{{formatStringToCarbon($news->created_at)->diffForHumans()}}</span>
                                </a>
                            </li>
                        {{-- @endif --}}
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>
@endsection
