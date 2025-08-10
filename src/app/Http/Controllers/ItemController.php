<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    /**
     * トップページの商品一覧を表示
     */
    public function index(Request $request)
    {
        $query = Item::latest();

        // ログインユーザーの商品を除外
        if (auth()->check()) {
            $query->where('user_id', '!=', auth()->id());
        }

        // 表示モードに応じてクエリを変更
        $mode = $request->get('mode', 'latest');
        switch ($mode) {
            case 'recommended':
                $query->withCount('likes')
                    ->orderByDesc('likes_count')
                    ->orderByDesc('created_at');
                break;
            case 'mylist':
                if (auth()->check()) {
                    $query->whereHas('likes', function($q) {
                        $q->where('user_id', auth()->id());
                    });
                }
                break;
            default:
                // デフォルトは新着順
                break;
        }

        // 検索キーワードが存在する場合
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        $items = $query->paginate(20)->withQueryString();
        return view('items.index', compact('items', 'mode'));
    }

    /**
     * マイリスト画面を表示
     */
    public function mylist()
    {
        $items = Item::whereHas('likes', function($query) {
            $query->where('user_id', auth()->id());
        })->latest()->paginate(20);
        return view('items.mylist', compact('items'));
    }

    /**
     * 商品詳細画面を表示
     */
    public function show($item_id)
    {
        $item = Item::with(['comments.user'])
            ->withCount(['likes', 'comments'])
            ->findOrFail($item_id);
        return view('items.show', compact('item'));
    }

    /**
     * 商品出品画面を表示
     */
    public function create()
    {
        // 予定されたカテゴリー一覧を定義
        $categories = [
            'ファッション',
            '家電・スマホ・カメラ',
            '食品',
            'キッチン・日用品',
            'コスメ・美容',
            'スポーツ・レジャー',
            '本・音楽・ゲーム',
            'その他'
        ];
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
            'category' => 'required|string',
        ]);

        $imagePath = $request->file('image')->store('items', 'public');

        $item = Item::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'brand_name' => $request->brand_name,
            'description' => $request->description,
            'price' => $request->price,
            'image_path' => $imagePath,
            'condition' => $request->condition,
            'category' => $request->category,
            'status' => 'on_sale',
        ]);

        return redirect()->route('item.show', $item)
            ->with('success', '商品を出品しました。');
    }
} 