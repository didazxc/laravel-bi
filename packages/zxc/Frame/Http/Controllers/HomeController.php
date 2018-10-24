<?php
namespace Zxc\Frame\Http\Controllers;

use Zxc\Frame\Models\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{

    public function index(Request $request)
    {
        return view('zxcframe::home.index');
    }

    public function searchNav(Request $request){
        $q=$request->input('q');
        $navs=Menu::whereDescendantOf(2);
        if($q){
            $navs=$navs->where('display_name','like','%'.$q.'%');
        }
        $navs=$navs->get();
        return view('zxcframe::home.navlist',compact('navs'));
    }

}