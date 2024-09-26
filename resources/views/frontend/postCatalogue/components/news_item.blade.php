<a href="{{setupUrl($itemBlog['canonical'])}}" previewlistener="true">
    <div class="tempvideo">
        <img width="100" height="70"
            src="{{asset($itemBlog['image'])}}">
    </div>
    <h3 class="titlecom">
        {{$itemBlog['name']}}
    </h3>
    <div class="timepost margin">
        <div class="user-info"><span>{{$itemBlog['users']['name']}}</span></div>
        <span>{{formatStringToCarbon($itemBlog['created_at'])->diffForHumans()}}</span>
    </div>
</a>