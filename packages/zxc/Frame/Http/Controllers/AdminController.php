<?php
namespace Zxc\Frame\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Auth;
use Zxc\Frame\Models\Menu;

class AdminController extends BaseController{

    public function index(){
        return view('zxcframe::admin.index');
    }

    public function ajaxData(Request $request){
        $navid=$request->input('navid');
        $thisnav=$navid?Menu::find($navid):[];
        $root_id=$request->input('rootid',2);
        $root=Menu::find($root_id);
        $navlist=Menu::withDepth()->where('id','<>',$navid)
            ->where('_lft','>=',$root->_lft)
            ->where('_rgt','<=',$root->_rgt)
            ->get();
        return compact('thisnav','navlist');
    }

    public function getMenu(Request $request){
        $root_id=$request->input('id',2);
        $root=Menu::withDepth()->find($root_id);
        $root_depth=$root->depth;
        $menu_tree=Menu::withDepth()->descendantsOf($root_id)->toTree();
        $select_list=$root->getAncestors()->pluck('display_name','id')->toArray()
            +[$root->id=>$root->display_name]
            +$root->getSiblings()->filter(function($i){
                return $i->_lft+1<>$i->_rgt;
            })->pluck('display_name','id')->toArray()
            +Menu::where('parent_id',$root_id)->whereRaw('_lft+1<>_rgt')->pluck('display_name','id')->toArray();
        return view('zxcframe::admin.menu',compact('menu_tree','root_depth','select_list','root_id'));
    }

    public function postMenu(Request $request){
        if($request->input('type')=='delete'){
            return Menu::destroy($request->input('navid'));
        }
        if($request->input('id')){
            $nav=Menu::find($request->input('id'));
        }else{
            $nav=new Menu;
        }
        $nav->parent_id=$request->input('parent_id');
        $nav->user_id=Auth::user()->id;
        $nav->user_name=Auth::user()->name;
        $nav->name=$request->input('name')?:'undefined';
        $nav->display_name=$request->input('display_name')?:'未填写';
        $nav->permission=$request->input('permission','')?:'';
        $nav->url=$request->input('url','')?:'';
        $nav->description=$request->input('description','')?:'';
        $nav->fa=$request->input('fa','')?:'';
        $nav->disable=$request->input('disable',0)?:0;
        $nav->save();
        return 1;
    }

}