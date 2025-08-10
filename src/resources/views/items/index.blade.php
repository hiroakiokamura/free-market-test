@extends('layouts.app')

@section('title', '商品一覧')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div class="flex space-x-4">
            <a href="{{ route('home', ['mode' => 'recommended']) }}" 
               class="px-4 py-2 rounded-md {{ $mode === 'recommended' ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-700' }} hover:opacity-80 transition-opacity">
                おすすめ
            </a>
            @auth
                <a href="{{ route('home', ['mode' => 'mylist']) }}" 
                   class="px-4 py-2 rounded-md {{ $mode === 'mylist' ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-700' }} hover:opacity-80 transition-opacity">
                    マイリスト
                </a>
            @endauth
        </div>
    </div>

    @if($items->isEmpty())
        <div class="text-center py-8">
            <p class="text-gray-600">検索結果が見つかりません</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($items as $item)
            <div class="bg-white rounded-lg shadow overflow-hidden relative">
                <a href="{{ route('item.show', $item->id) }}">
                    <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->name }}" class="w-full h-48 object-cover">
                    @if($item->status === 'sold_out' || $item->status === 'sold')
                        <div class="absolute top-0 right-0 bg-red-500 text-white px-3 py-1 m-2 rounded-md">
                            Sold
                        </div>
                    @endif
                    <div class="p-4">
                        <h2 class="text-lg font-semibold text-gray-800 mb-2">{{ $item->name }}</h2>
                        <p class="text-gray-600 mb-2 line-clamp-2">{{ $item->description }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-800">¥{{ number_format($item->price) }}</span>
                            <div class="flex items-center space-x-2">
                                <span class="text-yellow-500">☆ {{ $item->likes_count ?? 0 }}</span>
                                <span class="text-sm text-gray-500">{{ $item->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $items->links() }}
        </div>
    @endif
@endsection 