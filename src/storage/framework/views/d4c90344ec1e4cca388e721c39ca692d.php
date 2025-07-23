<?php $__env->startSection('title', '商品購入'); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('<?php echo e(config('services.stripe.key')); ?>');
    const elements = stripe.elements();

    // 支払い方法の切り替え
    function togglePaymentMethod() {
        const method = document.querySelector('input[name="payment_method"]:checked').value;
        document.getElementById('card-element-container').style.display = method === 'card' ? 'block' : 'none';
        document.getElementById('konbini-container').style.display = method === 'konbini' ? 'block' : 'none';
    }

    // カード決済用のフォーム
    const cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#32325d',
            }
        },
        hidePostalCode: true
    });
    cardElement.mount('#card-element');

    // エラーハンドリング
    cardElement.on('change', function(event) {
        const displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    // フォーム送信時の処理
    const form = document.getElementById('payment-form');
    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;

        const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
        
        if (paymentMethod === 'card') {
            try {
                const result = await stripe.createPaymentMethod({
                    type: 'card',
                    card: cardElement,
                });

                if (result.error) {
                    const errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                    submitButton.disabled = false;
                    return;
                }

                const hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'payment_method_id');
                hiddenInput.setAttribute('value', result.paymentMethod.id);
                form.appendChild(hiddenInput);
            } catch (error) {
                console.error('Error:', error);
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = '決済処理中にエラーが発生しました。';
                submitButton.disabled = false;
                return;
            }
        }

        form.submit();
    });
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="purchase-container">
            <!-- 左側：商品情報と支払い方法 -->
            <div class="purchase-main">
                <!-- 商品情報 -->
                <div class="bg-white rounded-lg p-6 mb-6">
                    <div class="flex items-start gap-4">
                        <img src="<?php echo e(Storage::url($item->image_path)); ?>" 
                             alt="<?php echo e($item->name); ?>" 
                             class="w-32 h-32 object-cover">
                        <h2 class="text-xl"><?php echo e($item->name); ?></h2>
                    </div>
                </div>

                <!-- 配送先情報 -->
                <div class="bg-white rounded-lg p-6 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg">配送先</h3>
                        <a href="<?php echo e(route('purchase.address', $item->id)); ?>" class="text-red-500 hover:text-red-600">
                            変更する
                        </a>
                    </div>
                    <?php if(!empty($address)): ?>
                        <p><?php echo e($address); ?></p>
                    <?php else: ?>
                        <p class="text-red-500">配送先住所が設定されていません</p>
                    <?php endif; ?>
                </div>

                <!-- 支払い方法選択 -->
                <div class="bg-white rounded-lg p-6">
                    <h3 class="text-lg mb-4">支払い方法</h3>
                    <div class="space-y-4">
                        <label class="block">
                            <input type="radio" name="payment_method" value="card" checked onchange="togglePaymentMethod()" class="mr-2">
                            クレジットカード
                        </label>
                        <div id="card-element-container" class="mt-4 mb-6">
                            <div id="card-element" class="border p-4 rounded-lg"></div>
                            <div id="card-errors" role="alert" class="text-red-500 text-sm mt-2"></div>
                        </div>

                        <label class="block">
                            <input type="radio" name="payment_method" value="konbini" onchange="togglePaymentMethod()" class="mr-2">
                            コンビニ支払い
                        </label>
                        <div id="konbini-container" class="hidden mt-4">
                            <input type="email" name="email" value="<?php echo e(auth()->user()->email); ?>" class="w-full border p-2 rounded-lg">
                        </div>
                    </div>
                </div>
            </div>

            <!-- 右側：購入サマリー -->
            <div class="purchase-sidebar">
                <div class="purchase-summary">
                    <form id="payment-form" action="<?php echo e(route('purchase.process', $item->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="purchase-summary-content">
                            <div class="purchase-price mb-4">
                                <span>商品代金</span>
                                <span class="text-xl font-bold">¥<?php echo e(number_format($item->price)); ?></span>
                            </div>
                            <div class="purchase-price mb-4">
                                <span>支払い方法</span>
                                <span id="selected-payment-method">コンビニ支払い</span>
                            </div>
                            <div class="border-t pt-4">
                                <div class="text-sm text-gray-600 mb-2">配送先</div>
                                <?php if(!empty($address)): ?>
                                    <div class="text-sm">
                                        <p><?php echo e($address); ?></p>
                                    </div>
                                <?php else: ?>
                                    <p class="text-red-500 text-sm">配送先住所が設定されていません</p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <button type="submit" class="purchase-button">
                            購入する
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 支払い方法の表示を更新する関数
    function updateSelectedPaymentMethod() {
        const method = document.querySelector('input[name="payment_method"]:checked').value;
        const displayText = method === 'card' ? 'クレジットカード' : 'コンビニ支払い';
        document.getElementById('selected-payment-method').textContent = displayText;
    }

    // ラジオボタンの変更を監視
    const radioButtons = document.querySelectorAll('input[name="payment_method"]');
    radioButtons.forEach(radio => {
        radio.addEventListener('change', updateSelectedPaymentMethod);
    });

    // 初期表示
    updateSelectedPaymentMethod();
});
</script>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/purchase/show.blade.php ENDPATH**/ ?>