<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>フリーマーケット - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-white">
    <header class="bg-black text-white">
        <nav class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <!-- ロゴ -->
                <a href="{{ route('home') }}" class="shrink-0 flex items-center">
                    <img src="{{ asset('images/logo.svg') }}" alt="COACHTECH" class="h-8">
                </a>

                <!-- 検索バー -->
                <div class="flex-1 max-w-2xl mx-8">
                    <form action="{{ route('home') }}" method="GET" class="relative">
                        <input type="text" 
                               name="search" 
                               placeholder="なにをお探しですか？" 
                               class="w-full px-4 py-1 border border-gray-300 rounded focus:outline-none focus:border-gray-400 text-black placeholder-gray-500"
                               value="{{ request('search') }}">
                    </form>
                </div>

                <!-- ナビゲーションリンク -->
                <div class="flex items-center gap-10">
                    @auth
                        <a href="{{ route('logout') }}" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                           class="text-white hover:text-gray-300 px-2">
                            ログアウト
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                        <a href="{{ route('profile.show') }}" class="text-white hover:text-gray-300 px-2">マイページ</a>
                        <a href="{{ route('item.create') }}" class="bg-white text-black px-6 py-2 rounded hover:bg-gray-100 ml-2">出品</a>
                    @else
                        <a href="{{ route('login') }}" class="text-white hover:text-gray-300 px-2">ログイン</a>
                        <a href="{{ route('register') }}" class="text-white hover:text-gray-300 px-2">新規登録</a>
                    @endauth
                </div>
            </div>
        </nav>
    </header>

    <main class="container mx-auto px-4 py-8">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>

</html>