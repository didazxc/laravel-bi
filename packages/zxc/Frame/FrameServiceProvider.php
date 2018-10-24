<?php
/*
 * Frame模块制定了一套适用于laravel的网站模板
 */

namespace Zxc\Frame;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

class FrameServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Route::group([
            'middleware' => ['web','auth'],
            'prefix' => 'frame',
            'namespace' => '\Zxc\Frame\Http\Controllers'
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/routes.php');
        });
        $this->loadViewsFrom(__DIR__.'/views', 'zxcframe');
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        View::composer('zxcframe::layouts.app', '\Zxc\Frame\Http\ViewComposers\AppComposer');
        View::composer('zxcframe::layouts.header', '\Zxc\Frame\Http\ViewComposers\HeaderComposer');
        View::composer('zxcframe::layouts.aside', '\Zxc\Frame\Http\ViewComposers\MenuComposer');
        View::composer('zxcframe::layouts.breadcrumb', '\Zxc\Frame\Http\ViewComposers\PathComposer');
        require __DIR__.'/gatePolicy.php';
        //发布资源文件
        $this->publishes([
            __DIR__.'/public' => public_path('packages/zxc'),
        ], 'public');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
