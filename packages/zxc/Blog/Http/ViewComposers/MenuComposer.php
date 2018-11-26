<?php

namespace Zxc\Blog\Http\ViewComposers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Zxc\Blog\Models\Cate;
use Illuminate\Database\Eloquent\Collection;
use DB;

class MenuComposer
{

    protected $user;
    protected $path;
    protected $menus;

    public function __construct(Request $request){
        $this->user=Auth::user();
        $this->menus=Cate::with('posts')->get()->toTree();
        $this->path=new Collection();
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
