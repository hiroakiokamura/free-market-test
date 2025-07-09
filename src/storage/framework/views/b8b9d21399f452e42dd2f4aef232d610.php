<?php $__env->startSection('title', '住所の変更'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl text-center mb-8">住所の変更</h1>

        <form action="<?php echo e(route('purchase.address.update', $item->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <!-- 郵便番号 -->
            <div class="mb-6">
                <label for="postal_code" class="block mb-2">郵便番号</label>
                <input type="text" 
                       name="postal_code" 
                       id="postal_code" 
                       value="<?php echo e(old('postal_code', auth()->user()->postal_code)); ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-400"
                       required>
                <?php $__errorArgs = ['postal_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- 住所 -->
            <div class="mb-6">
                <label for="address" class="block mb-2">住所</label>
                <input type="text" 
                       name="address" 
                       id="address"
                       value="<?php echo e(old('address', auth()->user()->prefecture . auth()->user()->city . auth()->user()->address)); ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-400"
                       required>
                <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- 建物名 -->
            <div class="mb-8">
                <label for="building" class="block mb-2">建物名</label>
                <input type="text" 
                       name="building" 
                       id="building"
                       value="<?php echo e(old('building', auth()->user()->building)); ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-400">
                <?php $__errorArgs = ['building'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- 更新ボタン -->
            <button type="submit" 
                    class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-3 rounded-lg transition-colors">
                更新する
            </button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/purchase/address.blade.php ENDPATH**/ ?>