<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed post
 */
class Comment extends Model
{
    protected $fillable = [
        'user_id',
        'post_id',
        'published_by',
        'content',
    ];
    use HasFactory;
    /**
     * Количество комментриев на странице при пагинации
     */
    protected $perPage = 5;

    /**
     * Выбирать из БД только опубликованные комментарии
     */
    public function scopePublished($builder) {
        return $builder->whereNotNull('published_by');
    }
    public function comments(){
        return $this->belongsTo(Comment::class);
    }
    /**
     * Номер страницы пагинации, на которой расположен комментарий;
     * учитываются все комментарии, в том числе не опубликованные
     */
    public function adminPageNumber(){
        $comments = $this->post->comments()->orderBy('created_at')->get();
        if ($comments->count() == 0) {
            return 1;
        }
        if ($comments->count() <= $this->getPerPage()) {
            return 1;
        }
        foreach ($comments as $i => $comment) {
            if ($this->id == $comment->id) {
                break;
            }
        }
        return (int) ceil(($i+1) / $this->getPerPage());
    }
    public function isAuthor() {
        return $this->user->id === auth()->user()->id;
    }
    public function userPageNumber() {
        // все опубликованные комментарии других пользователей
        $others = $this->post->comments()->published();
        // и не опубликованные комментарии этого пользователя
        $comments = $this->post->comments()
            ->whereUserId(auth()->user()->id)
            ->whereNull('published_by')
            ->union($others)
            ->orderBy('created_at')
            ->get();
        if ($comments->count() == 0) {
            return 1;
        }
        if ($comments->count() <= $this->getPerPage()) {
            return 1;
        }
        foreach ($comments as $i => $comment) {
            if ($this->id == $comment->id) {
                break;
            }
        }
        return (int) ceil(($i+1) / $this->getPerPage());
    }
}
