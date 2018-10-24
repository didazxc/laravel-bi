<?php

namespace Zxc\Frame\Http\ViewComposers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Zxc\Frame\Services\MenuService;

class PathComposer
{

    protected $user;
    protected $path;
    protected $menus;

    public function __construct(Request $request){
        $this->path=MenuService::getPaths($request->path());
    }

    public function compose(View $view)
    {
        $view->with([
            'path'=>$this->path
        ]);
    }

}
