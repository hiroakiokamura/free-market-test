@extends('layouts.app')

@section('title', '商品の出品')

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');
    const selectButton = document.getElementById('image-select-button');
    const previewArea = document.getElementById('image-preview');
    const previewImage = document.getElementById('preview-image');
    const form = document.querySelector('form');

    // 画像選択ボタンのクリックイベント
    selectButton.addEventListener('click', function(e) {
        e.preventDefault();
        imageInput.click();
    });

    // 画像が選択されたときの処理
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewArea.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    // フォーム送信時の処理
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // 必須項目のチェック
        const requiredFields = ['name', 'description', 'price', 'condition'];
        let isValid = true;

        requiredFields.forEach(field => {
            const input = form.querySelector(`[name="${field}"]`);
            if (!input.value) {
                isValid = false;
                const errorElement = document.createElement('p');
                errorElement.className = 'text-red-500 text-sm mt-1';
                errorElement.textContent = 'この項目は必須です';
                input.parentElement.appendChild(errorElement);
            }
        });

        // カテゴリーのチェック
        const selectedCategories = form.querySelectorAll('input[name="category_ids[]"]:checked');
        if (selectedCategories.length === 0) {
            isValid = false;
            const categoryError = document.createElement('p');
            categoryError.className = 'text-red-500 text-sm mt-1';
            categoryError.textContent = 'カテゴリーを1つ以上選択してください';
            form.querySelector('.category-label').parentElement.appendChild(categoryError);
        }

        // 画像のチェック
        if (!imageInput.files[0]) {
            isValid = false;
            const imageError = document.createElement('p');
            imageError.className = 'text-red-500 text-sm mt-1';
            imageError.textContent = '商品画像を選択してください';
            imageInput.parentElement.appendChild(imageError);
        }

        if (isValid) {
            form.submit();
        }
    });
});
</script>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold text-center mb-8">商品の出品</h1>

        <style>
            .category-label input[type="checkbox"]:checked + span {
                background-color: rgb(239 68 68);
                color: white;
            }
            .category-label:hover span {
                background-color: rgb(254 242 242);
            }
        </style>

        <form action="{{ route('item.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- 商品画像 -->
            <div class="mb-8">
                <h2 class="text-base mb-2">商品画像</h2>
                <div class="border border-gray-300 rounded p-4">
                    <input type="file" 
                           name="image" 
                           id="image"
                           accept="image/*"
                           class="hidden"
                           required>
                    
                    <!-- プレビューエリア -->
                    <div id="image-preview" class="hidden mb-4">
                        <img id="preview-image" src="" alt="プレビュー" class="max-w-full h-auto max-h-64 mx-auto">
                    </div>

                    <!-- 画像選択ボタン -->
                    <div class="text-center">
                        <button type="button" 
                                id="image-select-button"
                                class="text-red-500 border border-red-500 rounded-full px-6 py-2 hover:bg-red-50 transition-colors duration-200">
                            画像を選択する
                        </button>
                    </div>
                </div>
                @error('image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- 商品の詳細 -->
            <div class="mb-8">
                <h2 class="text-base mb-4">商品の詳細</h2>

                <!-- カテゴリー -->
                <div class="mb-8">
                    <h3 class="text-base mb-4">カテゴリー</h3>
                    <div class="space-y-4 px-4">
                        <!-- 1行目 -->
                        <div class="flex flex-wrap gap-4">
                            @foreach(['ファッション', '家電', 'インテリア', 'レディース', 'メンズ', 'コスメ'] as $category)
                                <label class="category-label">
                                    <input type="checkbox" 
                                           name="category_ids[]" 
                                           value="{{ $category }}"
                                           class="hidden peer"
                                           {{ in_array($category, old('category_ids', [])) ? 'checked' : '' }}>
                                    <span class="inline-block px-6 py-2 rounded-full border border-red-500 text-red-500 text-sm cursor-pointer
                                               hover:bg-red-50 transition-colors duration-200
                                               peer-checked:bg-red-500 peer-checked:text-white">
                                        {{ $category }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        <!-- 2行目 -->
                        <div class="flex flex-wrap gap-4">
                            @foreach(['本', 'ゲーム', 'スポーツ', 'キッチン', 'ハンドメイド', 'アクセサリー'] as $category)
                                <label class="category-label">
                                    <input type="checkbox" 
                                           name="category_ids[]" 
                                           value="{{ $category }}"
                                           class="hidden peer"
                                           {{ in_array($category, old('category_ids', [])) ? 'checked' : '' }}>
                                    <span class="inline-block px-6 py-2 rounded-full border border-red-500 text-red-500 text-sm cursor-pointer
                                               hover:bg-red-50 transition-colors duration-200
                                               peer-checked:bg-red-500 peer-checked:text-white">
                                        {{ $category }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        <!-- 3行目 -->
                        <div class="flex flex-wrap gap-4">
                            @foreach(['おもちゃ', 'ベビー・キッズ'] as $category)
                                <label class="category-label">
                                    <input type="checkbox" 
                                           name="category_ids[]" 
                                           value="{{ $category }}"
                                           class="hidden peer"
                                           {{ in_array($category, old('category_ids', [])) ? 'checked' : '' }}>
                                    <span class="inline-block px-6 py-2 rounded-full border border-red-500 text-red-500 text-sm cursor-pointer
                                               hover:bg-red-50 transition-colors duration-200
                                               peer-checked:bg-red-500 peer-checked:text-white">
                                        {{ $category }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @error('category_ids')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 商品の状態 -->
                <div class="mb-6">
                    <h3 class="text-base mb-2">商品の状態</h3>
                    <select name="condition" 
                            class="w-full border border-gray-300 rounded p-2"
                            required>
                        <option value="">選択してください</option>
                        <option value="new" {{ old('condition') == 'new' ? 'selected' : '' }}>新品・未使用</option>
                        <option value="like_new" {{ old('condition') == 'like_new' ? 'selected' : '' }}>未使用に近い</option>
                        <option value="good" {{ old('condition') == 'good' ? 'selected' : '' }}>目立った傷や汚れなし</option>
                        <option value="fair" {{ old('condition') == 'fair' ? 'selected' : '' }}>やや傷や汚れあり</option>
                        <option value="poor" {{ old('condition') == 'poor' ? 'selected' : '' }}>傷や汚れあり</option>
                    </select>
                    @error('condition')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- 商品名と説明 -->
            <div class="mb-8">
                <h2 class="text-base mb-4">商品名と説明</h2>

                <!-- 商品名 -->
                <div class="mb-4">
                    <h3 class="text-base mb-2">商品名</h3>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name') }}"
                           class="w-full border border-gray-300 rounded p-2"
                           required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- ブランド名 -->
                <div class="mb-4">
                    <h3 class="text-base mb-2">ブランド名</h3>
                    <input type="text" 
                           name="brand_name" 
                           value="{{ old('brand_name') }}"
                           class="w-full border border-gray-300 rounded p-2">
                    @error('brand_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 商品の説明 -->
                <div class="mb-4">
                    <h3 class="text-base mb-2">商品の説明</h3>
                    <textarea name="description" 
                              rows="5"
                              class="w-full border border-gray-300 rounded p-2"
                              required>{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- 販売価格 -->
            <div class="mb-8">
                <h2 class="text-base mb-2">販売価格</h2>
                <div class="flex items-center">
                    <span class="mr-2">¥</span>
                    <input type="number" 
                           name="price" 
                           value="{{ old('price') }}"
                           class="w-full border border-gray-300 rounded p-2"
                           required>
                </div>
                @error('price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- 出品ボタン -->
            <div class="text-center">
                <button type="submit" class="w-full bg-red-500 text-white py-3 rounded hover:bg-red-600 transition-colors duration-200">
                    出品する
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 