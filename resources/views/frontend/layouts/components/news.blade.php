<div class="col2-tech" style="margin-bottom: {{$marginBottom}}px;}}">
    <!-- 24h công nghệ -->
    @php
        $newsInfo = json_decode($system['module_news'], true);
        $keyWidgetNews = $newsInfo['widget'];
    @endphp
    <div class="tech-new" style="background: {{json_decode($system['module_news'], true)['colorBackground']}}">
        <strong class="name-box" style="color: {{$newsInfo['colorTitleLeft']}}">
            {{$newsInfo['titleLeft']}}
            <a href="{{setupUrl($newsInfo['canonicalTitleRight'])}}" previewlistener="true" style="color: {{$newsInfo['colorTitleRight']}}">{{$newsInfo['titleRight']}}</a>
        </strong>
        <ul class="owl-carousel-news owl-carousel">
            @foreach($dataByWidgets[$keyWidgetNews] as $widgetNews)
            <li class="item w100">
                <a href="{{setupUrl($widgetNews->canonical)}}" previewlistener="true">
                    <img data-src="{{$widgetNews->image}}"
                        class=" lazyloaded"
                        alt="{{$widgetNews->name}}"
                        width="270" height="151"
                        src="{{asset($widgetNews->image)}}">
                    <div class="text-tech">
                        <span style="color: {{$newsInfo['colorText']}}">
                            {{$widgetNews->name}}
                        </span>
                    </div>
                </a>
            </li>
            @endforeach
        </ul>
    </div>

    <!-- End -->
    <!-- Banner cộng tác viên -->

    <a class="bg-ctv" aria-label="slide" data-cate="0" data-place="2006" rel="follow"
        href="{{setupUrl($newsInfo['canonical'])}}"
        previewlistener="true"><img width="285" height="350" loading="lazy"
            class=" lazyloaded br10" data-src="{{asset($newsInfo['banner'])}}"
            alt="Bán hàng Nội Bộ" src="{{asset($newsInfo['banner'])}}"></a>
    <!-- End -->
</div>