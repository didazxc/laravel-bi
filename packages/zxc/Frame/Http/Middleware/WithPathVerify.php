<?php

namespace Zxc\Frame\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Zxc\Frame\Models\Menu;
use Zxc\Frame\Services\MenuService;

class WithPathVerify
{

    public function handle($request, Closure $next, $path='', $guard = null)
    {
        $user=Auth::guard($guard)->user();
        if(!$user->can('admin') || !$path){//管理员或者path未填写，可以随意访问
            if(!MenuService::canPerm($path,$user)){
                abort(503);
            }
        }
        return $next($request);
    }
}
