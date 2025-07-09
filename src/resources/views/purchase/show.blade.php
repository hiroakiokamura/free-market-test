@extends('layouts.app')

@section('title', '商品の購入')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- 商品情報 -->
        <div class="flex mb-8">
            <!-- 商品画像 -->
            <div class="w-32 h-32 flex-shrink-0">
                <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->name }}" class="w-full h-full object-cover rounded-lg">
            </div>
            <!-- 商品名と価格 -->
            <div class="ml-4">
                <h1 class="text-xl mb-2">{{ $item->name }}</h1>
                <p class="text-xl">¥ {{ number_format($item->price) }}</p>
            </div>
        </div>

        <!-- 支払い方法 -->
        <div class="mb-8">
            <h2 class="text-lg mb-4">支払い方法</h2>
            <select name="payment_method" class="w-64 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-400">
                <option value="" disabled selected>選択してください</option>
                <option value="convenience_store">コンビニ払い</option>
                <option value="credit_card">カード払い</option>
            </select>
        </div>

        <!-- 配送先 -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg">配送先</h2>
                <a href="{{ route('purchase.address', $item->id) }}" class="text-blue-500 hover:text-blue-600">変更する</a>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <p class="mb-2">〒 {{ auth()->user()->postal_code }}</p>
                <p class="mb-2">{{ auth()->user()->prefecture }}{{ auth()->user()->city }}{{ auth()->user()->address }}</p>
                @if(auth()->user()->building)
                    <p>{{ auth()->user()->building }}</p>
                @endif
            </div>
        </div>

        <!-- 金額情報 -->
        <div class="border border-gray-200 rounded-lg p-4 mb-8">
            <div class="flex justify-between mb-2">
                <span>商品代金</span>
                <span>¥ {{ number_format($item->price) }}</span>
            </div>
            <div class="flex justify-between">
                <span>支払い方法</span>
                <span id="selected-payment">コンビニ払い</span>
            </div>
        </div>

        <!-- 購入ボタン -->
        <form action="{{ route('purchase.address.update', $item->id) }}" method="POST">
            @csrf
            <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-3 rounded-lg transition-colors">
                購入する
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentSelect = document.querySelector('select[name="payment_method"]');
    const selectedPayment = document.getElementById('selected-payment');

    paymentSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        selectedPayment.textContent = selectedOption.text;
    });
});
</script>
@endpush

@endsection 