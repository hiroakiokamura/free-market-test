@extends('layouts.app')

@section('title', '購入履歴')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">購入履歴</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if($purchases->isEmpty())
        <div class="bg-gray-100 rounded-lg p-6 text-center">
            <p class="text-gray-600">購入履歴がありません。</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-6">
            @foreach($purchases as $purchase)
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-start space-x-4">
                        @if($purchase->item && $purchase->item->image_path)
                            <img src="{{ Storage::url($purchase->item->image_path) }}" 
                                 alt="{{ $purchase->item->name }}" 
                                 class="w-32 h-32 object-cover rounded-lg">
                        @else
                            <div class="w-32 h-32 bg-gray-200 rounded-lg flex items-center justify-center">
                                <span class="text-gray-400">画像なし</span>
                            </div>
                        @endif
                        
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h2 class="text-xl font-semibold">
                                        @if($purchase->item)
                                            {{ $purchase->item->name }}
                                        @else
                                            <span class="text-gray-400">商品は削除されました</span>
                                        @endif
                                    </h2>
                                    <p class="text-gray-600">¥{{ number_format($purchase->price) }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-block px-3 py-1 rounded-full text-sm
                                        @if($purchase->status === 'completed') 
                                            bg-green-100 text-green-800
                                        @elseif($purchase->status === 'pending')
                                            bg-yellow-100 text-yellow-800
                                        @else
                                            bg-gray-100 text-gray-800
                                        @endif
                                    ">
                                        @switch($purchase->status)
                                            @case('completed')
                                                支払い完了
                                                @break
                                            @case('pending')
                                                支払い待ち
                                                @break
                                            @default
                                                {{ $purchase->status }}
                                        @endswitch
                                    </span>
                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ $purchase->created_at->format('Y年n月j日 H:i') }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-4">
                                <h3 class="font-semibold text-gray-700">配送先</h3>
                                <p class="text-gray-600">
                                    〒{{ $purchase->shipping_postal_code }}<br>
                                    {{ $purchase->shipping_prefecture }}{{ $purchase->shipping_city }}{{ $purchase->shipping_address }}
                                    @if($purchase->shipping_building)
                                        <br>{{ $purchase->shipping_building }}
                                    @endif
                                </p>
                            </div>

                            @if($purchase->status === 'pending' && $purchase->payment_method === 'konbini')
                                <div class="mt-4">
                                    <a href="{{ route('purchase.konbini', ['payment_intent' => $purchase->payment_intent_id]) }}" 
                                       class="text-blue-500 hover:text-blue-700">
                                        支払い情報を確認する
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $purchases->links() }}
        </div>
    @endif
</div>
@endsection 