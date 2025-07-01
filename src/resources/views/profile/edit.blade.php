@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold text-center mb-8">プロフィール設定</h1>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- プロフィール画像 -->
            <div class="flex flex-col items-center space-y-4">
                <div class="w-32 h-32 rounded-full bg-gray-200 overflow-hidden">
                    <img id="preview" src="{{ auth()->user()->profile_photo_url ?? asset('images/default-profile.png') }}" 
                         alt="プロフィール画像" class="w-full h-full object-cover">
                </div>
                <label class="inline-flex items-center px-4 py-2 border border-red-300 text-red-500 rounded-md hover:bg-red-50 cursor-pointer">
                    <span>画像を選択する</span>
                    <input type="file" name="profile_photo" class="hidden" accept="image/*" onchange="previewImage(this)">
                </label>
            </div>

            <!-- ユーザー名 -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">ユーザー名</label>
                <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- 郵便番号 -->
            <div>
                <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">郵便番号</label>
                <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', auth()->user()->postal_code) }}"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200">
                @error('postal_code')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- 住所 -->
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">住所</label>
                <input type="text" name="address" id="address" value="{{ old('address', auth()->user()->address) }}"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200">
                @error('address')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- 建物名 -->
            <div>
                <label for="building" class="block text-sm font-medium text-gray-700 mb-1">建物名</label>
                <input type="text" name="building" id="building" value="{{ old('building', auth()->user()->building) }}"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200">
                @error('building')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- 更新ボタン -->
            <div>
                <button type="submit" class="w-full bg-red-500 text-white py-2 px-4 rounded-md hover:bg-red-600 transition duration-200">
                    更新する
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection 