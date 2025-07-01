<?php $__env->startSection('title', '商品を出品'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">商品を出品</h1>

        <form action="<?php echo e(route('item.store')); ?>" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-lg p-6">
            <?php echo csrf_field(); ?>

            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">商品名</label>
                <input type="text" name="name" id="name" value="<?php echo e(old('name')); ?>"
                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                    required>
                <?php $__errorArgs = ['name'];
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

            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">商品の説明</label>
                <textarea name="description" id="description" rows="5"
                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                    required><?php echo e(old('description')); ?></textarea>
                <?php $__errorArgs = ['description'];
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

            <div class="mb-6">
                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">価格</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">¥</span>
                    <input type="number" name="price" id="price" value="<?php echo e(old('price')); ?>"
                        class="w-full pl-8 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                        min="1" required>
                </div>
                <?php $__errorArgs = ['price'];
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

            <div class="mb-6">
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">商品画像</label>
                <input type="file" name="image" id="image" accept="image/*"
                    class="w-full text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                    required>
                <?php $__errorArgs = ['image'];
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

            <div class="flex justify-end">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold">
                    出品する
                </button>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/items/create.blade.php ENDPATH**/ ?>