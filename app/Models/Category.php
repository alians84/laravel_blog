<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'content',
        'image',
    ];
    use HasFactory;
    /**
     * Связь модели Category с моделью Post, позволяет получить все
     * посты, размещенные в текущей категори
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Связь модели Category с моделью Category, позволяет получить все
     * дочерние категории текущей категории
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Связь модели Category с моделью Category, позволяет получить
     * родителя текущей категории
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Возвращает список корневых категорий каталога товаров
     */
//    public static function roots() {
//        return self::where('parent_id', 0)->with('children')->get();
//    }
    /**
     * Возвращает массив идентификаторов всех потомков категории
     */
    public static function descendants($parent) {
        // получаем прямых потомков категории с идентификатором $id
        static $items = null;
        if (is_null($items)) {
            $items = self::all();
        }
        $ids = [];
        foreach ($items->where('parent_id', $parent) as $item) {
            $ids[] = $item->id;
            $ids = array_merge($ids, self::descendants($item->id));
        }
        return $ids;

    }

}
