<?php
namespace Zxc\Keysql\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Zxc\Keysql\Models\KeySql;
use Zxc\Keysql\Models\Helper;
use Auth;

class AdminController extends BaseController{
 
    public function getKeysql(Request $request){
        if($request->input('id')===null){
            $data=KeySql::orderBy('id','desc')->select('id','conn','temptable','cron','username','sql_desc')->get();
            $columns = array(
                ['data' => 'id', 'title' => 'ID'],
                ['data' => 'sql_desc', 'title' => 'SQL描述'],
                ['data' => 'conn', 'title' => '数据源'],
                ['data' => 'temptable', 'title' => '本地临时表'],
                ['data' => 'cron', 'title' => '周期'],
                ['data' => 'username', 'title' => '创建者']
            );
            $res=compact('data','columns');
            return view('keysql::admin.keysql',['res'=>$res]);
        }
        $sql_id=$request->input('id');
        $data=$sql_id?KeySql::find($sql_id):[];
        if(!$data){
            $sql_id=0;
            $data=new KeySql(['username'=>Auth::user()->name]);
        }
        $dbs=config('database.connections');
        return view('keysql::admin.keysqledit',['sql_id'=>$sql_id,'data'=>$data,'dbs'=>$dbs]);
        
    }

    public function postKeysql(Request $request){
        $sql_id=$request->input('sql_id');
        if($sql_id){
            $keysql=KeySql::find($sql_id);
        }else{
            $keysql=new KeySql(['username'=>Auth::user()->name]);
        }
        $keysql->sqlstr=$request->input('sqlstr','');
        $keysql->key_id_json=trim($request->input('key_id_json',''));
        $keysql->var_json=trim($request->input('var_json',''));
        $keysql->echart_json=trim($request->input('echart_json',''));
        $keysql->echart_js=trim($request->input('echart_js',''));
        $keysql->wx_str=trim($request->input('wx_str',''));
        $keysql->temptable=$request->input('temptable','');
        $keysql->cron=$request->input('cron',0);
        $keysql->conn=$request->input('conn','');
        $keysql->sql_desc=$request->input('sql_desc','没有填写');
        try{
            $keysql->save();
        }catch (\Exception $e){
            dd($e->getMessage());
        }
        $sql_id=$keysql->id;
        return $sql_id;
    }

    public function getKeysqltest(Request $request){
        $sql_id=$request->input('id',0);
        $form=[];
        $echarts=[];
        $echartjs=[];
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
        return view('keysql::admin.keysqltest',compact('sql_id','form','echarts','echartjs','desc_table'));
    }

    public function postKeysqltest(Request $request){
        $keysql=KeySql::find($request->input('sql_id'));
        if($request->input('type')){
            $keysql->tableOperation($request->input('type'));
            return 1;
        }
        $res=$keysql->getTableData($request->input());
        return $res;
    }
 
}