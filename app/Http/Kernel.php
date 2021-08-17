<?php

namespace App\Http;

use App\Http\Controllers\Admin\PostController;
use App\Http\Middleware\CheckUserPermission;
use App\Http\Middleware\CheckUserRole;
use App\Http\Middleware\CreateCategory;
use App\Http\Middleware\DeleteCategory;
use App\Http\Middleware\DeletePostMiddleware;
use App\Http\Middleware\EditCategory;
use App\Http\Middleware\EditPostMiddleware;
use App\Http\Middleware\ManageCategory;
use App\Http\Middleware\ManagePostsMiddleware;
use App\Http\Middleware\PublishPostMiddleware;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'role' => \App\Http\Middleware\CheckUserRole::class,
        'perm'=>\App\Http\Middleware\CheckUserPermission::class,
        'perm:manage-posts'=>\App\Http\Middleware\ManagePostsMiddleware::class,
        'perm:edit-post'=>\App\Http\Middleware\EditPostMiddleware::class,
        'perm:publish-post'=>\App\Http\Middleware\PublishPostMiddleware::class,
        'perm:delete-post'=>\App\Http\Middleware\DeletePostMiddleware::class,
        'perm:manage-categories'=>\App\Http\Middleware\ManageCategory::class,
        'perm:edit-category'=>\App\Http\Middleware\EditCategory::class,
        'perm:delete-category'=>\App\Http\Middleware\DeleteCategory::class,
        'perm:create-category'=>\App\Http\Middleware\CreateCategory::class,
    ];
}
