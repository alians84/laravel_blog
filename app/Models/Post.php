<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model {
    protected $fillable=[
      'user_id',
      'category_id',
      'name',
      'slug',
      'excerpt',
      'content',
      'image',

    ];

    use HasFactory;
    /**
     * Количество постов на странице при пагинации
     */
    protected $perPage = 5;
    /**
     * Связь модели Post с моделью Tag, позволяет получить
     * все теги поста
     */
    public function tags() {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }
    /**
     * Связь модели Post с моделью Category, позволяет получить
     * родительскую категорию поста
     */
    public function category() {
        return $this->belongsTo(Category::class);
    }
    /**
     * Связь модели Post с моделью User, позволяет получить
     * автора поста
     */
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function comments(){
        return $this->belongsTo(Comment::class);
    }
    public function isVisible() {
        return ! is_null($this->published_by);
    }
    /**
     * Выбирать из БД только опубликовынные посты
     */
    public function scopePublished($builder){
        return $builder ->whereNotNull('published_by');
    }
    public function enable(){
        $this->published_by = auth()->user()->id;
        $this->update();
    }
    public function disable(){
        $this->published_by=null;
        $this->update();
    }
    public function isAuthor() {
        return $this->user->id === auth()->user()->id;
    }


}
