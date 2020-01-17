<!-- Stored in resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>@yield('title') | 板城管樂團後端系統</title>
        @include('layouts.headerInclude')
        @include('layouts.footerInclude')
    </head>
    <body>
        <div class="header">
            <div class="container">
                <h1><a href="{{ route('index') }}" class="text-inherit">板城管樂團後端系統</a></h1>
                @if (Route::has('login'))
                <div class="login-section">
                        @auth
                            <span><i class="fas fa-user"></i> {{ $user_name }}</span><br>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">登出</a>
                            <form id="logoutForm" method="post" action="{{ route('logout') }}" style="display: none;">@csrf</form>
                        @endauth
                        @guest
                            @if (!Route::is('login'))
                            <a href="{{ route('login') }}"><i class="fas fa-user"></i> 登入</a>
                            @endif
                        @endguest
                </div>
                @endif
            </div>
        </div>
        <div class="container">
            @yield('content')
        </div>
    </body>
</html>