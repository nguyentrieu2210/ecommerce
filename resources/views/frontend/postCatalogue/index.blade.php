@extends('frontend.layouts.layout')
@section('content')
    <div id="homepage">
        @php
            $blog = json_decode($system['blog'], true);
            $menuBlog = setupData($menus)[$blog['menu']];
            $featuredBlog = array_slice($postCatalogue->posts->toArray(), 0, 3);
            $newsList = array_slice($postCatalogue->posts->toArray(), 3);
            $widgetBlog = $dataByWidgets[$blog['widget']];
        @endphp
        <div class="container blog">
            @include('frontend.layouts.components.blog_menu', ['canonical' => $postCatalogue->canonical])
            <div class="row">
                <div class="col-lg-8 col-md-12 col-12">
                    @include('frontend.postCatalogue.components.blog_featured')
                    @include('frontend.postCatalogue.components.list_news')
                </div>
                <div class="col-lg-4 col-md-12 col-12">
                    @include('frontend.postCatalogue.components.blog_aside')
                </div>
            </div>
        </div>
    </div>
@endsection
