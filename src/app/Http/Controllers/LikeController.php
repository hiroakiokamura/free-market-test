<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function toggle(Request $request, Item $item)
    {
        $existing_like = Like::where('user_id', Auth::id())
            ->where('item_id', $item->id)
            ->first();

        if ($existing_like) {
            $existing_like->delete();
            $liked = false;
        } else {
            Like::create([
                'user_id' => Auth::id(),
                'item_id' => $item->id
            ]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'likes_count' => $item->fresh()->likes_count
        ]);
    }
}
