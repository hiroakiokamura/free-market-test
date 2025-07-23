<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>フリーマーケット - <?php echo $__env->yieldContent('title'); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>

<body class="min-h-screen bg-white">
    <header class="bg-black text-white">
        <nav class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <!-- ロゴ -->
                <a href="<?php echo e(route('home')); ?>" class="shrink-0 flex items-center">
                    <img src="<?php echo e(asset('images/logo.svg')); ?>" alt="COACHTECH" class="h-8">
                </a>

                <!-- 検索バー -->
                <div class="flex-1 max-w-2xl mx-8">
                    <form action="<?php echo e(route('home')); ?>" method="GET" class="relative">
                        <input type="text" 
                               name="search" 
                               placeholder="なにをお探しですか？" 
                               class="w-full px-4 py-1 border border-gray-300 rounded focus:outline-none focus:border-gray-400 text-black placeholder-gray-500"
                               value="<?php echo e(request('search')); ?>">
                    </form>
                </div>

                <!-- ナビゲーションリンク -->
                <div class="flex items-center gap-10">
                    <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(route('logout')); ?>" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                           class="text-white hover:text-gray-300 px-2">
                            ログアウト
                        </a>
                        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="hidden">
                            <?php echo csrf_field(); ?>
                        </form>
                        <a href="<?php echo e(route('profile.show')); ?>" class="text-white hover:text-gray-300 px-2">マイページ</a>
                        <a href="<?php echo e(route('item.create')); ?>" class="bg-white text-black px-6 py-2 rounded hover:bg-gray-100 ml-2">出品</a>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="text-white hover:text-gray-300 px-2">ログイン</a>
                        <a href="<?php echo e(route('register')); ?>" class="text-white hover:text-gray-300 px-2">新規登録</a>
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

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html><?php /**PATH /var/www/resources/views/layouts/app.blade.php ENDPATH**/ ?>