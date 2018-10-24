<?php

namespace Zxc\Keysql;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class KeysqlServiceProvider extends ServiceProvider
{

    public function boot(){
        Route::group([
            'middleware' => ['web','auth'],
            'prefix' => 'keysql',
            'namespace' => '\Zxc\Keysql\Http\Controllers'
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/routes.php');
        });
        $this->loadViewsFrom(__DIR__.'/views', 'keysql');
        $this->loadMigrationsFrom(__DIR__.'/migrations');
    }

    public function register(){
        //绑定自定义命令
        $this->commands('Zxc\Keysql\Console\Commands\UpdateKeySql');
        $this->commands('Zxc\Keysql\Console\Commands\MailCommand');
    }
}