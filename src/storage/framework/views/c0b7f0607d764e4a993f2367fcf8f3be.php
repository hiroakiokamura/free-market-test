<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold text-center mb-8">プロフィール設定</h1>

        <form action="<?php echo e(route('profile.update')); ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <!-- プロフィール画像 -->
            <div class="flex flex-col items-center space-y-4">
                <div class="w-32 h-32 rounded-full bg-gray-200 overflow-hidden">
                    <img id="preview" src="<?php echo e(auth()->user()->profile_photo_url ?? asset('images/default-profile.png')); ?>" 
                         alt="プロフィール画像" class="w-full h-full object-cover">
                </div>
                <label class="inline-flex items-center px-4 py-2 border border-red-300 text-red-500 rounded-md hover:bg-red-50 cursor-pointer">
                    <span>画像を選択する</span>
                    <input type="file" name="profile_photo" class="hidden" accept="image/*" onchange="previewImage(this)">
                </label>
            </div>

            <!-- ユーザー名 -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">ユーザー名</label>
                <input type="text" name="name" id="name" value="<?php echo e(old('name', auth()->user()->name)); ?>"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200">
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

            <!-- 郵便番号 -->
            <div>
                <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">郵便番号</label>
                <input type="text" name="postal_code" id="postal_code" value="<?php echo e(old('postal_code', auth()->user()->postal_code)); ?>"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200">
                <?php $__errorArgs = ['postal_code'];
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

            <!-- 住所 -->
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">住所</label>
                <input type="text" name="address" id="address" value="<?php echo e(old('address', auth()->user()->address)); ?>"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200">
                <?php $__errorArgs = ['address'];
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

            <!-- 建物名 -->
            <div>
                <label for="building" class="block text-sm font-medium text-gray-700 mb-1">建物名</label>
                <input type="text" name="building" id="building" value="<?php echo e(old('building', auth()->user()->building)); ?>"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200">
                <?php $__errorArgs = ['building'];
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

            <!-- 更新ボタン -->
            <div>
                <button type="submit" class="w-full bg-red-500 text-white py-2 px-4 rounded-md hover:bg-red-600 transition duration-200">
                    更新する
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/profile/edit.blade.php ENDPATH**/ ?>