<?php

namespace Zxc\Blog\Http\Controllers;

use Illuminate\Http\Request;
use Zxc\Blog\Models\Post;
use Zxc\Blog\Models\Cate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{

    public function index()
    {
        $posts=Post::with("user")->orderBy('updated_at','desc')->paginate(5);
        return view('zxcblog::posts.index',compact('posts'));
    }

    public function show($post_id)
    {
        $post=Post::find($post_id);
        return view('zxcblog::posts.show',compact('post'));
    }

    public function lists()
    {
        $posts=Post::orderBy('updated_at','desc')->paginate(5);
        return view('zxcblog::posts.lists',compact('posts'));
    }

    public function postDestroy(Request $request)
    {
        if($request->ajax() &&
            $request->has("posts")){
            $posts=$request->post("posts");
            $this->authorize('zxcblog.update-post-ids', [[$posts]]);
            return Post::destroy($posts);
        }else{
            return 0;
        }
    }

    public function edit(Request $request)
    {
        $post_id=$request->route('post',0);
        if($post_id>0){
            $post=Post::find($post_id);
            if(!$post){
                abort(404);
            }
            $this->authorize('zxcblog.update-post', $post);
        }else{
            $post= new Post();
            $post->fill([
                'user_id' => Auth::user()->id,
                'title' => "请在这里输入文章标题",
                'text' => "# 标题",
            ]);
        }
        $pjax=$request->pjax();
        $cates=Cate::pluck("name","id");
        return view('zxcblog::posts.edit',compact('post','pjax','cates'));
    }

    public function postUpdate(Request $request)
    {
        $post_id=$request->route('post',0);
        if($post_id>0){
            $post=Post::find($post_id);
            if(!$post){
                abort(404);
            }
            $this->authorize('zxcblog.update-post', $post);
        }else{
            $post=new Post();
        }
        $post->fill([
            'user_id' => Auth::user()->id,
            'title' => $request->input('title'),
            'text' => $request->input('text'),
            'cate_id' => $request->input('cate_id')
        ]);
        if($post->save()){
            return $post->id;
        }
        return 0;
    }

}
