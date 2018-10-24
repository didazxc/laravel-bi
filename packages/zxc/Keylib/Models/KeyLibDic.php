<?php

namespace Zxc\Keylib\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

/**
 * Class KeyLibDic
 * 一、周期
 * 0:custom,1:daily,2:weekly,4:monthly,8:realtime
 * 二、端口
 * 0:all,1:pc,2:mb,3:ad,4:ios
 * @package Zxc\Keylib
 */
class KeyLibDic extends Model
{
    protected $table = 'zxc__key_lib_dic';
    public $timestamps = false;
    protected $primaryKey = 'key_id';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('keylib.keylib_dic_table','zxc__key_lib_dic');
    }

    public function keyLib()
    {
        return $this->hasMany('Zxc\Keylib\Models\KeyLib','key_id','key_id');
    }

    public function keyLibRealtime()
    {
        return $this->hasMany('Zxc\Keylib\Models\KeyLibRealtime','key_id','key_id');
    }



}
