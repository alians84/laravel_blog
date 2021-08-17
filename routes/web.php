<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\User\IndexController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\User\CommentsController;
use App\Http\Controllers\User\PostsController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//
Route::get('/',[\App\Http\Controllers\IndexController::class,'__invoke'])
->name('index');
/*
 * Регистрация, вход в ЛК, восстановление пароля
 */
Route::group([
    'as' => 'auth.', // имя маршрута, например auth.index
    'prefix' => 'auth', // префикс маршрута, например auth/index
], function () {
    // форма регистрации
    Route::get('register', [RegisterController::class,'register'])
        ->name('register');
    // создание пользователя
    Route::post('register', [RegisterController::class,'create'])
        ->name('create');
    //форма входа
    Route::get('login',[LoginController::class,'login'])
        ->name('login');
    //аутификация
    Route::post('login',[LoginController::class,'authenticate'])
        ->name('auth');
    //выход
    Route::get('logout',[LoginController::class,'logout'])
        ->name('logout');
    Route::get('forgot-password',[ForgotPasswordController::class,'form'])
        ->name('forgot-form');
    // письмо на почту
    Route::post('forgot-password', [ForgotPasswordController::class,'mail'])
        ->name('forgot-mail');
    //форма для восстановления
    Route::get(
        'reset-password/token/{token}/email/{email}',[ResetPasswordController::class,'form']
    )->name('reset-form');
    //востоновления пароля
    Route::post('reset-password',[ResetPasswordController::class,'reset'])
        ->name('reset-password');
    //сообщение о необходимости проверки нового адреса почты
    Route::get('verify-message',[VerifyEmailController::class,'message'])
        ->name('verify-message');
    Route::get('verify-email/token/{token}/id/{id}',[VerifyEmailController::class,'verify'])
        ->where('token','[a-f0-9]{32}')
        ->where('id','[0-9]+')
        ->name('verify-email');
});

/*
 * Личный кабинет пользователя
 */
Route::group([
    'as' => 'user.', // имя маршрута, например user.index
    'prefix' => 'user', // префикс маршрута, например user/index
    'middleware' => ['auth'] // один или несколько посредников
], function () {
    // главная страница
    Route::get('index',[IndexController::class,'__invoke'])
    ->name('index');
//    Route::resource('post', PostsController::class);
//    Route::resource('comment',[CommentsController::class,['except'=>['create','store']]]);

});
/*
 * Блог: все посты, посты категории, посты тега, страница поста
 */
Route::group([
    'as' => 'blog.', // имя маршрута, например blog.index
    'prefix' => 'blog', // префикс маршрута, например blog/index
], function () {
    // /#главная страница (все посты)
    Route::get('index', [BlogController::class, 'index'])
        ->name('index');
    // категория блога (посты категории)
    Route::get('category/{category:slug}', [BlogController::class, 'category'])
        ->name('category');
    // тег блога (посты с этим тегом)
    Route::get('tag/{tag:slug}', [BlogController::class, 'tag'])
        ->name('tag');
    // автор блога (посты этого автора)
    Route::get('author/{user}', [BlogController::class, 'author'])
        ->name('author');
    // страница просмотра поста блога
    Route::get('post/{post:slug}', [BlogController::class, 'post'])
        ->name('post');
    Route::post('post/{post}/comment', [BlogController::class, 'comment'])
        ->name('comment');
});
/*
 * Панель управления: CRUD-операции над постами, категориями, тегами
 */
Route::group([
    'as' => 'admin.', // имя маршрута, например admin.index
    'prefix' => 'admin', // префикс маршрута, например admin/index
    'middleware' => ['auth'] // один или несколько посредников
], function () {
    /*
     * CRUD-операции над постами блога
     */
    Route::resource('post', PostController::class);
    // доп.маршрут для показа постов категории
    Route::get('post/category/{category}', [PostController::class,'category'])
        ->name('post.category');
    // доп.маршрут, чтобы разрешить публикацию поста
    Route::get('post/enable/{post}', [PostController::class,'enable'])
        ->name('post.enable');
    // доп.маршрут, чтобы запретить публикацию поста
    Route::get('post/disable/{post}', [PostController::class,'disable'])
        ->name('post.disable');
    Route::resource('category', CategoryController::class, ['except'=>'show']);
    Route::resource('tag',TagController::class,['except' => 'show']);
    Route::resource('user',UserController::class,);
    Route::resource('comment', CommentController::class, ['except' => ['create', 'store']]);
    // доп.маршрут, чтобы разрешить публикацию комментария
    Route::get('comment/enable/{comment}', [CommentController::class,'enable'])
        ->name('comment.enable');
    // доп.маршрут, чтобы запретить публикацию комментария
    Route::get('comment/disable/{comment}',  [CommentController::class,'disable'])
        ->name('comment.disable');
    Route::get('index',\App\Http\Controllers\Admin\IndexController::class)
        ->name('index');
});
