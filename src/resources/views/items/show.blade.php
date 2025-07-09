@extends('layouts.app')

@section('title', $item->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-5xl mx-auto">
        <div class="md:flex md:space-x-8">
            <!-- 商品画像 -->
            <div class="md:w-1/2 mb-6 md:mb-0">
                <div class="bg-gray-100 aspect-square flex items-center justify-center rounded-lg">
                    <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->name }}" class="max-h-full max-w-full object-contain">
                </div>
            </div>

            <!-- 商品情報 -->
            <div class="md:w-1/2">
                <!-- 商品名とブランド名 -->
                <h1 class="text-2xl mb-2">{{ $item->name }}</h1>
                @if($item->brand_name)
                    <p class="text-gray-600 text-sm mb-4">{{ $item->brand_name }}</p>
                @endif

                <!-- 価格 -->
                <p class="text-2xl font-bold mb-4">¥{{ number_format($item->price) }} <span class="text-sm font-normal">(税込)</span></p>

                <!-- お気に入りとコメント数 -->
                <div class="flex items-center space-x-4 mb-4">
                    <div class="flex items-center">
                        <span class="text-2xl">☆</span>
                        <span class="ml-1">3</span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-2xl">💬</span>
                        <span class="ml-1">1</span>
                    </div>
                </div>

                <!-- 購入ボタン -->
                @if($item->isOnSale() && auth()->id() !== $item->user_id)
                    <a href="{{ route('purchase.show', $item->id) }}" 
                       class="block w-full bg-red-500 hover:bg-red-600 text-white text-center px-6 py-3 rounded-lg font-semibold mb-8">
                        購入手続きへ
                    </a>
                @elseif($item->status === 'sold_out')
                    <button class="block w-full bg-gray-500 text-white text-center px-6 py-3 rounded-lg font-semibold cursor-not-allowed mb-8" disabled>
                        売り切れ
                    </button>
                @endif

                <!-- 商品説明 -->
                <div class="mb-8">
                    <h2 class="font-bold mb-2">商品説明</h2>
                    <p class="whitespace-pre-line text-gray-700">{{ $item->description }}</p>
                </div>

                <!-- 商品の情報 -->
                <div class="mb-8">
                    <h2 class="font-bold mb-2">商品の情報</h2>
                    <div class="space-y-2">
                        <div class="flex">
                            <span class="w-24 text-gray-600">カテゴリー</span>
                            <div class="flex-1 flex flex-wrap gap-2">
                                @foreach($item->categories as $category)
                                    <span class="inline-block px-3 py-1 bg-red-50 text-red-500 text-sm rounded-full">
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        <div class="flex">
                            <span class="w-24 text-gray-600">商品の状態</span>
                            <span>{{ $item->getConditionLabel() }}</span>
                        </div>
                    </div>
                </div>

                <!-- コメントセクション -->
                <div>
                    <h2 class="font-bold mb-4">コメント(1)</h2>
                    <!-- コメント一覧 -->
                    <div class="mb-4">
                        <div class="flex items-start space-x-3 mb-4">
                            <div class="w-10 h-10 bg-gray-200 rounded-full flex-shrink-0"></div>
                            <div>
                                <p class="font-bold">admin</p>
                                <p class="bg-gray-100 p-3 rounded-lg">こちらにコメントが入ります。</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- コメント入力フォーム -->
                    <form action="#" method="POST">
                        @csrf
                        <textarea name="comment" rows="4" 
                                  class="w-full border border-gray-300 rounded-lg p-3 mb-3"
                                  placeholder="商品へのコメント"></textarea>
                        <button type="submit" 
                                class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-3 rounded-lg">
                            コメントを送信する
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 