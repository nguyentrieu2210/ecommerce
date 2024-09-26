<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

{{-- <link rel="stylesheet" href="{{ asset('frontend/css/account.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/cart.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/category.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/login.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/product.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/search.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/success.css') }}"> --}}

<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="index,follow">
<meta name="author" content="{{$system['homepage_company']}}">
<meta name="copyright" content="{{$system['homepage_company']}}">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta http-equiv="refresh" content="1800">
<link rel="icon" href="{{$system['homepage_favicon']}}" type="image/png" sizes="30x30">

{{-- GOOGLE --}}
<title>{{$seo['meta_title']}}</title>
<meta name="description" content="{{$seo['meta_description']}}">
<meta name="keyword" content="{{$seo['meta_keyword']}}">
<link rel="canonical" href="{{$seo['canonical']}}">
<meta property="og:locale" content="vi_VN">
{{-- For Facebook --}}
<meta property="og:title" content="{{$seo['meta_title']}}">
<meta property="og:type" content="website">
<meta property="og:image" content="{{$system['seo_meta_images']}}">
<meta property="og:url" content="{{$seo['canonical']}}">
<meta property="og:description" content="{{$seo['meta_description']}}">
<meta property="og:site_name" content="">
<meta property="fb:admins" content="">
<meta property="fb:image" content="">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="{{$seo['meta_title']}}">
<meta name="twitter:description" content="{{$seo['meta_description']}}">
<meta name="twitter:image" content="{{$system['seo_meta_images']}}">
@php 
    $coreCss = [
        "frontend/css/bootstrap.css",
        "frontend/font/css/all.css",
        "frontend/css/owl.carousel.min.css",
        "frontend/css/owl.theme.default.min.css",
        "frontend/css/header.css",
        "frontend/css/footer.css",
        "frontend/css/style_frontend.css",
        "frontend/css/responsive.css",
        "https://unpkg.com/swiper/swiper-bundle.min.css"
    ];
    if(isset($config) && isset($config['css']) && count($config['css'])) {
        $coreCss = array_merge($coreCss, $config['css']);
    }
@endphp
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/vi.min.js"></script>
<script>
    var BASE_URL = '{{config('apps.general.APP_URL')}}'
    moment.locale('vi');
</script>
@foreach($coreCss as $key => $val) 
    <link href={{asset($val)}} rel="stylesheet">
@endforeach