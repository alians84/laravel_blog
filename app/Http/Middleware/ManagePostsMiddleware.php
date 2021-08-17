<?php

namespace App\Http\Middleware;

use App\Models\Category;
use App\Models\Post;
use Closure;
use Illuminate\Http\Request;

class ManagePostsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next,$perm)
    {
        $roots = Category::where('parent_id', 0)->get();
        $posts = Post::orderBy('created_at', 'desc')->paginate();

        return $next($request);
    }
}
