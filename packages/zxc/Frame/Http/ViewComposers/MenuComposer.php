<?php

namespace Zxc\Frame\Http\ViewComposers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Zxc\Frame\Services\MenuService;

class MenuComposer
{

    protected $user;
    protected $path;
    protected $menus;

    public function __construct(Request $request){
        $this->user=Auth::user();
        $this->path=MenuService::getPaths($request->path());
        if($this->path->isEmpty()){
            $root_id=2;
        }else{
            $root_id=$this->path->first()->parent_id;
        }
        $this->menus=MenuService::getPermedMenus($this->user,$root_id);
    }

    public function compose(View $view)
    {
        $view->with([
            'menus'=>$this->menus,
            'path'=>$this->path,
            'user'=>$this->user
        ]);
    }

}
