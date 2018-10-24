<?php

namespace Zxc\Keylib;

use Illuminate\Support\ServiceProvider;

class KeylibServiceProvider extends ServiceProvider
{

    public function boot(){
        $this->loadMigrationsFrom(__DIR__.'/migrations');
    }

    public function register(){
        //绑定自定义命令
        $this->commands('Zxc\Keylib\Console\Commands\UpdateKeyLib');
    }
}