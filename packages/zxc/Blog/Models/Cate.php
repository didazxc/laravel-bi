<?php

namespace Zxc\Blog\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Auth;

class Cate extends Model
{
    use NodeTrait;
    protected $table='t_zxc_blog_cate';
    protected $fillable = ['id','parent_id','user_id','permission_id','description'];

    public function user()
    {
        return $this->belongsTo(\App\User::class,"user_id","id");
    }

    public function permission()
    {
        return $this->belongsTo(config('permission.models.permission'),"permission_id","id");
    }

    public function save(array $options = []){
        if(!$this->user_id){
            $this->user_id=Auth::user()->id;
        }
        if($this->parent_id==$this->id || $this->isAncestorOf(Cate::find($this->parent_id))){
            $this->parent_id=null;
        }
        parent::save($options);
    }

}
