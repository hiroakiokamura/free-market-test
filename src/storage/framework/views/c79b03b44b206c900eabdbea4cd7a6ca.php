<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>フリーマーケット - <?php echo $__env->yieldContent('title'); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="min-h-screen bg-gray-100">
    <header class="bg-white shadow">
        <nav class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="<?php echo e(route('home')); ?>" class="text-xl font-bold text-gray-800">フリーマーケット</a>
                <div class="flex items-center space-x-4">
                    <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(route('item.create')); ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">出品する</a>
                        <a href="<?php echo e(route('profile.show')); ?>" class="text-gray-600 hover:text-gray-800">マイページ</a>
                        <form action="<?php echo e(route('logout')); ?>" method="POST" class="inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="text-gray-600 hover:text-gray-800">ログアウト</button>
                        </form>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="text-gray-600 hover:text-gray-800">ログイン</a>
                        <a href="<?php echo e(route('register')); ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">新規登録</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>

    <main class="container mx-auto px-4 py-8">
        <?php if(session('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <p>&copy; <?php echo e(date('Y')); ?> フリーマーケット. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>

</html><?php /**PATH /var/www/resources/views/layouts/app.blade.php ENDPATH**/ ?>