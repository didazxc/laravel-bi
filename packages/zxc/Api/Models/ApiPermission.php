<?php

namespace Zxc\Api\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ApiPermission extends Model
{
    public $timestamps = false;

    protected $casts = [
        'json' => 'array',
    ];

    public function roles(){
        return $this->belongsTo(config('permission.models.permission'),'role_id','id');
    }

    public function getJsonstrAttribute(){
        return implode(',',$this->json?:['']);
    }

    public function setJsonstrAttribute($value){
        if($value!=''){
            $this->json=explode(',',$value);
        }
    }

    static public function getPerms(string $action){
        $ids=Auth::user()->roles()->pluck('id');
        $perms=ApiPermission::where('action','=',$action)
            ->whereIn('role_id',$ids)
            ->get();
        return $perms;
    }

    static public function getJsons(string $action){
        $ids=Auth::user()->roles()->pluck('id');
        $jsons=ApiPermission::where('action','=',$action)
            ->whereIn('role_id',$ids)
            ->pluck('json')
            ->flatten()
            ->flip()
            ->keys();
        return $jsons;
    }

}
