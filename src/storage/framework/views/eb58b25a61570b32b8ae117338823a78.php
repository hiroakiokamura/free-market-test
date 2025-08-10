<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">マイリスト</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="<?php echo e(Storage::url($item->image_path)); ?>" alt="<?php echo e($item->name); ?>" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h2 class="text-lg font-semibold mb-2"><?php echo e($item->name); ?></h2>
                    <p class="text-gray-600 mb-2"><?php echo e(Str::limit($item->description, 100)); ?></p>
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-gray-800">¥<?php echo e(number_format($item->price)); ?></span>
                        <div class="flex items-center space-x-2">
                            <span class="text-yellow-500">☆ <?php echo e($item->likes_count ?? 0); ?></span>
                            <span class="text-sm text-gray-500"><?php echo e($item->created_at->diffForHumans()); ?></span>
                        </div>
                    </div>
                    <a href="<?php echo e(route('item.show', $item->id)); ?>" class="block mt-4 text-center bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600 transition-colors">
                        詳細を見る
                    </a>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="mt-6">
        <?php echo e($items->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/items/mylist.blade.php ENDPATH**/ ?>