<?php

namespace Zxc\Frame\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Zxc\Frame\Services\MenuService;

class MenuVerify
{

    public function handle($request, Closure $next, $guard = null)
    {
        $user=Auth::guard($guard)->user();
        if(!$user->can('admin')){//管理员，可以随意访问
            if(!MenuService::canPerm($request->path(),$user)){
                abort(503);
            }
        }
        return $next($request);
    }
}
