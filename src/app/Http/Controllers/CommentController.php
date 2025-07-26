<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Item;
use App\Http\Requests\CommentRequest;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(CommentRequest $request, Item $item)
    {
        $comment = new Comment([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'type' => 'human'
        ]);

        $item->comments()->save($comment);

        return redirect()->back()->with('success', 'コメントを投稿しました。');
    }
}
