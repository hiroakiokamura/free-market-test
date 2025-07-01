<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>フリーマーケット - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="min-h-screen bg-gray-100">
    <header class="bg-white shadow">
        <nav class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="{{ route('home') }}" class="text-xl font-bold text-gray-800">フリーマーケット</a>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('item.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">出品する</a>
                        <a href="{{ route('profile.show') }}" class="text-gray-600 hover:text-gray-800">マイページ</a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-gray-800">ログアウト</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-800">ログイン</a>
                        <a href="{{ route('register') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">新規登録</a>
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

    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <p>&copy; {{ date('Y') }} フリーマーケット. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>

</html>