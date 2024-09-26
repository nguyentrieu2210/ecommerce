@php 
$coreJs = [
    "frontend/js/jquery-3.6.4.js",
    "frontend/js/bootstrap.js",
    "frontend/js/owl.carousel.min.js",
    "https://unpkg.com/swiper/swiper-bundle.min.js",
    "frontend/js/library/frontend.js"
];
if(isset($config) && isset($config['js']) && count($config['js'])) {
        $coreJs = array_merge($coreJs, $config['js']);
    }
@endphp
@foreach($coreJs as $key => $val) 
    <script src={{asset($val)}}></script>
@endforeach

