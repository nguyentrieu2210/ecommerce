<div id="slider" class="swiper-container" data-number="1">
    @php
        $keywordModuleSlide = json_decode($system['header'], true)['slide'];
        $slide = setupData($slides)[$keywordModuleSlide];
    @endphp
    <div class="swiper-wrapper">
        @foreach($slide->item as $key => $item)
            <div class="swiper-slide"><a style="cursor: {{$slide['setting']['arrow'] == 'on' ? 'default' : 'pointer'}}" target="{{$item['window'] == 'on' ? '_blank' : '_self'}}" href="{{setupUrl($item['canonical'])}}"><img
                src="{{asset($item['image'])}}"
                class="d-block w-100" alt="{{$item['alt']}}"></a></div>
        @endforeach
    </div>
    <input type="hidden" class="settingSlide" value="{{json_encode($slide['setting'])}}">
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-pagination"></div>
</div>