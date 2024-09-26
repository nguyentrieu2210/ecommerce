<div class="breadCrumb breadCrumProduct">
    <ul class="flex items-center gap-1 text-base text-blue">
        <li><a class="home block text-blue" href="/" previewlistener="true">Trang chủ</a></li>
        {!! setupBreadCrumb($breadcrumb) !!}
        <li class="divider">/</li>
        <li class="text-black">{{$nameObject}}</li>
    </ul>
</div>