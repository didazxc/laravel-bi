<?php
namespace Zxc\Blog\Http\Controllers;

use Illuminate\Http\Request;
use Zxc\Blog\Models\Comment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function getList(Request $request)
    {
        $post_id=$request->route("post",0);
        $per_page=$request->input("per_page",10);
        $comments=Comment::with('comments.user:id,name','comments.at:id,name','user:id,name')->where("post_id",$post_id)->where("of_cid",0)->paginate($per_page);
        return $comments;
    }

    public function postComment(Request $request)
    {
        $post_id=$request->route("post",0);
        $of_cid=$request->input("of_cid", 0)?:0;
        $at_cid=$request->input("at_cid", 0)?:0;
        $at_c=Comment::find($at_cid);
        $at_uid=$at_c?$at_c->user_id:0;
        if($post_id>0) {
            Comment::create([
                "user_id" => Auth::user()->id,
                "post_id" => $post_id,
                "at_cid" => $at_cid,
                "at_uid" => $at_uid,
                "of_cid" => $of_cid,
                "text" => $request->input("text")
            ]);
            return $this->getList($request);
        }
        return 0;
    }

}