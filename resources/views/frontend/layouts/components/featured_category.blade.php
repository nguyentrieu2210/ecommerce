<div class="cate-feature"  style="margin-bottom: {{$marginBottom}}px; background: {{json_decode($system['module_featured_category'], true)['colorBackground']}}">
    @php
        $featuredCategory = json_decode($system['module_featured_category'], true);
        $featuredCategories = setupData($widgets)[$featuredCategory['widget']];
    @endphp
    <strong class="name-box" style="color: {{$featuredCategory['colorTitle']}}">
        {{$featuredCategory['title']}}
    </strong>
    <style>
        .box-cate aside h3 span {
            color: {{$featuredCategory['colorText']}};
        }
    </style>
    <div class="box-cate">
        <aside>
            @foreach($dataByWidgets[$featuredCategory['widget']] as $itemData) 
            <h3>
                <a href="{{setupUrl($itemData['canonical'])}}" previewlistener="true">
                    <div class="img-boxcate">
                        <img width="55" height="auto" 
                            data-src="{{$itemData['image']}}" alt="{{$itemData['name']}}"
                            class=" lazyloaded" src="{{asset($itemData['image'])}}">
                    </div>
                    <span>{{$itemData['name']}}</span>
                </a>
            </h3>
            @endforeach
        </aside>
    </div>
</div>