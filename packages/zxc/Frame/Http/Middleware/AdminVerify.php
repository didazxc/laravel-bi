<?php

namespace Zxc\Frame\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminVerify
{

    public function handle($request, Closure $next, $guard = null)
    {
        $user=Auth::guard($guard)->user();
        if(!$user->can('admin')){//管理员，可以随意访问
            return redirect(route('home'));
        }
        return $next($request);
    }
}
