<!DOCTYPE html>
<html lang="en">
    <head>
        @include('frontend.layouts.components.head')
    </head>

    <body style="overflow-x: hidden">
        {{-- -----------HEADER----------- --}}
        @include('frontend.layouts.components.header')

        {{-- ------------MENU------------ --}}
        @include('frontend.layouts.components.menu')

        {{-- -----------PAGE-------- --}}
        @yield('content')
        
        {{-- ------------FOOTER---------- --}}
        @include('frontend.layouts.components.footer')

        {{-- -------------SCRIPT------------ --}}
        @include('frontend.layouts.components.scripts')
    </body>
</html>
