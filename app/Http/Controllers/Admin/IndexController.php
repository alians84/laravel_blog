<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;




class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $posts = Post::whereNull('published_by')->orderBy('created_at')->limit(5)->get();
        $comments = Comment::whereNull('published_by')->orderBy('created_at')->limit(5)->get();
        return view('admin.index', compact('posts', 'comments'));    }
}
