@extends('layouts.app')

@section('title', 'マイページ')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- プロフィール部分 -->
        <div class="bg-white rounded-lg p-6 mb-8">
            <div class="flex justify-between items-center mb-8">
                <div class="flex items-center space-x-4">
                    <div class="w-24 h-24 rounded-full overflow-hidden bg-gray-200">
                        <img src="{{ auth()->user()->profile_photo_url ?? asset('images/default-profile.png') }}" 
                             alt="プロフィール画像" class="w-full h-full object-cover">
                    </div>
                    <h2 class="text-xl">ユーザー名</h2>
                </div>
                <a href="{{ route('profile.edit') }}" class="text-red-500 border border-red-500 rounded-full px-6 py-2 hover:bg-red-50">
                    プロフィールを編集
                </a>
            </div>
        </div>

        <!-- タブ切り替え -->
        <div class="border-b border-gray-200 mb-8">
            <div class="flex">
                <button onclick="showSales()" id="salesBtn" class="px-8 py-4 text-lg border-b-2 border-transparent hover:border-gray-300">
                    出品した商品
                </button>
                <button onclick="showPurchases()" id="purchasesBtn" class="px-8 py-4 text-lg border-b-2 border-transparent hover:border-gray-300">
                    購入した商品
                </button>
            </div>
        </div>

        <!-- 商品一覧表示エリア -->
        <div>
            <!-- 出品した商品一覧 -->
            <div id="salesList" class="hidden">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach(auth()->user()->items as $item)
                        <div class="bg-white rounded-lg overflow-hidden">
                            <a href="{{ route('item.show', $item->id) }}" class="block">
                                <div class="aspect-w-1 aspect-h-1">
                                    <img src="{{ Storage::url($item->image_path) }}" 
                                         alt="{{ $item->name }}" 
                                         class="w-full h-64 object-cover">
                                </div>
                                <div class="p-4">
                                    <h4 class="text-base text-gray-900">{{ $item->name }}</h4>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 購入した商品一覧 -->
            <div id="purchasesList" class="hidden">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach(auth()->user()->purchases as $purchase)
                        <div class="bg-white rounded-lg overflow-hidden">
                            <a href="{{ route('item.show', $purchase->item->id) }}" class="block">
                                <div class="aspect-w-1 aspect-h-1">
                                    <img src="{{ Storage::url($purchase->item->image_path) }}" 
                                         alt="{{ $purchase->item->name }}" 
                                         class="w-full h-64 object-cover">
                                </div>
                                <div class="p-4">
                                    <h4 class="text-base text-gray-900">{{ $purchase->item->name }}</h4>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showSales() {
    document.getElementById('salesList').classList.remove('hidden');
    document.getElementById('purchasesList').classList.add('hidden');
    document.getElementById('salesBtn').classList.add('border-red-500', 'text-red-500');
    document.getElementById('purchasesBtn').classList.remove('border-red-500', 'text-red-500');
}

function showPurchases() {
    document.getElementById('purchasesList').classList.remove('hidden');
    document.getElementById('salesList').classList.add('hidden');
    document.getElementById('purchasesBtn').classList.add('border-red-500', 'text-red-500');
    document.getElementById('salesBtn').classList.remove('border-red-500', 'text-red-500');
}

// 初期表示時は出品商品一覧を表示
document.addEventListener('DOMContentLoaded', function() {
    showSales();
});
</script>
@endpush

@endsection 