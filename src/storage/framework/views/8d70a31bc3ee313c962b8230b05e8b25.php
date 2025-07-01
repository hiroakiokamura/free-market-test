<?php $__env->startSection('title', 'マイページ'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold">マイページ</h1>
                <div class="flex space-x-4">
                    <a href="<?php echo e(route('profile.edit')); ?>" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        プロフィール編集
                    </a>
                    <form action="<?php echo e(route('logout')); ?>" method="POST" class="inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                            ログアウト
                        </button>
                    </form>
                </div>
            </div>

            <div class="flex items-center space-x-6 mb-6">
                <div class="w-32 h-32 rounded-full overflow-hidden">
                    <img src="<?php echo e(auth()->user()->profile_photo_url ?? asset('images/default-profile.png')); ?>" 
                         alt="プロフィール画像" class="w-full h-full object-cover">
                </div>
                <div>
                    <h2 class="text-xl font-semibold"><?php echo e(auth()->user()->name); ?></h2>
                    <p class="text-gray-600"><?php echo e(auth()->user()->email); ?></p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <h3 class="font-semibold text-gray-700">住所情報</h3>
                    <p class="text-gray-600">〒<?php echo e(auth()->user()->postal_code); ?></p>
                    <p class="text-gray-600"><?php echo e(auth()->user()->address); ?></p>
                    <?php if(auth()->user()->building): ?>
                        <p class="text-gray-600"><?php echo e(auth()->user()->building); ?></p>
                    <?php endif; ?>
                </div>

                <div class="border-t pt-4">
                    <h3 class="font-semibold text-gray-700 mb-2">アクティビティ</h3>
                    <div class="flex space-x-4">
                        <a href="<?php echo e(route('profile.purchases')); ?>" class="text-blue-500 hover:underline">購入した商品</a>
                        <a href="<?php echo e(route('profile.sales')); ?>" class="text-blue-500 hover:underline">出品した商品</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/profile/show.blade.php ENDPATH**/ ?>