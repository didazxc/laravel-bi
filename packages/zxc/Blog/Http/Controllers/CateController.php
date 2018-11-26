<?php

namespace Zxc\Blog\Http\Controllers;

use Illuminate\Http\Request;
use Zxc\Blog\Models\Post;
use Zxc\Blog\Models\Cate;
use App\Http\Controllers\Controller;
use DB;

class CateController extends Controller
{

    function index(Request $request)
    {
        $cates=Cate::select("id","label","parent_id","_lft","_rgt")->get()->toTree();
        return view("zxcblog::posts.cate",compact("cates"));
    }

    function catePosts(Request $request)
    {
        $request->validate([
            'cateId' => 'required|integer'
        ]);
        $cateId=$request->input('cateId');
        #id,label,isLeaf
        $idBase=1000;
        $posts=Post::where('cate_id',$cateId)
            ->orderBy('updated_at','desc')
            ->select(DB::Raw("1000*id id,title label,1 isLeaf"))
            ->get()
            ->toArray();
        return $posts;
    }

    function cates(Request $request)
    {
        $catesCollection=Cate::select("id","label","parent_id","_lft","_rgt")->get();
        $maxid=$catesCollection->max("id");
        $cates=$catesCollection->toTree();
        return view("zxcblog::posts.cateEdit",compact("cates","maxid"));
    }

    function catesUpdate(Request $request)
    {
        $request->validate([
            'nodeId' => 'required|integer',
            'operate'=>'required|string'
        ]);
        $nodeId=$request->input('nodeId');
        $operate=$request->input('operate');
        switch($operate){
            case "append":
                $request->validate([
                    'newChild' => 'required|array'
                ]);
                $newChild=$request->input('newChild');
                Cate::create(['id'=>$newChild['id'],'label'=>$newChild['label'],'parent_id'=>$nodeId]);
                break;
            case "remove":
                $request->validate([
                    'nodeId' => 'required|integer|min:2'
                ]);
                $node=Cate::find($nodeId);
                $ids=$node->descendants()->pluck("id");
                $ids[]=$node->id;
                if(Post::whereIn("cate_id",$ids)->count()>0){
                    return "请先删除该分类下的所有文章";
                }
                Cate::find($nodeId)->delete();
                break;
            case "drop":
                $request->validate([
                    'dropNodeId' => 'required|integer',
                    'dropType' => 'required'
                ]);
                $draggingNode=Cate::find($nodeId);
                $dropNodeId=$request->input('dropNodeId');
                $dropNode=Cate::find($dropNodeId);
                $dropType=$request->input('dropType');
                echo $nodeId."|".$dropNodeId;
                switch($dropType){
                    case "after":
                        $draggingNode->afterNode($dropNode)->save();
                        break;
                    case "before":
                        $draggingNode->beforeNode($dropNode)->save();
                        break;
                    case "inner":
                        $draggingNode->appendToNode($dropNode)->save();
                        break;
                }
                break;
        }
        return 1;
    }
}
