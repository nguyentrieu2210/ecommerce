<div id="homepage" style="background: {{json_decode($system['general_setting'], true)['colorBackground']}}">
    <!-- Slider -->
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