   
@php 
$coreJs = [
    "/backend/js/jquery-3.1.1.min.js",
    "/backend/js/bootstrap.min.js",
    // Custom and plugin javascript
    "/backend/js/plugins/pace/pace.min.js",
    // jQuery UI
    "/backend/js/plugins/jquery-ui/jquery-ui.min.js",
    "/backend/js/plugins/select2/select2.full.min.js",
];
if(isset($config) && isset($config['js']) && count($config['js'])) {
    $coreJs = array_merge($coreJs, $config['js']);
}
if(!isset($config) || (isset($config) && $config['method'] !== 'theme')) {
    $coreJs[] = "/backend/js/plugins/metisMenu/jquery.metisMenu.js";
    $coreJs[] = "/backend/js/plugins/slimscroll/jquery.slimscroll.min.js";
    $coreJs[] = "/backend/js/inspinia.js";
}
@endphp
@foreach($coreJs as $key => $val) 
    <script src={{asset($val)}}></script>
@endforeach

