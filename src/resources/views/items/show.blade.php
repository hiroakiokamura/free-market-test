@extends('layouts.app')

@section('title', $item->name)

@section('content')
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="md:flex">
            <div class="md:w-1/2">
                <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->name }}" class="w-full h-96 object-cover">
            </div>
            <div class="md:w-1/2 p-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-4">{{ $item->name }}</h1>
                <p class="text-3xl font-bold text-gray-800 mb-6">¥{{ number_format($item->price) }}</p>
                
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-700 mb-2">商品の説明</h2>
                    <p class="text-gray-600 whitespace-pre-line">{{ $item->description }}</p>
                </div>

                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-700 mb-2">出品者</h2>
                    <p class="text-gray-600">{{ $item->seller->name }}</p>
                </div>

                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-700 mb-2">出品日時</h2>
                    <p class="text-gray-600">{{ $item->created_at->format('Y年n月j日 H:i') }}</p>
                </div>

                @if($item->isOnSale() && auth()->id() !== $item->user_id)
                    <a href="{{ route('purchase.show', $item->id) }}" 
                       class="block w-full bg-blue-500 hover:bg-blue-600 text-white text-center px-6 py-3 rounded-lg font-semibold">
                        購入する
                    </a>
                @elseif($item->status === 'sold_out')
                    <button class="block w-full bg-gray-500 text-white text-center px-6 py-3 rounded-lg font-semibold cursor-not-allowed" disabled>
                        売り切れ
                    </button>
                @endif
            </div>
        </div>
    </div>
@endsection 