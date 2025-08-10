<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Purchase;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;

class PurchaseController extends Controller
{
    /**
     * 商品購入画面を表示
     */
    public function show($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        // デバッグ情報をログに出力
        \Log::info('User address info:', [
            'user_id' => $user->id,
            'postal_code' => $user->postal_code,
            'city' => $user->city,
            'address' => $user->address,
            'building' => $user->building,
        ]);

        // 住所が設定されているかチェック
        if (empty($user->postal_code) || empty($user->address)) {
            return redirect()->route('profile.edit')->with('error', '配送先住所を設定してください');
        }

        $address = $user->address;

        return view('purchase.show', compact('item', 'address'));
    }

    /**
     * 送付先住所変更画面を表示
     */
    public function showAddress($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();
        
        // 現在の住所を結合（nullの場合は空文字を使用）
        $currentAddress = ($user->prefecture ?? '') . ($user->city ?? '') . ($user->address ?? '');
        
        return view('purchase.address', compact('item', 'currentAddress'));
    }

    /**
     * 送付先住所を更新
     */
    public function updateAddress(Request $request, $item_id)
    {
        $validated = $request->validate([
            'postal_code' => 'required|string|size:7',
            'address' => 'required|string',
            'building' => 'nullable|string',
        ]);

        $user = auth()->user();
        $user->update([
            'postal_code' => $validated['postal_code'],
            'address' => $validated['address'],
            'building' => $validated['building'],
        ]);

        // 更新後のユーザー情報を再取得
        $user->refresh();

        return redirect()->route('purchase.show', $item_id)
            ->with('success', '送付先住所を更新しました。');
    }

    /**
     * 決済処理を実行
     */
    public function process(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        // 自分の商品かチェック（最初にチェック）
        if ($item->user_id == $user->id) {
            abort(403, '自分の商品は購入できません。');
        }

        // 商品が既に売れているかチェック
        if ($item->status === 'sold' || $item->status === 'sold_out') {
            abort(403, 'この商品は既に売り切れです。');
        }

        // 住所が設定されているかチェック
        if (empty($user->postal_code) || empty($user->address)) {
            return redirect()->back()->with('error', '配送先住所を設定してください');
        }

        // メールアドレスの形式を確認
        if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()
                ->with('error', '有効なメールアドレスが設定されていません。プロフィールから正しいメールアドレスを設定してください。');
        }

        // 在庫確認
        if (!$item->isOnSale()) {
            return redirect()->back()->with('error', 'この商品は既に売り切れです。');
        }

