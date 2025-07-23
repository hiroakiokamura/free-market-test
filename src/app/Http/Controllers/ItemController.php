<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;

class ItemController extends Controller
{
    /**
     * トップページの商品一覧を表示
     */
    public function index(Request $request)
    {
        $query = Item::latest();

        // 検索キーワードが存在する場合
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        $items = $query->paginate(20);
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
        $item = Item::with(['categories', 'comments.user'])
            ->withCount(['likes', 'comments'])
            ->findOrFail($item_id);
        return view('items.show', compact('item'));
    }

    /**
     * 商品出品画面を表示
     */
    public function create()
    {
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    /**
     * 商品を出品
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand_name' => 'nullable|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:1',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'condition' => 'required|in:new,like_new,good,fair,poor',
            'category_ids' => 'required|array|min:1',
        ]);

        $imagePath = $request->file('image')->store('items', 'public');

        // カテゴリー名を取得
        $categoryNames = $request->category_ids;
        
        // 既存のカテゴリーを取得または新規作成
        $categoryIds = [];
        foreach ($categoryNames as $name) {
            $category = Category::firstOrCreate(['name' => $name]);
            $categoryIds[] = $category->id;
        }

        $item = Item::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'brand_name' => $request->brand_name,
            'description' => $request->description,
            'price' => $request->price,
            'image_path' => $imagePath,
            'condition' => $request->condition,
            'status' => 'on_sale',
        ]);

        // カテゴリーを関連付け
        $item->categories()->attach($categoryIds);

        return redirect()->route('item.show', $item)
            ->with('success', '商品を出品しました。');
    }
} 