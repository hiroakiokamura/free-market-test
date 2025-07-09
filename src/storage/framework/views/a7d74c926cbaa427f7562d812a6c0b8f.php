<?php $__env->startSection('title', '商品を出品'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h2 class="text-xl font-bold text-center mb-8">商品の出品</h2>
        
        <form action="<?php echo e(route('item.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            <!-- 商品画像 -->
            <div class="mb-8">
                <p class="text-base mb-2">商品画像</p>
                <div id="dropArea" class="border-2 border-dashed border-gray-300 rounded-lg p-4 transition-colors duration-200 ease-in-out">
                    <div class="text-center">
                        <input type="file" name="image" id="image" class="hidden" accept="image/*">
                        <div id="preview" class="hidden mb-2">
                            <div class="relative inline-block">
                                <img src="" alt="プレビュー" class="w-24 h-24 object-cover rounded-lg">
                                <button type="button" id="removeImage" class="absolute -top-2 -right-2 bg-white rounded-full p-1 shadow-md hover:bg-gray-100">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div id="uploadPrompt">
                            <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-gray-600 text-sm mb-2">ドラッグ＆ドロップ、または</p>
                            <label for="image" class="cursor-pointer">
                                <span class="inline-block text-red-500 border border-red-500 rounded-full px-4 py-1 text-sm hover:bg-red-50">
                                    画像を選択する
                                </span>
                            </label>
                            <p class="text-gray-500 text-xs mt-2">対応フォーマット: jpg, jpeg, png, gif</p>
                        </div>
                    </div>
                </div>
                <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- 商品の詳細 -->
            <div class="mb-8">
                <h3 class="text-lg border-b pb-1 mb-4">商品の詳細</h3>

                <!-- カテゴリー -->
                <div class="mb-6">
                    <p class="text-base mb-2">カテゴリー（複数選択可）</p>
                    <div class="flex flex-wrap gap-2" id="categoryContainer">
                        <?php $__currentLoopData = [
                            '1' => 'ファッション',
                            '2' => '家電',
                            '3' => 'インテリア',
                            '4' => 'レディース',
                            '5' => 'メンズ',
                            '6' => 'コスメ',
                            '7' => '本',
                            '8' => 'ゲーム',
                            '9' => 'スポーツ',
                            '10' => 'キッチン',
                            '11' => 'ハンドメイド',
                            '12' => 'アクセサリー',
                            '13' => 'おもちゃ',
                            '14' => 'ベビー・キッズ'
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="category-label">
                                <input type="checkbox" name="category_ids[]" value="<?php echo e($value); ?>" class="hidden" 
                                    <?php echo e(is_array(old('category_ids')) && in_array($value, old('category_ids')) ? 'checked' : ''); ?>>
                                <span class="inline-block px-4 py-1 border rounded-full cursor-pointer transition-colors duration-200">
                                    <?php echo e($label); ?>

                                </span>
                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php $__errorArgs = ['category_ids'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- 商品の状態 -->
                <div>
                    <label class="block text-base mb-2">商品の状態</label>
                    <select name="condition" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-400">
                        <option value="" disabled selected>選択してください</option>
                        <option value="new">新品、未使用</option>
                        <option value="like_new">未使用に近い</option>
                        <option value="good">目立った傷や汚れなし</option>
                        <option value="fair">やや傷や汚れあり</option>
                        <option value="poor">傷や汚れあり</option>
                    </select>
                    <?php $__errorArgs = ['condition'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- 商品名と説明 -->
            <div class="mb-8">
                <h3 class="text-lg border-b pb-1 mb-4">商品名と説明</h3>

                <!-- 商品名 -->
                <div class="mb-4">
                    <label class="block text-base mb-2">商品名</label>
                    <input type="text" name="name" value="<?php echo e(old('name')); ?>" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-400">
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- ブランド名 -->
                <div class="mb-4">
                    <label class="block text-base mb-2">ブランド名</label>
                    <input type="text" name="brand_name" value="<?php echo e(old('brand_name')); ?>" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-400">
                    <?php $__errorArgs = ['brand_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- 商品の説明 -->
                <div>
                    <label class="block text-base mb-2">商品の説明</label>
                    <textarea name="description" rows="5" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-400"><?php echo e(old('description')); ?></textarea>
                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- 販売価格 -->
            <div class="mb-8">
                <h3 class="text-lg border-b pb-1 mb-4">販売価格</h3>
                <div>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2">¥</span>
                        <input type="number" name="price" value="<?php echo e(old('price')); ?>" 
                               class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-400">
                    </div>
                    <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- 出品ボタン -->
            <div>
                <button type="submit" class="w-full bg-red-500 text-white py-3 rounded-lg hover:bg-red-600 transition-colors">
                    出品する
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.category-label input[type="radio"]:checked + span {
    background-color: rgb(254 242 242);
    color: rgb(239 68 68);
    border-color: rgb(239 68 68);
}
.category-label:hover span {
    background-color: rgb(254 242 242);
}
</style>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropArea = document.getElementById('dropArea');
    const fileInput = document.getElementById('image');
    const preview = document.getElementById('preview');
    const previewImg = preview.querySelector('img');
    const uploadPrompt = document.getElementById('uploadPrompt');
    const removeButton = document.getElementById('removeImage');

    // ドラッグオーバー時のスタイル
    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropArea.classList.add('bg-gray-50', 'border-red-500');
        });
    });

    // ドラッグリーブ時のスタイル
    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropArea.classList.remove('bg-gray-50', 'border-red-500');
        });
    });

    // ドロップ時の処理
    dropArea.addEventListener('drop', (e) => {
        e.preventDefault();
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            handleFile(file);
        }
    });

    // ファイル選択時の処理
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            handleFile(e.target.files[0]);
        }
    });

    // 削除ボタンの処理
    removeButton.addEventListener('click', () => {
        fileInput.value = '';
        preview.classList.add('hidden');
        uploadPrompt.classList.remove('hidden');
        previewImg.src = '';
    });

    // ファイル処理の共通関数
    function handleFile(file) {
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImg.src = e.target.result;
                preview.classList.remove('hidden');
                uploadPrompt.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    // カテゴリー選択の処理
    const categoryContainer = document.getElementById('categoryContainer');
    const categoryLabels = categoryContainer.querySelectorAll('.category-label');

    categoryLabels.forEach(label => {
        const radio = label.querySelector('input[type="radio"]');
        const span = label.querySelector('span');

        // 初期状態の設定
        if (radio.checked) {
            span.classList.add('bg-red-50', 'text-red-500', 'border-red-500');
        }

        // クリックイベントの処理
        label.addEventListener('click', () => {
            // 全てのスパンからアクティブクラスを削除
            categoryLabels.forEach(otherLabel => {
                const otherSpan = otherLabel.querySelector('span');
                otherSpan.classList.remove('bg-red-50', 'text-red-500', 'border-red-500');
            });

            // クリックされたスパンにアクティブクラスを追加
            span.classList.add('bg-red-50', 'text-red-500', 'border-red-500');
        });

        // ホバー効果
        label.addEventListener('mouseenter', () => {
            if (!radio.checked) {
                span.classList.add('bg-red-50');
            }
        });

        label.addEventListener('mouseleave', () => {
            if (!radio.checked) {
                span.classList.remove('bg-red-50');
            }
        });
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/items/create.blade.php ENDPATH**/ ?>