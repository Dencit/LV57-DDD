<?php

namespace Modules\Oauth\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

/**
 * notes: 注册模块路由配置 - 必须
 * @author 陈鸿扬 | @date 2021/2/3 9:54
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * The module namespace to assume when generating URLs to actions.
     * @var string
     */
    protected $moduleNamespace = '';

    /**
     * Called before routes are registered.
     * Register any model bindings or pattern based filters.
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     * @return void
     */
    public function map()
    {
        //$this->mapApiRoutes();

        //$this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     * These routes all receive session state, CSRF protection, etc.
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->moduleNamespace)
            ->group(module_path('Oauth', '/Routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     * These routes are typically stateless.
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('')
            ->middleware('api')
            ->namespace($this->moduleNamespace)
            ->group(module_path('Oauth', '/Routes/api.php'));
    }
}
