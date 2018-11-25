<?php

namespace Zxc\Frame\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Kalnoy\Nestedset\NodeTrait;

class Menu extends Model
{
    use NodeTrait;
    protected $table='t_zxc_frame_menus';

    public function user(){
        return $this->belongsTo(\App\User::class,'user_id','id');
    }

    public function save(array $options = []){
        if(!$this->user_id){
            $this->user_id=Auth::user()->id;
            $this->user_name=Auth::user()->name;
        }
        if($this->parent_id==$this->id || $this->isAncestorOf(Menu::find($this->parent_id))){
            $this->parent_id=null;
        }
        parent::save($options);
    }

}
