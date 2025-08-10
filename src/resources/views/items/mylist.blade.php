@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">マイリスト</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($items as $item)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->name }}" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h2 class="text-lg font-semibold mb-2">{{ $item->name }}</h2>
                    <p class="text-gray-600 mb-2">{{ Str::limit($item->description, 100) }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-gray-800">¥{{ number_format($item->price) }}</span>
                        <div class="flex items-center space-x-2">
                            <span class="text-yellow-500">☆ {{ $item->likes_count ?? 0 }}</span>
                            <span class="text-sm text-gray-500">{{ $item->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <a href="{{ route('item.show', $item->id) }}" class="block mt-4 text-center bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600 transition-colors">
                        詳細を見る
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $items->links() }}
    </div>
</div>
@endsection 