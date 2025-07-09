<?php $__env->startSection('title', $item->name); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-5xl mx-auto">
        <div class="md:flex md:space-x-8">
            <!-- 商品画像 -->
            <div class="md:w-1/2 mb-6 md:mb-0">
                <div class="bg-gray-100 aspect-square flex items-center justify-center rounded-lg">
                    <img src="<?php echo e(Storage::url($item->image_path)); ?>" alt="<?php echo e($item->name); ?>" class="max-h-full max-w-full object-contain">
                </div>
            </div>

            <!-- 商品情報 -->
            <div class="md:w-1/2">
                <!-- 商品名とブランド名 -->
                <h1 class="text-2xl mb-2"><?php echo e($item->name); ?></h1>
                <?php if($item->brand_name): ?>
                    <p class="text-gray-600 text-sm mb-4"><?php echo e($item->brand_name); ?></p>
                <?php endif; ?>

                <!-- 価格 -->
                <p class="text-2xl font-bold mb-4">¥<?php echo e(number_format($item->price)); ?> <span class="text-sm font-normal">(税込)</span></p>

                <!-- お気に入りとコメント数 -->
                <div class="flex items-center space-x-4 mb-4">
                    <div class="flex items-center">
                        <span class="text-2xl">☆</span>
                        <span class="ml-1">3</span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-2xl">💬</span>
                        <span class="ml-1">1</span>
                    </div>
                </div>

                <!-- 購入ボタン -->
                <?php if($item->isOnSale() && auth()->id() !== $item->user_id): ?>
                    <a href="<?php echo e(route('purchase.show', $item->id)); ?>" 
                       class="block w-full bg-red-500 hover:bg-red-600 text-white text-center px-6 py-3 rounded-lg font-semibold mb-8">
                        購入手続きへ
                    </a>
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
                            <span class="w-24 text-gray-600">カテゴリー</span>
                            <div class="flex-1 flex flex-wrap gap-2">
                                <?php $__currentLoopData = $item->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="inline-block px-3 py-1 bg-red-50 text-red-500 text-sm rounded-full">
                                        <?php echo e($category->name); ?>

                                    </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <div class="flex">
                            <span class="w-24 text-gray-600">商品の状態</span>
                            <span><?php echo e($item->getConditionLabel()); ?></span>
                        </div>
                    </div>
                </div>

                <!-- コメントセクション -->
                <div>
                    <h2 class="font-bold mb-4">コメント(1)</h2>
                    <!-- コメント一覧 -->
                    <div class="mb-4">
                        <div class="flex items-start space-x-3 mb-4">
                            <div class="w-10 h-10 bg-gray-200 rounded-full flex-shrink-0"></div>
                            <div>
                                <p class="font-bold">admin</p>
                                <p class="bg-gray-100 p-3 rounded-lg">こちらにコメントが入ります。</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- コメント入力フォーム -->
                    <form action="#" method="POST">
                        <?php echo csrf_field(); ?>
                        <textarea name="comment" rows="4" 
                                  class="w-full border border-gray-300 rounded-lg p-3 mb-3"
                                  placeholder="商品へのコメント"></textarea>
                        <button type="submit" 
                                class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-3 rounded-lg">
                            コメントを送信する
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/items/show.blade.php ENDPATH**/ ?>