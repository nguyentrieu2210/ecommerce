<div class="menu-home" style="background: {{$header['backgroundMenu']}}; color: {{$header['colorMenu']}} !important">
    <div>
        @php
            $keywordMainMenu = json_decode($system['header'], true)['menu'];
            $mainMenu = setupData($menus)[$keywordMainMenu];
        @endphp
        <ul class="main-menu">
            @foreach($mainMenu->links->toTree()->toArray() as $index => $menuItem)
            <li class="{{$menuItem['children'] !== [] ? ($index <= 3 ? 'has-list show-right' : 'has-list show-left') : ''}}">
                <a href="{{setupUrl($menuItem['canonical'])}}" previewlistener="true">
                    @if($menuItem['image'] !== '')
                        <i>
                            <img src="{{$menuItem['image']}}" alt="{{$menuItem['name']}}">
                        </i>
                    @endif
                    <span style="color: {{$header['colorMenu']}}" class="{{$menuItem['children'] !== [] ? 'has-child' : ''}}">{{$menuItem['name']}}</span>
                </a>
                <div class="navmwg ">
                    @if($menuItem['children'] !== []) 
                        @foreach($menuItem['children'] as $menuChildren)
                            <div class="item-child ">
                                <strong>
                                    <a href="{{setupUrl($menuChildren['canonical'])}}">{{$menuChildren['name']}}</a>
                                </strong>
                                @if($menuChildren['children'] !== [])
                                    @foreach($menuChildren['children'] as $menuGrandChildren)
                                        <a href="{{setupUrl($menuGrandChildren['canonical'])}}" previewlistener="true">
                                            <h3>{{$menuGrandChildren['name']}}</h3>
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            </li>
            @endforeach
        </ul>
    </div>
</div>
