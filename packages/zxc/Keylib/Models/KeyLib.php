<?php

namespace Zxc\Keylib\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Mockery\Exception;
use Illuminate\Support\Facades\Config;

class KeyLib extends Model{

    protected $table = 'zxc__key_lib';
    public $timestamps = false;

    protected $primaryKey = ['logtime','cycle','terminal','key_id'];
    
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('keylib.keylib_table','zxc__key_lib');
    }

    public function keyLibDic()
    {
        return $this->belongsTo('Zxc\Keylib\Models\KeyLibDic','key_id','key_id');
    }

    /**
     * 将结果集转换为可插入KeyLib的数据形式
     * @param $result
     * @param $key_id_json_array
     * @return array
     */
    static function res2insData($result,$key_id_json_array){
        //循环结果集，构造插入SQL，并生成插入数据集
        $data=array();
        $result=Helper::my_objectToArray($result);
        foreach($result as $res){
            $logtime=$res['logtime'];
            foreach($key_id_json_array as $k=>$v){
                $key_value=$res[$k];
                $cycle=$v['cycle']?$v['cycle']:0;
                $terminal=$v['terminal']?$v['terminal']:0;

                if(strlen($key_value)) {
                    $data[] = ['logtime' => $logtime, 'cycle' => $v['cycle'], 'terminal' => $v['terminal'], 'key_id' => $v['key_id'], 'key_value' => $key_value];
                }
            }
        }
        return $data;
    }

    /**
     * 将结果集数据直接插入数据库
     * @param $result
     * @param $key_id_json_array
     * @param string $intotable
     */
    static function insData($result,$key_id_json_array,$intotable=''){
        if($intotable==''){
            $intotable=config('keylib.keylib_table');
        }
        $data=KeyLib::res2insData($result,$key_id_json_array);
        $replace_sql=Helper::my_ReplaceSql($intotable,$data);
        DB::insert($replace_sql);
    }

    //以下为数据输出模块——————————————————————————————————————
    /**
     * 获取datatable数据源
     * @param $id_arr
     * @param string $startdate
     * @param string $enddate
     * @param bool|true $convert
     * @param string $fromtable
     * @return array
     */
    static function getTableData($id_arr,$cycle,$terminal,$startdate='',$enddate='',$convert=true,$fromtable='key_lib'){
        $startdate=$startdate?$startdate:date('Y-m-d',strtotime(' -30 day'));
        $enddate=$enddate?$enddate:date('Y-m-d');
        $id_str=implode(',',$id_arr);
        $id_data=KeyLibDic::whereIn('key_id',$id_arr)->get();
        $column_arr=[["data"=> "logtime" ,"title"=>"时间"]];
        $str='logtime';
        foreach($id_data as $i){
            $str=$str.',sum(if(key_id='.$i['key_id'].',key_value,0)) as '."'".$i['key_id']."'";
            $column_arr[]=["data"=> $i['key_id'],"title"=>$i['key_name']];
        }
        $sql=<<<SQL
select $str
from $fromtable
where key_id in ($id_str)
and logtime>='$startdate'
and logtime<='$enddate'
and cycle = $cycle
and terminal = $terminal
group by logtime
order by logtime
SQL;
        $data=DB::select($sql);
        if($convert){
            foreach($data as $key=>$d){
                foreach($d as $k=>$v){
                    if($k!='logtime'){
                        switch(substr($k,-2)){
                            case '20':
                            case '12':
                                $data[$key]->{$k}=number_format($v*100,2).'%';
                                break;
                            case '21':
                                $data[$key]->{$k}=intval($v);
                                break;
                            case '19':
                            case '23':
                            case '25':
                                $data[$key]->{$k}=intval($v/1000);
                                break;
                            default:
                                $data[$key]->{$k}=floatval($v);
                        }
                    }
                }
            }
        }
        $res=["data"=>$data,"columns"=>$column_arr];
        return $res;
    }

    static function getDescTable($id_arr){
        $key_datas=KeyLibDic::whereIn('key_id',$id_arr)->get();
        foreach($key_datas as $key_i){
            $data[]=['keytag'=>$key_i->key_id,'keydesc'=>$key_i->key_desc];
        }
        $columns = [['data' => 'keytag', 'title' => '指标'],['data' => 'keydesc', 'title' => '描述']];
        $result = ['data' => $data, 'columns' => $columns];
        return $result;
    }

    /**
     * 获取echarts数据源
     * 字典列表式数据
     * @param $id_arr
     * @param string $startdate
     * @param string $enddate
     * @param bool|true $convert
     * @param string $fromtable
     * @return array
     */
    static function getEcData($id_arr,$cycle,$terminal,$startdate='',$enddate='',$convert=true,$fromtable='key_lib'){
        $res=KeyLib::getTableData($id_arr,$cycle,$terminal,$startdate,$enddate,$convert,$fromtable);
        $column_arr=$res['columns'];
        $data=$res['data'];
        $ec_array=array();
        foreach($data as $row){
            foreach($row as $k=>$v){
                $ec_array[$k][]=$v;
            }
        }
        return array('data'=>$ec_array,'columns'=>$column_arr);
    }

    /**
     * 获取datatable 和 echarts数据源
     * 字典列表式数据
     * @param $id_arr
     * @param string $startdate
     * @param string $enddate
     * @param bool|true $convert
     * @param string $fromtable
     * @return array
     */
    static function getAllData($id_arr,$cycle,$terminal,$startdate='',$enddate='',$convert=true,$fromtable='key_lib'){
        $res=KeyLib::getTableData($id_arr,$cycle,$terminal,$startdate,$enddate,$convert,$fromtable);
        $id_data=KeyLibDic::whereIn('key_id',$id_arr)->lists('key_name','key_id')->all();
        $data=$res['data'];
        $ec_array=array();
        foreach($data as $row){
            foreach($row as $k=>$v){
                $ec_array[$k][]=$v;
            }
        }
        return array(
            'datatables'=>$res,
            'echarts'=>array('data'=>$ec_array,'columns'=>$id_data));
    }

}