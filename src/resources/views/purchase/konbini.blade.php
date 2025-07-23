@extends('layouts.app')

@section('title', 'コンビニ支払い情報')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6">コンビニでのお支払い方法</h1>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-8">
            <p class="text-yellow-800">
                以下の支払い情報を控えて、コンビニの店頭でお支払いください。
                支払期限までにお支払いがない場合、注文はキャンセルされます。
            </p>
        </div>

        <div class="space-y-6">
            <div>
                <h2 class="text-lg font-semibold mb-2">支払い番号</h2>
                <p class="bg-gray-50 p-4 rounded text-lg font-mono">
                    {{ $payment->next_action->konbini_display_details->payment_code }}
                </p>
            </div>

            <div>
                <h2 class="text-lg font-semibold mb-2">支払い期限</h2>
                <p class="bg-gray-50 p-4 rounded">
                    {{ \Carbon\Carbon::parse($payment->next_action->konbini_display_details->expires_at)->format('Y年m月d日 H:i') }}まで
                </p>
            </div>

            <div>
                <h2 class="text-lg font-semibold mb-2">支払い金額</h2>
                <p class="bg-gray-50 p-4 rounded text-lg font-bold">
                    ¥{{ number_format($purchase->price) }}
                </p>
            </div>

            <div class="border-t pt-6">
                <h2 class="text-lg font-semibold mb-4">お支払い可能なコンビニエンスストア</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-gray-50 rounded text-center">
                        <p>ローソン</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded text-center">
                        <p>ファミリーマート</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded text-center">
                        <p>セイコーマート</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded text-center">
                        <p>ミニストップ</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8">
            <a href="{{ route('profile.purchases') }}" 
               class="block w-full bg-blue-500 hover:bg-blue-600 text-white text-center px-6 py-3 rounded-lg font-semibold">
                購入履歴へ戻る
            </a>
        </div>
    </div>
</div>
@endsection 