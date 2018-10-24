<?php
namespace Zxc\Keysql\Models;
use Illuminate\Database\Eloquent\Model;
use Auth;

class KeySqlMail extends Model
{
    protected $table = 'zxc__key_sql_mail';

    public $timestamps = false;
    
    protected $casts = [
        'tos' => 'array',
        'ccs' => 'array',
    ];
    
    public function user()
    {
        return $this->belongsTo('App\User','username','name');
    }
    
    public function save(array $options = [])
    {
        $this->username=Auth::user()->name;
        parent::save($options);
    }
    public function setTosAttribute($value)
    {
        if(is_array($value)){
            $value=json_encode($value);
        }elseif($value){
            $value=json_encode(explode(',',$value));
        }
        $this->attributes['tos']=$value;
    }

    public function setCcsAttribute($value)
    {
        if(is_array($value)){
            $value=json_encode($value);
        }elseif($value){
            $value=json_encode(explode(',',$value));
        }
        $this->attributes['ccs']=$value;
    }
}