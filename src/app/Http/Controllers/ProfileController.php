<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * プロフィール画面を表示
     */
    public function show()
    {
        $user = auth()->user();
        return view('profile.show', compact('user'));
    }

    /**
     * プロフィール編集画面を表示
     */
    public function edit()
    {
        return view('profile.edit');
    }

    /**
     * プロフィールを更新
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:8'],
            'address' => ['required', 'string', 'max:255'],
            'building' => ['nullable', 'string', 'max:255'],
            'profile_photo' => ['nullable', 'image', 'max:1024'],
        ]);

        $user = auth()->user();

        if ($request->hasFile('profile_photo')) {
            // 古い画像を削除
            if ($user->profile_photo_path) {
                Storage::delete($user->profile_photo_path);
            }

            // 新しい画像を保存
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
        }

        $user->update([
            'name' => $request->name,
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
        ]);

        return redirect()->route('home')->with('success', 'プロフィールを更新しました。');
    }

    /**
     * 購入した商品一覧を表示
     */
    public function purchases()
    {
        $purchases = Purchase::where('user_id', auth()->id())
            ->with('item')
            ->latest()
            ->paginate(20);
        
        return view('profile.purchases', compact('purchases'));
    }

    /**
     * 出品した商品一覧を表示
     */
    public function sales()
    {
        $items = Item::where('user_id', auth()->id())
            ->latest()
            ->paginate(20);
        
        return view('profile.sales', compact('items'));
    }
} 