        try {
            // 金額を整数に変換（小数点以下を削除）
            $amount = (int)$item->price;

            \Log::info('Stripe決済開始: ', [
                'payment_method' => $request->payment_method,
                'amount' => $amount,
                'original_price' => $item->price,
                'user_id' => $user->id,
                'item_id' => $item->id,
                'user_email' => $user->email
            ]);

            Stripe::setApiKey(config('services.stripe.secret'));
            \Log::info('Stripe APIキー設定完了');

            if ($request->payment_method === 'card') {
                // カード決済の場合
                if (!$request->payment_method_id) {
                    return redirect()->back()->with('error', 'カード情報が正しく送信されませんでした。');
                }

                $payment = PaymentIntent::create([
                    'amount' => $amount,
                    'currency' => 'jpy',
                    'automatic_payment_methods' => [
                        'enabled' => true,
                        'allow_redirects' => 'never'
                    ],
                    'payment_method' => $request->payment_method_id,
                    'confirm' => true,
                    'return_url' => route('purchase.complete', $item->id),
                    'metadata' => [
                        'item_id' => $item->id,
                        'user_id' => $user->id
                    ]
                ]);

                \Log::info('カード決済PaymentIntent作成完了', [
                    'payment_intent_id' => $payment->id,
                    'status' => $payment->status
                ]);
            } else {
                // コンビニ決済の場合
                \Log::info('コンビニ決済処理開始');
                
                // 名前を適切なフォーマットに変換（全角スペースを半角に）
                $userName = str_replace('　', ' ', $user->name);
                
                $paymentData = [
                    'amount' => $amount,
                    'currency' => 'jpy',
                    'payment_method_types' => ['konbini'],
                    'payment_method_data' => [
                        'type' => 'konbini',
                        'billing_details' => [
                            'email' => trim($user->email),
                            'name' => trim($userName)
                        ]
                    ],
                    'confirm' => true,
                    'return_url' => route('purchase.complete', $item->id),
                    'metadata' => [
                        'item_id' => $item->id,
                        'user_id' => $user->id
                    ]
                ];
                \Log::info('コンビニ決済パラメータ: ', $paymentData);

                $payment = PaymentIntent::create($paymentData);
                \Log::info('PaymentIntent作成完了: ', ['payment_id' => $payment->id]);
            }

            // 購入情報を保存
            $purchase = Purchase::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
                'price' => $item->price,
                'shipping_postal_code' => $user->postal_code ?? null,
                'shipping_address' => $user->address ?? null,
                'shipping_building' => $user->building,  // buildingは元々nullable
                'payment_intent_id' => $payment->id,
                'payment_method' => $request->payment_method,
                'status' => $request->payment_method === 'card' ? 'completed' : 'pending'
            ]);

            // 商品を売り切れ状態に更新
            $item->update(['status' => 'sold']);

            if ($request->payment_method === 'konbini') {
                // コンビニ決済の場合は購入履歴画面へ
                return redirect()->route('profile.purchases')
                               ->with('success', 'コンビニ決済の支払い情報が発行されました。');
            }

            return redirect()->route('profile.purchases')
                           ->with('success', '商品の購入が完了しました。');

        } catch (ApiErrorException $e) {
            \Log::error('Stripeエラー詳細: ', [
                'message' => $e->getMessage(),
                'code' => $e->getStripeCode(),
                'http_status' => $e->getHttpStatus(),
                'request_id' => $e->getRequestId(),
                'error' => $e->getError()
            ]);
            return redirect()->back()
                           ->with('error', '決済処理中にエラーが発生しました：' . $e->getMessage());
        } catch (\Exception $e) {
            \Log::error('決済エラー詳細: ', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                           ->with('error', '決済処理中にエラーが発生しました：' . $e->getMessage());
        }
    }

    /**
     * 決済完了処理
     */
    public function complete(Request $request, $item_id)
    {
        try {
            $payment_intent_id = $request->query('payment_intent');
            if ($payment_intent_id) {
                Stripe::setApiKey(config('services.stripe.secret'));
                $payment_intent = PaymentIntent::retrieve($payment_intent_id);
                
                if ($payment_intent->status === 'succeeded') {
                    // 購入情報のステータスを更新
                    Purchase::where('payment_intent_id', $payment_intent_id)
                           ->update(['status' => 'completed']);
                           
                    return redirect()->route('profile.purchases')
                                   ->with('success', '商品の購入が完了しました。');
                }
            }
            
            return redirect()->route('purchase.show', $item_id)
                           ->with('error', '決済処理が完了していません。');
                           
        } catch (\Exception $e) {
            \Log::error('決済完了処理エラー: ', [
                'message' => $e->getMessage(),
                'payment_intent_id' => $payment_intent_id ?? null
            ]);
            
            return redirect()->route('purchase.show', $item_id)
                           ->with('error', '決済処理の確認中にエラーが発生しました。');
        }
    }

    /**
     * コンビニ決済の支払い情報を表示
     */
    public function showKonbini(Request $request)
    {
        $payment = session('payment');
        $purchase = session('purchase');

        if (!$payment || !$purchase) {
            return redirect()->route('home')
                ->with('error', '支払い情報が見つかりませんでした。');
        }

        // セッションから取得したデータを適切なオブジェクトに変換
        $payment = json_decode(json_encode($payment));
        $purchase = json_decode(json_encode($purchase));

        return view('purchase.konbini', [
            'payment' => $payment,
            'purchase' => $purchase
        ]);
    }

    /**
     * 住所文字列を都道府県、市区町村、番地に分割
     */
    private function parseAddress($address)
    {
        // 都道府県のリスト
        $prefectures = [
            '北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県',
            '茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県',
            '新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県', '岐阜県',
            '静岡県', '愛知県', '三重県', '滋賀県', '京都府', '大阪府', '兵庫県',
            '奈良県', '和歌山県', '鳥取県', '島根県', '岡山県', '広島県', '山口県',
            '徳島県', '香川県', '愛媛県', '高知県', '福岡県', '佐賀県', '長崎県',
            '熊本県', '大分県', '宮崎県', '鹿児島県', '沖縄県'
        ];

        // 都道府県を検出
        $prefecture = '';
        foreach ($prefectures as $pref) {
            if (strpos($address, $pref) === 0) {
                $prefecture = $pref;
                break;
            }
        }

        // 都道府県を除去
        $remainingAddress = trim(substr($address, strlen($prefecture)));

        // 市区町村と番地を分割（最初の数字が出てくる位置で分割）
        $city = '';
        $street = '';
        if (preg_match('/^(.+?)([0-9].*)$/', $remainingAddress, $matches)) {
            $city = trim($matches[1]);
            $street = trim($matches[2]);
        } else {
            // 数字が見つからない場合は全て市区町村として扱う
            $city = $remainingAddress;
        }

        return [
            'prefecture' => $prefecture,
            'city' => $city,
            'street' => $street,
        ];
    }
} 