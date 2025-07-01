<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>ログイン</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-white">
        <header class="bg-black py-4">
            <div class="container mx-auto">
                <img src="{{ asset('images/logo.svg') }}" alt="COACHTECH" class="h-8">
            </div>
        </header>

        <div class="container mx-auto px-4 py-16 max-w-md">
            <h1 class="text-2xl font-bold text-center mb-8">ログイン</h1>
            
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm mb-2">メールアドレス</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                        class="w-full border border-gray-300 rounded p-2"
                        required autofocus>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm mb-2">パスワード</label>
                    <input id="password" type="password" name="password"
                        class="w-full border border-gray-300 rounded p-2"
                        required>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-[#ff4b4b] hover:bg-[#ff3b3b] text-white py-2 rounded">
                    ログインする
                </button>

                <div class="text-center">
                    <a href="{{ route('register') }}" class="text-[#4b9cff] hover:underline text-sm">
                        会員登録はこちら
                    </a>
                </div>
            </form>
        </div>
    </body>
</html>