<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Item $item)
    {
        $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $comment = new Comment([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'type' => 'human'
        ]);

        $item->comments()->save($comment);

        return redirect()->back()->with('success', 'コメントを投稿しました。');
    }
}
