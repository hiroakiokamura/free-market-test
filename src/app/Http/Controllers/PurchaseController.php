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
        $user = auth()->user();
        return view('purchase.address', compact('item', 'user'));
    }

    /**
     * 送付先住所を更新
     */
    public function updateAddress(Request $request, $item_id)
    {
        $validated = $request->validate([
            'postal_code' => 'required|string|size:7',
            'prefecture' => 'required|string',
            'city' => 'required|string',
            'address' => 'required|string',
            'building' => 'nullable|string',
        ]);

        $user = auth()->user();
        $user->update([
            'postal_code' => $validated['postal_code'],
            'prefecture' => $validated['prefecture'],
            'city' => $validated['city'],
            'address' => $validated['address'],
            'building' => $validated['building'],
        ]);

        return redirect()->route('purchase.show', $item_id)
            ->with('success', '送付先住所を更新しました。');
    }
} 