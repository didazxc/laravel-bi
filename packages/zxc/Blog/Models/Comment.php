<?php

namespace Zxc\Blog\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table='t_zxc_blog_comments';
    protected $fillable = ['user_id','post_id','at_cid','at_uid','of_cid','text'];
    //protected $guarded = ['id','created_at','updated_at'];

    public function user()
    {
        return $this->belongsTo(\App\User::class,"user_id","id");
    }

    public function comments()
    {
        return $this->hasMany(self::class,"of_cid","id");
    }

    public function at()
    {
        return $this->belongsTo(\App\User::class,"at_uid","id");
    }

    public function post()
    {
        return $this->belongsTo(Post::class,"post_id","id");
    }

    public function atuser()
    {
        return $this->belongsTo(\App\User::class,"at_cid","id");
    }

}
