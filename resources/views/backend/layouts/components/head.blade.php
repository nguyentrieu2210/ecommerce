
@php 
    $coreCss = [
        "/backend/css/bootstrap.min.css",
        "/backend/font-awesome/css/font-awesome.css",
        "/backend/css/animate.css",
        "/backend/css/plugins/select2/select2.min.css",
        "/backend/css/style.css",
        
    ];
    if(!isset($config) || (isset($config) && $config['method'] !== 'theme')) {
        $coreCss[] = "/backend/css/customize.css";
    }
    if(isset($config) && isset($config['css']) && count($config['css'])) {
        $coreCss = array_merge($coreCss, $config['css']);
    }
@endphp
<script>
    var BASE_URL = '{{config('apps.general.APP_URL')}}'
</script>
@foreach($coreCss as $key => $val) 
    <link href={{asset($val)}} rel="stylesheet">
@endforeach