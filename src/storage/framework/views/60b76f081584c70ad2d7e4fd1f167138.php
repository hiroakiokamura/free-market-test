<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <title>会員登録 - COACHTECH</title>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    </head>
    <body class="bg-white">
        <header class="bg-black py-4">
            <div class="container mx-auto px-4">
                <img src="<?php echo e(asset('images/logo.svg')); ?>" alt="COACHTECH" class="h-8">
            </div>
        </header>

        <div class="container mx-auto px-4 py-16 max-w-md">
            <h1 class="text-2xl font-bold text-center mb-8">会員登録</h1>
            
            <form method="POST" action="<?php echo e(route('register')); ?>" class="space-y-6">
                <?php echo csrf_field(); ?>
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">ユーザー名</label>
                    <input id="name" type="text" name="name" value="<?php echo e(old('name')); ?>"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                        required autofocus>
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

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">メールアドレス</label>
                    <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                        required>
                    <?php $__errorArgs = ['email'];
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

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">パスワード</label>
                    <input id="password" type="password" name="password"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                        required>
                    <?php $__errorArgs = ['password'];
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

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">確認用パスワード</label>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                        required>
                </div>

                <button type="submit" class="w-full bg-[#ff4b4b] hover:bg-[#ff3b3b] text-white py-2 rounded">
                    登録する
                </button>

                <div class="text-center">
                    <a href="<?php echo e(route('login')); ?>" class="text-[#4b9cff] hover:underline text-sm">
                        ログインはこちら
                    </a>
                </div>
            </form>
        </div>
    </body>
</html><?php /**PATH /var/www/resources/views/auth/register.blade.php ENDPATH**/ ?>