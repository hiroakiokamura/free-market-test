<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-6 text-center">メール認証</h2>

        <?php if(session('status') == 'verification-link-sent'): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                新しい認証リンクをメールアドレスに送信しました。
            </div>
        <?php endif; ?>

        <div class="text-gray-600 mb-6">
            <p>
                ご登録ありがとうございます！<br>
                開始する前に、メールアドレスを確認していただく必要があります。<br>
                認証メールが届いていない場合は、再送信をリクエストしてください。
            </p>
        </div>

        <form method="POST" action="<?php echo e(route('verification.send')); ?>" class="mb-4">
            <?php echo csrf_field(); ?>
            <button type="submit" class="w-full bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600 transition-colors">
                認証メールを再送信
            </button>
        </form>

        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="w-full bg-gray-200 text-gray-700 py-2 px-4 rounded hover:bg-gray-300 transition-colors">
                ログアウト
            </button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/auth/verify-email.blade.php ENDPATH**/ ?>