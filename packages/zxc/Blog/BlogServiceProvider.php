<?php

namespace Zxc\Blog;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;


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
