<?php $__env->startSection('title', '購入履歴'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">購入履歴</h1>

    <?php if(session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>

    <?php if($purchases->isEmpty()): ?>
        <div class="bg-gray-100 rounded-lg p-6 text-center">
            <p class="text-gray-600">購入履歴がありません。</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 gap-6">
            <?php $__currentLoopData = $purchases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $purchase): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-start space-x-4">
                        <?php if($purchase->item && $purchase->item->image_path): ?>
                            <img src="<?php echo e(Storage::url($purchase->item->image_path)); ?>" 
                                 alt="<?php echo e($purchase->item->name); ?>" 
                                 class="w-32 h-32 object-cover rounded-lg">
                        <?php else: ?>
                            <div class="w-32 h-32 bg-gray-200 rounded-lg flex items-center justify-center">
                                <span class="text-gray-400">画像なし</span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h2 class="text-xl font-semibold">
                                        <?php if($purchase->item): ?>
                                            <?php echo e($purchase->item->name); ?>

                                        <?php else: ?>
                                            <span class="text-gray-400">商品は削除されました</span>
                                        <?php endif; ?>
                                    </h2>
                                    <p class="text-gray-600">¥<?php echo e(number_format($purchase->price)); ?></p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-block px-3 py-1 rounded-full text-sm
                                        <?php if($purchase->status === 'completed'): ?> 
                                            bg-green-100 text-green-800
                                        <?php elseif($purchase->status === 'pending'): ?>
                                            bg-yellow-100 text-yellow-800
                                        <?php else: ?>
                                            bg-gray-100 text-gray-800
                                        <?php endif; ?>
                                    ">
                                        <?php switch($purchase->status):
                                            case ('completed'): ?>
                                                支払い完了
                                                <?php break; ?>
                                            <?php case ('pending'): ?>
                                                支払い待ち
                                                <?php break; ?>
                                            <?php default: ?>
                                                <?php echo e($purchase->status); ?>

                                        <?php endswitch; ?>
                                    </span>
                                    <p class="text-sm text-gray-500 mt-1">
                                        <?php echo e($purchase->created_at->format('Y年n月j日 H:i')); ?>

                                    </p>
                                </div>
                            </div>

                            <div class="mt-4">
                                <h3 class="font-semibold text-gray-700">配送先</h3>
                                <p class="text-gray-600">
                                    〒<?php echo e($purchase->shipping_postal_code); ?><br>
                                    <?php echo e($purchase->shipping_prefecture); ?><?php echo e($purchase->shipping_city); ?><?php echo e($purchase->shipping_address); ?>

                                    <?php if($purchase->shipping_building): ?>
                                        <br><?php echo e($purchase->shipping_building); ?>

                                    <?php endif; ?>
                                </p>
                            </div>

                            <?php if($purchase->status === 'pending' && $purchase->payment_method === 'konbini'): ?>
                                <div class="mt-4">
                                    <a href="<?php echo e(route('purchase.konbini', ['payment_intent' => $purchase->payment_intent_id])); ?>" 
                                       class="text-blue-500 hover:text-blue-700">
                                        支払い情報を確認する
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="mt-6">
            <?php echo e($purchases->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/profile/purchases.blade.php ENDPATH**/ ?>