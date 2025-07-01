<?php $__env->startSection('title', '商品一覧'); ?>

<?php $__env->startSection('content'); ?>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">商品一覧</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <a href="<?php echo e(route('item.show', $item->id)); ?>">
                    <img src="<?php echo e(Storage::url($item->image_path)); ?>" alt="<?php echo e($item->name); ?>" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h2 class="text-lg font-semibold text-gray-800 mb-2"><?php echo e($item->name); ?></h2>
                        <p class="text-gray-600 mb-2 line-clamp-2"><?php echo e($item->description); ?></p>
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-800">¥<?php echo e(number_format($item->price)); ?></span>
                            <span class="text-sm text-gray-500"><?php echo e($item->created_at->diffForHumans()); ?></span>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="mt-6">
        <?php echo e($items->links()); ?>

    </div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/items/index.blade.php ENDPATH**/ ?>