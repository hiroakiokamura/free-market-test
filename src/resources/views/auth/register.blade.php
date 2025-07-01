<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>会員登録 - COACHTECH</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-white">
        <header class="bg-black py-4">
            <div class="container mx-auto px-4">
                <img src="{{ asset('images/logo.svg') }}" alt="COACHTECH" class="h-8">
            </div>
        </header>

        <div class="container mx-auto px-4 py-16 max-w-md">
            <h1 class="text-2xl font-bold text-center mb-8">会員登録</h1>
            
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">ユーザー名</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                        required autofocus>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">メールアドレス</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                        required>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">パスワード</label>
                    <input id="password" type="password" name="password"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                        required>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">確認用パスワード</label>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                        required>
                </div>

                <button type="submit" class="w-full bg-[#ff4b4b] hover:bg-[#ff3b3b] text-white py-2 rounded">
                    登録する
                </button>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-[#4b9cff] hover:underline text-sm">
                        ログインはこちら
                    </a>
                </div>
            </form>
        </div>
    </body>
</html>