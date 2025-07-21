@extends('layouts.app')

@section('title', '商品購入')

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('{{ config('services.stripe.key') }}');
    const elements = stripe.elements();

    // 支払い方法の切り替え
    function togglePaymentMethod() {
        const method = document.querySelector('input[name="payment_method"]:checked').value;
        document.getElementById('card-element-container').style.display = method === 'card' ? 'block' : 'none';
        document.getElementById('konbini-container').style.display = method === 'konbini' ? 'block' : 'none';
    }

    // カード決済用のフォーム
    const cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#32325d',
            }
        },
        hidePostalCode: true
    });
    cardElement.mount('#card-element');

    // エラーハンドリング
    cardElement.on('change', function(event) {
        const displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    // フォーム送信時の処理
    const form = document.getElementById('payment-form');
    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;

        const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
        
        if (paymentMethod === 'card') {
            try {
                const result = await stripe.createPaymentMethod({
                    type: 'card',
                    card: cardElement,
                });

                if (result.error) {
                    const errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                    submitButton.disabled = false;
                    return;
                }

                // 支払い方法IDをフォームに追加
                const hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'payment_method_id');
                hiddenInput.setAttribute('value', result.paymentMethod.id);
                form.appendChild(hiddenInput);
            } catch (error) {
                console.error('Error:', error);
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = '決済処理中にエラーが発生しました。';
                submitButton.disabled = false;
                return;
            }
        }

        form.submit();
    });
</script>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">商品購入</h1>

    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">商品情報</h2>
        <div class="flex items-start">
            <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->name }}" class="w-32 h-32 object-cover rounded-lg">
            <div class="ml-4">
                <h3 class="text-lg font-semibold">{{ $item->name }}</h3>
                <p class="text-gray-600">¥{{ number_format($item->price) }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">配送先情報</h2>
        @if(auth()->user()->prefecture && auth()->user()->city && auth()->user()->address)
            <p>〒{{ auth()->user()->postal_code }}</p>
            <p>{{ auth()->user()->prefecture }}{{ auth()->user()->city }}{{ auth()->user()->address }}</p>
            @if(auth()->user()->building)
                <p>{{ auth()->user()->building }}</p>
            @endif
        @else
            <p class="text-red-500">配送先住所が設定されていません</p>
        @endif
        <div class="mt-4">
            <a href="{{ route('purchase.address', $item->id) }}" class="text-blue-500 hover:text-blue-700">
                配送先を変更する
            </a>
        </div>
    </div>

    <form id="payment-form" action="{{ route('purchase.process', $item->id) }}" method="POST" class="bg-white rounded-lg shadow-lg p-6">
        @csrf
        <h2 class="text-xl font-semibold mb-4">支払い方法</h2>

        <div class="mb-4">
            <label class="inline-flex items-center">
                <input type="radio" name="payment_method" value="card" checked onchange="togglePaymentMethod()" class="form-radio">
                <span class="ml-2">クレジットカード</span>
            </label>
            <label class="inline-flex items-center ml-6">
                <input type="radio" name="payment_method" value="konbini" onchange="togglePaymentMethod()" class="form-radio">
                <span class="ml-2">コンビニ決済</span>
            </label>
        </div>

        <div id="card-element-container">
            <div id="card-element" class="mb-4"></div>
            <div id="card-errors" role="alert" class="text-red-500 mb-4"></div>
        </div>

        <div id="konbini-container" style="display: none;">
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">メールアドレス</label>
                <input type="email" name="email" id="email" value="{{ auth()->user()->email }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
        </div>

        <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition duration-200">
            購入する
        </button>
    </form>
</div>
@endsection 