<?php

namespace Zxc\Keylib\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use DB;
use Log;
use Zxc\Keysql\Models\KeySql;

class KeyLibSql extends Model
{
    protected $table = 'zxc__key_lib_sql';
    protected $keylib_table;
    protected $keylib_realtime_table;

    public $cycle_array=[
        'daily'=>1,
        'weekly'=>2,
        'monthly'=>4,
        'realtime'=>8,
        'minutely'=>8,
        //'hourly'=>16,
        'day'=>1,
        'week'=>2,
        'month'=>4,
        'minute'=>8,
        //'hour'=>16,
        '1'=>1,
        '2'=>2,
        '4'=>4,
        '8'=>8,
        //'16'=>16
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('keylib.keylib_sql_table','zxc__key_lib_sql');
        $this->keylib_table = config('keylib.keylib_table','zxc__key_lib');
        $this->keylib_realtime_table = config('keylib.keylib_realtime_table','zxc__key_lib_realtime');
    }

    /**
     * 系统规则的变量
     * $_daily_last_0_month_end_time
     * var0: daily weekly monthly 周期第一天
     * var1: last next 加减
     * var2: int 数量
     * var3: 间隔
     * var4: start end 基准
     * var5: date横线日期 date0数字日期 time秒 time0毫秒 yyyymmdd yyyy mm dd 日期形式
     * @param $startdate
     * @param $enddate
     * @return array
     */
    private function getSysVars($cycle,$startdate,$enddate){
        $sqlstr=$this->getAttribute('sqlstr');
        if($sqlstr){
            preg_match_all('/(?<=\$_)\w+/i',$sqlstr,$matches);
            $var_array=$matches[0];
        }
        $sys_vars=[];
        foreach($var_array as $var){
            $vs0=explode('_',$var);
            $vsn=count($vs0);
            $vs=[];
            if($vsn<6){
                for($i=0;$i<$vsn;++$i){
                    $vs[$i+6-$vsn]=$vs0[$i];
                }
                for($i=0;$i<6-$vsn;++$i){
                    $vs[$i]='_null_';
                }
            }else{
                $vs=$vs0;
            }

            $opt=$vs[1]=='next'?'+':'-';
            $intcrement=($vs[2] && $vs[2]!='_null_')?$vs[2]:'0';
            $interval=($vs[3]&& $vs[2]!='_null_')?$vs[3]:'day';

            if($interval=='cycle'){
                switch($cycle){
                    case 2:
                        $interval='week';
                        break;
                    case 4:
                        $interval='month';
                        break;
                    case 1:
                    default:
                        $interval='day';
                        break;
                }
            }

            if($vs[4]=='start'){
                $thisdate=$startdate;
            }else{
                $thisdate=$enddate;
            }
            switch($vs[0]){
                case 'monthly':
                    $thisv=strtotime(date('Y-m-1',strtotime("$thisdate $opt$intcrement $interval")));
                    break;
                case 'weekly':
                    $thisv=strtotime("$thisdate $opt$intcrement sunday -6 day");
                    break;
                case 'daily':
                default:
                    $thisv=strtotime("$thisdate $opt$intcrement $interval");
            }
            switch($vs[5]){
                case 'date':
                    $thisv=date('Y-m-d',$thisv);
                    break;
                case 'date0':
                    $thisv=date('Ymd',$thisv);
                    break;
                case 'time0':
                    break;
                case 'time':
                    $thisv=$thisv*1000;
                    break;
                default:
                    $thisv=date($vs[5],$thisv);
                    break;
            }
            $sys_vars['_'.$var]=$thisv;
        }

        return $sys_vars;
    }

    private function getTflagVars($cycle=1){
        $sqlstr=$this->getAttribute('sqlstr');
        
        if($sqlstr){
            preg_match_all('/(?<=\$tflag_)\w+/i',$sqlstr,$matches);
            $var_array=$matches[0];
        }
        $sys_vars=[];
        foreach($var_array as $var){
            $vs=explode('_',$var);
            $vsn=count($vs);

            $tag=$vs[0]?$vs[0]:'logtime';
            $op=$vsn>1?$vs[1]:'';
            $timestamp=$vsn>2?$vs[2]:0;

            $sys_vars['tflag_'.$var]=Helper::tflag($cycle,$tag,$op,$timestamp);
        }
        return $sys_vars;
    }

