@extends('layouts.app')

@section('title', '商品の購入')

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">商品の購入</h1>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
            <div class="p-6">
                <div class="md:flex">
                    <div class="md:w-1/3 mb-4 md:mb-0">
                        <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->name }}" class="w-full h-48 object-cover rounded">
                    </div>
                    <div class="md:w-2/3 md:pl-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-2">{{ $item->name }}</h2>
                        <p class="text-2xl font-bold text-gray-800 mb-4">¥{{ number_format($item->price) }}</p>
                        <p class="text-gray-600 mb-4">{{ $item->description }}</p>
                        <p class="text-sm text-gray-500">出品者: {{ $item->seller->name }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">配送先情報</h2>

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('purchase.address.update', $item->id) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">郵便番号</label>
                        <input type="text" name="postal_code" id="postal_code" 
                            value="{{ old('postal_code', auth()->user()->postal_code) }}"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                            required pattern="\d{7}" placeholder="1234567">
                    </div>

                    <div>
                        <label for="prefecture" class="block text-sm font-medium text-gray-700 mb-2">都道府県</label>
                        <input type="text" name="prefecture" id="prefecture" 
                            value="{{ old('prefecture', auth()->user()->prefecture) }}"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                            required>
                    </div>

                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">市区町村</label>
                        <input type="text" name="city" id="city" 
                            value="{{ old('city', auth()->user()->city) }}"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                            required>
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">番地</label>
                        <input type="text" name="address" id="address" 
                            value="{{ old('address', auth()->user()->address) }}"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                            required>
                    </div>

                    <div class="md:col-span-2">
                        <label for="building" class="block text-sm font-medium text-gray-700 mb-2">建物名・部屋番号</label>
                        <input type="text" name="building" id="building" 
                            value="{{ old('building', auth()->user()->building) }}"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-4">
                    <a href="{{ route('item.show', $item->id) }}" 
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold">
                        キャンセル
                    </a>
                    <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold">
                        購入を確定する
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection 