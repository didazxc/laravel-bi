<?php

namespace Zxc\Frame\Http\ViewComposers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Zxc\Frame\Services\MenuService;

class AppComposer
{

    protected $notPjax;

    public function __construct(Request $request){
        $this->notPjax=!$request->pjax();
    }

    public function compose(View $view)
    {
        $view->with([
            'notPjax'=>$this->notPjax
        ]);
    }

}
