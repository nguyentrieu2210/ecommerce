<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'E-commerce')</title>
    {{-- <link rel="icon" href="{{asset($system['homepage_favicon'])}}" type="image/x-icon"> --}}
    {{-- head  --}}
    @include('backend.layouts.components.head')
</head>
<body>
    <div id="wrapper">  
        {{-- sidebar  --}}
        @include('backend.layouts.components.sidebar') 
        <div id="page-wrapper" class="gray-bg animated fadeInLeft">
            {{-- header --}}
            @include('backend.layouts.components.header')
            {{-- body  --}}
            @yield('content')
        </div>
        {{-- footer  --}}
        @include('backend.layouts.components.footer')
    </div>

    {{-- script --}}
    @include('backend.layouts.components.script')
</body>

</html>
