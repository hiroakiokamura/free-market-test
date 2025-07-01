@extends('layouts.app')

@section('title', '商品一覧')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">商品一覧</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($items as $item)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <a href="{{ route('item.show', $item->id) }}">
                    <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->name }}" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h2 class="text-lg font-semibold text-gray-800 mb-2">{{ $item->name }}</h2>
                        <p class="text-gray-600 mb-2 line-clamp-2">{{ $item->description }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-800">¥{{ number_format($item->price) }}</span>
                            <span class="text-sm text-gray-500">{{ $item->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $items->links() }}
    </div>
@endsection 