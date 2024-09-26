<div class="swiper-container slider-banner" data-number="2"  style="margin-bottom: {{$marginBottom}}px;">
    @php
        $keywordModuleBanner = json_decode($system['module_banner'], true)['slide'];
        $banner = setupData($slides)[$keywordModuleBanner];
    @endphp
    <div class="swiper-wrapper">
        @foreach($banner->item as $key => $item)
            <div class="swiper-slide item"><a target="{{$item['window'] == 'on' ? '_blank' : '_self'}}" href="{{setupUrl($item['canonical'])}}"><img
                src="{{asset($item['image'])}}"
                class="d-block w-100" alt="{{$item['alt']}}"></a></div>
        @endforeach
    </div>
    <input type="hidden" class="settingSlide" value="{{json_encode($banner['setting'])}}">
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
</div>