<?php

namespace Zxc\Frame\Http\ViewComposers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HeaderComposer
{

    protected $user;

    public function __construct(Request $request){
        $this->user=Auth::user();
    }

    public function compose(View $view)
    {
        $view->with([
            'user'=>$this->user
        ]);
    }

}
