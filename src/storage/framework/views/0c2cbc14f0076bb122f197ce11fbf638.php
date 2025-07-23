<?php $__env->startSection('title', $item->name); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function toggleLike(itemId) {
        fetch(`/items/${itemId}/like`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            const likeButton = document.getElementById('like-button');
            const likeCount = document.getElementById('like-count');
            
            if (data.liked) {
                likeButton.classList.add('text-yellow-500');
                likeButton.classList.remove('text-gray-400');
            } else {
                likeButton.classList.remove('text-yellow-500');
                likeButton.classList.add('text-gray-400');
            }
            
            likeCount.textContent = data.likes_count;
        })
        .catch(error => console.error('Error:', error));
    }
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-5xl mx-auto">
        <!-- フラッシュメッセージ -->
        <?php if(session('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo e(session('success')); ?></span>
            </div>
        <?php endif; ?>

        <div class="md:flex md:space-x-16">
            <!-- 商品画像 -->
            <div class="md:w-1/2 mb-12 md:mb-0">
                <div class="bg-gray-100 aspect-square flex items-center justify-center rounded-lg">
                    <img src="<?php echo e(Storage::url($item->image_path)); ?>" alt="<?php echo e($item->name); ?>" class="max-h-full max-w-full object-contain p-4">
                </div>
            </div>

            <!-- 商品情報 -->
            <div class="md:w-1/2">
                <!-- 商品名とブランド名 -->
                <h1 class="text-2xl font-bold mb-4"><?php echo e($item->name); ?></h1>
                <?php if($item->brand_name): ?>
                    <p class="text-gray-600 text-sm mb-8"><?php echo e($item->brand_name); ?></p>
                <?php endif; ?>

                <!-- カテゴリー -->
                <div class="flex flex-wrap gap-2 mb-8">
                    <?php if($item->categories->isEmpty()): ?>
                        <p class="text-gray-500">カテゴリーが設定されていません</p>
                    <?php else: ?>
                        <?php $__currentLoopData = $item->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="inline-block px-4 py-1 border border-red-500 text-red-500 bg-red-50 rounded-full text-sm">
                                <?php echo e($category->name); ?>

                            </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>

                <!-- 価格 -->
                <p class="text-3xl font-bold mb-8">¥<?php echo e(number_format($item->price)); ?> <span class="text-sm font-normal">(税込)</span></p>

                <!-- お気に入りとコメント数 -->
                <div class="flex items-center space-x-6 mb-8">
                    <div class="flex items-center">
                        <?php if(auth()->guard()->check()): ?>
                            <button onclick="toggleLike(<?php echo e($item->id); ?>)" 
                                    id="like-button" 
                                    class="text-2xl transition-colors duration-200 <?php echo e($item->isLikedBy(auth()->user()) ? 'text-yellow-500' : 'text-gray-400'); ?> hover:text-yellow-500">
                                ☆
                            </button>
                        <?php else: ?>
                            <a href="<?php echo e(route('login')); ?>" class="text-2xl text-gray-400 hover:text-yellow-500">☆</a>
                        <?php endif; ?>
                        <span class="ml-1" id="like-count"><?php echo e($item->likes_count); ?></span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-2xl">💬</span>
                        <span class="ml-1"><?php echo e($item->comments_count); ?></span>
                    </div>
                </div>

                <!-- 購入ボタン -->
                <?php if($item->isOnSale()): ?>
                    <?php if(auth()->check()): ?>
                        <?php if(auth()->id() !== $item->user_id): ?>
                            <a href="<?php echo e(route('purchase.show', $item->id)); ?>" 
                               class="block w-full bg-red-500 hover:bg-red-600 text-white text-center px-6 py-3 rounded-lg font-semibold mb-8">
                                購入手続きへ
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" 
                           class="block w-full bg-red-500 hover:bg-red-600 text-white text-center px-6 py-3 rounded-lg font-semibold mb-8">
                            ログインして購入
                        </a>
                    <?php endif; ?>
                <?php elseif($item->status === 'sold_out'): ?>
                    <button class="block w-full bg-gray-500 text-white text-center px-6 py-3 rounded-lg font-semibold cursor-not-allowed mb-8" disabled>
                        売り切れ
                    </button>
                <?php endif; ?>

                <!-- 商品説明 -->
                <div class="mb-8">
                    <h2 class="font-bold mb-2">商品説明</h2>
                    <p class="whitespace-pre-line text-gray-700"><?php echo e($item->description); ?></p>
                </div>

                <!-- 商品の情報 -->
                <div class="mb-8">
                    <h2 class="font-bold mb-2">商品の情報</h2>
                    <div class="space-y-2">
                        <div class="flex">
                            <span class="w-24 text-gray-600">商品の状態</span>
                            <span><?php echo e($item->getConditionLabel()); ?></span>
                        </div>
                    </div>
                </div>

                <!-- コメントセクション -->
                <div>
                    <h2 class="font-bold mb-4">コメント(<?php echo e($item->comments_count); ?>)</h2>
                    
                    <!-- コメント一覧 -->
                    <div class="mb-4">
                        <?php $__empty_1 = true; $__currentLoopData = $item->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="mb-4">
                                <div class="bg-gray-100 p-3 rounded-lg">
                                    <p class="font-bold text-sm mb-1"><?php echo e($comment->user->name); ?></p>
                                    <p class="text-gray-700"><?php echo e($comment->content); ?></p>
                                    <p class="text-xs text-gray-500 mt-2"><?php echo e($comment->created_at->format('Y/m/d H:i')); ?></p>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p class="text-gray-500">まだコメントはありません。</p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- コメント入力フォーム -->
                    <?php if(auth()->guard()->check()): ?>
                        <form action="<?php echo e(route('comments.store', $item)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <textarea name="content" rows="4" 
                                      class="w-full border border-gray-300 rounded-lg p-3 mb-3 <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                      placeholder="商品へのコメント（最大255文字）"></textarea>
                            <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-sm mb-3"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <button type="submit" 
                                    class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-3 rounded-lg">
                                コメントを送信する
                            </button>
                        </form>
                    <?php else: ?>
                        <div class="text-center py-4 bg-gray-50 rounded-lg">
                            <p class="text-gray-600">コメントするには<a href="<?php echo e(route('login')); ?>" class="text-red-500 hover:text-red-600">ログイン</a>が必要です。</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/items/show.blade.php ENDPATH**/ ?>