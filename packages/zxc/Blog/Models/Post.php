<?php

namespace Zxc\Blog\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table='t_zxc_blog_posts';
    protected $fillable = ['user_id','title','text'];
    //protected $guarded = ['id','created_at','updated_at'];

    public function user()
    {
        return $this->belongsTo(\App\User::class,"user_id","id");
    }

    public function comments()
    {
        return $this->hasMany(Comment::class,"post_id","id");
    }

}
