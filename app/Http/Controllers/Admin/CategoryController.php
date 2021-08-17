<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
class CategoryController extends Controller
{
    public function __construct() {
        $this->middleware('perm:manage-posts')->only(['index', 'category', 'show']);
        $this->middleware('perm:edit-post')->only(['edit', 'update']);
        $this->middleware('perm:publish-post')->only(['enable', 'disable']);
        $this->middleware('perm:delete-post')->only('destroy');
    }

    /**
     * Показывает список всех категорий
     */
    public function index() {
        $items = Category::all();
        return view('admin.category.index', compact('items'));
    }

    /**
     * Показывает форму для создания категории
     */
    public function create() {
        return view('admin.category.create');
    }

    /**
     * Сохраняет новую категорию в базу данных
     */
    public function store(Request $request) {
        Category::create($request->all());
        return redirect()
            ->route('admin.category.index')
            ->with('success', 'Новая категория успешно создана');
    }

    /**
     * Показывает форму для редактирования категории
     */
    public function edit(Category $category) {
        return view('admin.category.edit', compact('category'));
    }

    /**
     * Обновляет категорию блога в базе данных
     */
    public function update(Request $request, Category $category) {
        $category->update($request->all());
        return redirect()
            ->route('admin.category.index')
            ->with('success', 'Категория была успешно исправлена');
    }

    /**
     * Удаляет категорию блога
     */
    public function destroy(Category $category) {
        if ($category->children->count()) {
            $errors[] = 'Нельзя удалить категорию с дочерними категориями';
        }
        if ($category->posts->count()) {
            $errors[] = 'Нельзя удалить категорию, которая содержит посты';
        }
        if (!empty($errors)) {
            return back()->withErrors($errors);
        }
        $category->delete();
        return redirect()
            ->route('admin.category.index')
            ->with('success', 'Категория блога успешно удалена');
    }
}
