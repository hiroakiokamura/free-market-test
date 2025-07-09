<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Purchase;

class PurchaseController extends Controller
{
    /**
     * 商品購入画面を表示
     */
    public function show($item_id)
    {
        $item = Item::findOrFail($item_id);
        return view('purchase.show', compact('item'));
    }

    /**
     * 送付先住所変更画面を表示
     */
    public function showAddress($item_id)
    {
        $item = Item::findOrFail($item_id);
        return view('purchase.address', compact('item'));
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

        // 住所を都道府県、市区町村、番地に分割
        $addressParts = $this->parseAddress($validated['address']);

        $user = auth()->user();
        $user->update([
            'postal_code' => $validated['postal_code'],
            'prefecture' => $addressParts['prefecture'],
            'city' => $addressParts['city'],
            'address' => $addressParts['street'],
            'building' => $validated['building'],
        ]);

        return redirect()->route('purchase.show', $item_id)
            ->with('success', '送付先住所を更新しました。');
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
                $address = substr($address, strlen($pref));
                break;
            }
        }

        // 残りの住所を市区町村と番地に分割（簡易的な実装）
        $remainingParts = explode('市', $address, 2);
        if (count($remainingParts) === 2) {
            $city = $remainingParts[0] . '市';
            $street = trim($remainingParts[1]);
        } else {
            // 市で分割できない場合は、最初の数字が出てくる位置で分割
            preg_match('/^([^\d]+)(.*)/u', $address, $matches);
            $city = $matches[1] ?? '';
            $street = $matches[2] ?? $address;
        }

        return [
            'prefecture' => $prefecture,
            'city' => $city,
            'street' => $street,
        ];
    }
} 