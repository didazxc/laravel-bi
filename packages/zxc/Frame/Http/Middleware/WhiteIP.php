<?php

namespace Zxc\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Zxc\Frame\Models\WhiteIp as Model;

class WhiteIP
{

    public function handle(Request $request, Closure $next)
    {
        #添加信任代理
        Request::setTrustedProxies(['10.144.0.0/16','10.153.0.0/16','10.152.84.21']);
        $ip = $request->getClientIp();
        if (!Model::where('ip',$ip)->first()) {
            abort(503,"ip:".$ip." not available~");
        }

        return $next($request);
    }

}
