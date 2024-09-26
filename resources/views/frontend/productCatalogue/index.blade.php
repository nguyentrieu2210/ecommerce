@extends('frontend.layouts.layout')
@section('content')
<div id="search-page">
    <div class="container">
        @include('frontend.layouts.components.breadcrumb', ['breadcrumb' => $productCatalogues, 'nameObject' => $productCatalogue->name])
        <div class="row">
            <div id="search-page-l" class="col-lg-9 col-md-9 col-sm-12 mx-auto">
                <h3>103 sản phẩm được tìm thấy theo "iphone"</h3>
                <div class="product-main-inner card-deck">
                    @for($i = 0; $i < 10; $i ++)
                    <div class="product-item item card">
                        <a class="product-item-t" href="">
                            <img class="product-item-t-img" src="/backend/img/empty-image.png"
                                alt="">
                            <span></span>
                        </a>
                        <div class="product-item-b">
                            <p><a href="">Tivi Qled Samsung QA55Q60BAKXXV 55" 4K Ultra HD</a></p>
                            <div class="product-item-b-g">
                                <div>
                                    <span>15.700.000đ</span>
                                    <del>22.900.000đ</del>
                                </div>
                                <span class="discount">-32%</span>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
            <div id="search-page-r" class="col-lg-3 col-md-3 col-sm-12 mx-auto">
                <h3>GIÁ BÁN</h3>
                <div id="filter-list">
                    <a href="./search_filter.html"><span class="icon-square"></span><span>Dưới 1 triệu</span></a>
                    <a href="./search_filter.html"><span class="icon-square"></span><span>10 triệu - 20 triệu</span></a>
                    <a href="./search_filter.html"><span class="icon-square"></span><span>Trên 40 triệu</span></a>
                    <a href="./search_filter.html"><span class="icon-square"></span><span>1 triệu - 5 triệu</span></a>
                    <a href="./search_filter.html"><span class="icon-square"></span><span>20 triệu - 40 triệu</span></a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection