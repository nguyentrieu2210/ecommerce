<a href="{{setupUrl($blog['canonical'])}}" class="linkproduct" previewlistener="true" style="color: {{$blog['colorTitle']}}">{{$blog['title']}}</a>
<ul class="newspromotion">
    @if(count($widgetBlog))
        @foreach($widgetBlog as $itemBlog)
            <li>
                <a href="{{setupUrl($itemBlog['canonical'])}}" previewlistener="true">
                    <img width="380" height="215"
                        data-original="{{asset($itemBlog['image'])}}"
                        src="{{asset($itemBlog['image'])}}"
                        class=" lazyloaded">
                    <h3>{{$itemBlog['name']}}</h3>
                </a>
            </li>
        @endforeach
    @endif
</ul>
