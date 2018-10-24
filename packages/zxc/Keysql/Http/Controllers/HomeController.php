<?php
namespace Zxc\Keysql\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Zxc\Keysql\Models\KeySql;
use Zxc\Keysql\Models\Helper;
use Auth;
use Route;
use Redirect;

class HomeController extends BaseController
{
    
    public function index(){
        return view('keysql::home.home',['sysname'=>config('keysql.sysname')]);
    }
    
    public function postKeysql(Request $request){
        $keysql=KeySql::find($request->input('sql_id'));
        $res=$keysql->getTableData($request->input());
        return $res;
    }
    
    public function getKeysql(Request $request){
        $sql_id=$request->route('sql_id');
        $desc_table=[];
        if($sql_id){
            $keysqldb=KeySql::find($sql_id);
            if($keysqldb){
                $form=$keysqldb->var_json?Helper::my_objectToArray(json_decode($keysqldb->var_json)):[];
                $echarts=$keysqldb->echart_json?Helper::my_objectToArray(json_decode($keysqldb->echart_json)):[];
                $echartjs=$keysqldb->echart_js;
            }
            $desc_table=$keysqldb->getDescTable();
        }
        return view('keysql::home.keysql',compact('sql_id','form','echarts','echartjs','desc_table'));
    }

    public function getwx(Request $request)
    {

        if($request->has('test')){
            $keysql=KeySql::find($request->route('sql_id'));
            $res=$keysql?$keysql->getWxStr($request->input()):'';
            return $res;
        }else{
            $hour=$request->has('H')?$request->input('H'):'09';
            $min=$request->has('i')?$request->input('i'):'30';
            if(date('H')==$hour && intval(date('i'))<$min){
                $keysql=KeySql::find($request->route('sql_id'));
                $res=$keysql?$keysql->getWxStr($request->input()):'';
            }else{
                $res='';
            }
            return $res;
        }
    }

}
