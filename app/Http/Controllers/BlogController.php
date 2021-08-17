<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;

class BlogController extends Controller
{ /**
 * Главная страница блога (список всех постов)
 */
    public function index() {
        $posts = Post::published()
            ->with('user')->with('tags')
            ->orderByDesc('created_at')
            ->paginate();
        return view('blog.index', compact('posts'));
    }
    /**
     * Страница просмотра отдельного поста блога
     */
    public function post(Post $post) {
        $comments = $post->comments()
            ->published()
            ->orderBy('created_at')
            ->paginate();
        return view('blog.post', compact('post', 'comments'));
    }

    /**
     * Список постов блога выбранного автора
     */
    public function author(User $user) {
        $posts = $user->posts()
            ->published()
            ->with('user')->with('tags')
            ->orderByDesc('created_at')
            ->paginate();
        return view('blog.author', compact('user', 'posts'));
    }

    /**
     * Список постов блога с выбранным тегом
     */
    public function tag(Tag $tag) {
        $posts = $tag->posts()
            ->published()
            ->with('user')->with('tags')
            ->orderByDesc('created_at')
            ->paginate();
        return view('blog.tag', compact('tag', 'posts'));
    }
    /**
     * Список постов блога выбранной категории
     */
    public function category(Category $category) {
        $descendants = array_merge(Category::descendants($category->id), [$category->id]);
        $posts = Post::whereIn('category_id', $descendants)
            ->published()
            ->with('user')->with('tags')
            ->orderByDesc('created_at')
            ->paginate();
        return view('blog.category', compact('category', 'posts'));
    }
    /**
     * Сохраняет новый комментарий в базу данных
     */
    public function comment(CommentRequest $request){
        $request -> merge(['user_id'=>auth()->user()->id]);
        $message ='Клмментраий добавлен , будет доступен после проверки';
        if (auth()->user()->hasPermAnyWay('published_by')){
            $request->merge(['published_by'=>auth()->user()->id]);
            $message = 'Комментарий добавлен и уже доступен для просмотра';
        }
        $comment = Comment::create($request->all());
        // комментариев может быть много, поэтому есть пагинация; надо
        // перейти на последнюю страницу — новый комментарий будет там
        $page = $comment->post->comments()->published()->paginate()->lastPage();
        return redirect()
            ->route('blog.post', ['post' => $comment->post->slug, 'page' => $page])
            ->withFragment('comment-list')
            ->with('success', $message);

    }
}
