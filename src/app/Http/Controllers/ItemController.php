<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    /**
     * トップページの商品一覧を表示
     */
    public function index()
    {
        $items = Item::latest()->paginate(20);
        return view('items.index', compact('items'));
    }

    /**
     * マイリスト画面を表示
     */
    public function mylist()
    {
        $items = Item::where('user_id', auth()->id())->latest()->paginate(20);
        return view('items.mylist', compact('items'));
    }

    /**
     * 商品詳細画面を表示
     */
    public function show($item_id)
    {
        $item = Item::findOrFail($item_id);
        return view('items.show', compact('item'));
    }

    /**
     * 商品出品画面を表示
     */
    public function create()
    {
        return view('items.create');
    }

    /**
     * 商品を出品
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $item = new Item();
        $item->name = $validated['name'];
        $item->description = $validated['description'];
        $item->price = $validated['price'];
        $item->user_id = auth()->id();
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('items', 'public');
            $item->image_path = $path;
        }

        $item->save();

        return redirect()->route('item.show', $item->id)->with('success', '商品を出品しました。');
    }
} 