    private function parseSQL($cycle,$startdate,$enddate,$debug=false){
        switch($cycle){
            case 8:
                $startdate=date('Y-m-d H:i:s',strtotime($startdate));
                $enddate=date('Y-m-d H:i:s',strtotime($enddate));
                break;
            case 4:
                $startdate=date('Y-m-01',strtotime($startdate));
                $enddate=date('Y-m-01',strtotime($enddate));
                break;
            case 2:
                $startdate=date('Y-m-d',strtotime($startdate.' sunday -6 day'));
                $enddate=date('Y-m-d',strtotime($enddate.' sunday -6 day'));
                break;
            case 1:
            default:
                $startdate=date('Y-m-d',strtotime($startdate));
                $enddate=date('Y-m-d',strtotime($enddate));
                break;
        }
        extract($this->getSysVars($cycle,$startdate,$enddate));
        extract($this->getTflagVars($cycle));
        $keylib_table=$this->keylib_table;
        $sqlstr=$this->sqlstr;
        try{
            eval("\$sqlstr=\"$sqlstr\";");
        }catch (Exception $e){
            if($debug){
                echo $e->getMessage();
            }
            $sqlstr='';
        }
        return $sqlstr;
    }

    private function getData($sqlstr,$debug=false){
        $db_str=$this->conn;
        $db_config=config('database.connections.'.$db_str);

        return KeySql::getDbRes($sqlstr,$db_str)[0];

        if(!$db_config){
            if($debug){
                throw new Exception('database.connections.'.$db_str.' 不存在.');
            }else{
                return [];
            }
        }
        $coding=$db_config['charset'];
        $local_coding=config('database.connections.'.config('database.default').'.charset');
        $needchange=($local_coding!=$coding);
        $sqlstr = $needchange?iconv($local_coding, $coding, trim($sqlstr)):trim($sqlstr);
        $sqlstr_arr = explode(';', $sqlstr);
        foreach ($sqlstr_arr as $sql) {
            if(strlen(trim($sql))>0){
                $num_into_matches = preg_match('/\s*into\s*/i', $sql);
                $num_matches = preg_match('/^\s*select/i', $sql);
                if ($num_into_matches || !$num_matches) {
                    DB::connection($db_str)->statement($sql);
                } else {
                    $result_raw = DB::connection($db_str)->select($sql);
                    if($needchange){
                        $result=[];
                        foreach ($result_raw as $key_raw => $res_raw) {
                            foreach ($res_raw as $k_raw => $v_raw) {
                                $k=iconv($coding, $local_coding, $k_raw);
                                $v= mb_convert_encoding($v_raw, $local_coding,$coding);
                                $result[$key_raw][$k] = $v;
                            }
                        }
                    }else{
                        $result=Helper::my_objectToArray($result_raw);
                    }
                }
            }
        }
        return $result;
    }

    public function getKeyIdJsonArray($debug=false){
        try{
            $key_id_json_array = Helper::my_objectToArray(json_decode($this->key_id_json));
            if(!$key_id_json_array){throw new Exception('key_id_json格式问题');}
        }catch (Exception $e){
            if($debug){
                Helper::jsonerror($this->key_id_json);
                echo $e->getMessage();
            }
            $key_id_json_array=[];
        }
        return $key_id_json_array;
    }

    public function getDataArray($cycle,$startdate,$enddate,$debug=false){
        try{
            $sqlstr=$this->parseSQL($cycle,$startdate,$enddate,$debug);
            $data=$this->getData($sqlstr,$debug);
        }catch (Exception $e){
            if($debug){
                echo $e->getMessage().chr(10).chr(13);
            }
            $data=[];
        }
        if(!$data){
            echo $sqlstr.chr(10).chr(13);
        }
        return $data;
    }

