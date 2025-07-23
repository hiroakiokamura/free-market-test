@extends('layouts.app')

@section('title', '住所の変更')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl text-center mb-8">住所の変更</h1>

        <form action="{{ route('purchase.address.update', $item->id) }}" method="POST">
            @csrf

            <!-- 郵便番号 -->
            <div class="mb-6">
                <label for="postal_code" class="block mb-2">郵便番号</label>
                <input type="text" 
                       name="postal_code" 
                       id="postal_code" 
                       value="{{ old('postal_code', auth()->user()->postal_code) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-400">
                @error('postal_code')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- 住所 -->
            <div class="mb-6">
                <label for="address" class="block mb-2">住所</label>
                <input type="text" 
                       name="address" 
                       id="address"
                       value="{{ old('address', $currentAddress) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-400"
                       placeholder="例：渋谷区神南1-2-3"
                       required>
                @error('address')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- 建物名 -->
            <div class="mb-8">
                <label for="building" class="block mb-2">建物名</label>
                <input type="text" 
                       name="building" 
                       id="building"
                       value="{{ old('building', auth()->user()->building) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-400">
                @error('building')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- 更新ボタン -->
            <button type="submit" 
                    class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-3 rounded-lg transition-colors">
                更新する
            </button>
        </form>
    </div>
</div>
@endsection 