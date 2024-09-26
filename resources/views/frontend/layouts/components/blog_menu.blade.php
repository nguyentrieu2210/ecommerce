<div class="blog-menu">
    <style>
        .menu-active{
            background: {{json_decode($system['general_setting'], true)['colorTheme']}}
        }
    </style>
    <section>
        <ul class="menu">
            @foreach($menuBlog->links as $menuItem)
            <li>
                <a class="{{$canonical == $menuItem->canonical ? 'menu-active' : ''}}" href="{{setupUrl($menuItem->canonical)}}" previewlistener="true">{{$menuItem->name}}</a>
            </li>
            @endforeach
        </ul>
    </section>
</div>