<?php

namespace Zxc\Blog;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use View;

class BlogServiceProvider extends ServiceProvider
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
            'prefix' => 'blog',
            'namespace' => 'Zxc\Blog\Http\Controllers'
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/routes.php');
        });
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadViewsFrom(__DIR__.'/views', 'zxcblog');

        //Gate::policy(Models\Post::class, Policies\PostPolicy::class);
        View::composer('zxcblog::layouts.frameapp', '\Zxc\Frame\Http\ViewComposers\AppComposer');
        View::composer('zxcblog::layouts.aside', '\Zxc\Blog\Http\ViewComposers\MenuComposer');

        require __DIR__.'/gatePolicy.php';
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