    public function updateDataByStep($cycle='daily',$startdate='',$enddate='',$debug=false)
    {
        $cycle=$this->cycle_array[$cycle];
        switch($cycle){
            case 8:
                $format='Y-m-d H:i:s';
                $startdate=$startdate?date('Y-m-d H:i:s',strtotime($startdate)):date('Y-m-d H:i:s',strtotime('-1 hour'));
                $enddate=$enddate?date('Y-m-d H:i:s',strtotime($enddate)):date('Y-m-d H:i:s');
                $step=' +1 day ';
                break;
            case 4:
                $format='Y-m-01';
                $startdate=$startdate?date('Y-m-01',strtotime($startdate)):date('Y-m-01',strtotime('-1 month'));
                $enddate=$enddate?date('Y-m-01',strtotime($enddate)):date('Y-m-01');
                $step=' +1 month ';
                break;
            case 2:
                $format='Y-m-d';
                $startdate=$startdate?date('Y-m-d',strtotime($startdate.' sunday -6 day')):date('Y-m-d',strtotime('last sunday -6 day'));
                $enddate=$enddate?date('Y-m-d',strtotime($enddate.' sunday -6 day')):date('Y-m-d',strtotime('sunday -6 day'));
                $step=' +1 week ';
                break;
            case 1:
            default:
                $format='Y-m-d';
                $startdate=$startdate?date('Y-m-d',strtotime($startdate)):date('Y-m-d',strtotime('-1 day'));
                $enddate=$enddate?date('Y-m-d',strtotime($enddate)):date('Y-m-d');
                $step=' +1 day ';
                break;
        }
        for($i=$startdate;$i<$enddate;$i=$j){
            $j=date($format,strtotime($i.$step));
            $this->updateData($cycle,$i,$j,$debug);
        }
    }
    
    public function updateData($cycle,$startdate,$enddate,$debug=false){
        if($debug){
            echo $startdate.'  to  '.$enddate.chr(10).chr(13);
        }
        $cycle=$this->cycle_array[$cycle];

        if($this->cron==8){
            $intotable= $this->keylib_realtime_table;
        }else{
            $intotable= $this->keylib_table;
        }
        try{
            if($debug){
                $t0=time();
            }
            $key_id_json_array=$this->getKeyIdJsonArray($debug);
            if(!$key_id_json_array){
                throw new Exception('key_id_json为空');
            }

            
            $data=$this->getDataArray($cycle,$startdate,$enddate,$debug);
            if(!$data){
                throw new Exception('空数据');
            }
            
            foreach($key_id_json_array as $k=>$v){
                $key_id_json_array[$k]['cycle'] = array_key_exists('cycle',$v)?$v['cycle']:$cycle;
                $key_id_json_array[$k]['terminal'] = array_key_exists('terminal',$v)?$v['terminal']:0;
            }
            KeyLib::insData($data,$key_id_json_array,$intotable);
            if($debug){
                echo '耗时：'.(time()-$t0).'秒'.chr(10).chr(13);
                echo '插入成功！'.chr(10).chr(13);
            }
        }catch (Exception $e){
            if($debug){
                var_dump($data);
                echo $e->getMessage().chr(10).chr(13);
                echo '数据插入失败！'.chr(10).chr(13);
            }
        }
    }

    //以下为数据输入模块——————————————————————————————————————
    static function updateKeyLib($cycle='daily',$startdate='',$enddate='',$id_array=[],$byStep=true,$debug=true){
        switch($cycle){
            case 'daily':
                $crons=[1,3,5,7];
                break;
            case 'weekly':
                $crons=[2,3,6,7];
                break;
            case 'monthly':
                $crons=[4,5,6,7];
                break;
            case 'realtime':
                $crons=[8];
                break;
            default:
                $crons=$cycle;
        }

        if(count($id_array)){
            $sql_arr=KeyLibSql::whereIn('cron',$crons)->whereIn('id',$id_array)->orderBy('id')->get();
        }else{
            $sql_arr=KeyLibSql::whereIn('cron',$crons)->orderBy('id')->get();
        }

        foreach($sql_arr as $sql_i){
            if($debug){
                echo 'sql_id='.$sql_i->id.' : '.chr(10).chr(13);
            }
            if($byStep){
                $sql_i->updateDataByStep($cycle,$startdate,$enddate,$debug);
            }else{
                $sql_i->updateData($cycle,$startdate,$enddate,$debug);
            }
        }
    }

}
