@extends('frontend.layouts.layout')
@section('content')
    <div id="homepage">
        @php
            $home = json_decode($system['home'], true);
            $modules = [];
            foreach ($system as $key => $item) {
                if(strpos($key, 'module_') !== false) {
                    $modules[$key] = json_decode($item, true);
                }
            }
        @endphp
        @include('frontend.layouts.components.main_slider')

        <div class="container">
            @foreach(json_decode($system['home'], true) as $moduleItem)
                @if($moduleItem['module'] == 'none') 
                    @continue
                @endif
                @include('frontend.layouts.components.'.str_replace('module_', '', $moduleItem['module']), ['marginBottom' => $moduleItem['marginBottom'] ?? 0])
            @endforeach
        </div>
    </div>
@endsection
