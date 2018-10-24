<?php

namespace Zxc\Keysql\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Storage;
use Mockery\Exception;
use Schema;
use Auth;

class KeySql extends Model
{
    protected $table = 'zxc__key_sql';
    static public $table_prefix = 't_keysql__';
    protected $fillable=['username'];

    public function user()
    {
        return $this->belongsTo('App\User','username','name');
    }

//本地临时表逻辑——————————————————————————————————————————————————————————————    
    //存在数据库里的临时表名称
    public function realtable()
    {
        $table_prefix = KeySql::$table_prefix;
        $intotable=$this->temptable;
        if($intotable){
            if (preg_match('/^' . $table_prefix . '/', $intotable)) {
                $table_name = $intotable;
            } else {
                $table_name = $table_prefix . $intotable;
            };
        }else{
            $table_name='';
        }
        return $table_name;
    }

    public function keyidjson()
    {
        $search=['，','：','“','”',"'","‘","’"];
        $replace=[',',':','"','"','"','"','"'];
        $key_id_json=str_replace($search,$replace, $this->key_id_json);
        $key_id_json = json_decode($key_id_json);
        if($key_id_json && !array_key_exists(0,$key_id_json)){
            $key_id_json=[$key_id_json];
        }
        return $key_id_json;
    }
    
    //对临时表进行删除或清空操作
    public function tableOperation($type)
    {
        if(!$this->intotable || !$this->key_id_json){
            return 0;
        }
        $tablename=$this->realtable();
        $dbres=DB::select("select table_name from INFORMATION_SCHEMA.TABLES where TABLE_NAME like '{$tablename}_%';");
        foreach($dbres as $k=>$v){
            $tname=$v->table_name;
            switch($type){
                case 'delete':
                    $sql='DROP TABLE '.$tname;
                    break;
                case 'truncate':
                    $sql='TRUNCATE '.$tname;
                    break;
            }
            if(Schema::hasTable($tname)){
                DB::statement($sql);
            }
        }
        return 1;
        
    }
    
    //数据插入临时表
    protected function intoTable($res)
    {
        if(!$res || !$this->key_id_json){
            return 0;
        }
        $table_name=$this->realtable();
        if($table_name){
            $key_id_json = $this->keyidjson();
            foreach ($key_id_json as $kjson => $vjson) {
                //1.建表
                $tname=$table_name.'_'.$kjson;
                if (!Schema::hasTable($tname)) {
                    $createsql_arr = array();
                    foreach ($vjson as $k => $v) {
                        $createsql_arr[] = "`$k`" . ' ' . $v->type;
                    }
                    $createsql = 'create table ' . $tname . '(' . implode(',', $createsql_arr) . ')';
                    DB::statement($createsql);
                }
                //2.入库
                $data=$res[$kjson];
                if(explode(' ',trim($vjson->logtime->type))[0]=='date'){
                    foreach($data as $k=>$d){
                        if(strtotime($d['logtime'])>=strtotime(date('Y-m-d'))){
                            unset($data[$k]);
                        }
                    }
                }
                if(count($data)>0){
                    $replace_sql = Helper::my_ReplaceSql($tname, $data);
                    DB::insert($replace_sql);
                }
            }
            
        }
    }
    
    
//数据查询逻辑——————————————————————————————————————————————————————————————
 
    //对提交上来的form_array做预处理
    protected function parseFormArray($form_array=[])
    {
        if(!array_key_exists('startdate',$form_array) || !$form_array['startdate']){
            $form_array['startdate']=date('Y-m-d',strtotime('-1 day'));
        }
        if(!array_key_exists('enddate',$form_array)  || !$form_array['enddate']){
            $form_array['enddate']=date('Y-m-d',strtotime($form_array['startdate'].' +1 day'));
        }else{
            $var_array=json_decode($this->var_json);
            if($var_array->enddate->type=='date'){
                $form_array['enddate']=date('Y-m-d',strtotime($form_array['enddate'].' +1 day'));
            }
        }
        return $form_array;
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
    private function getSysVars($cycle=1,$startdate,$enddate)
    {
        
        $cycle_array=[
            'daily'=>1,
            'weekly'=>2,
            'monthly'=>4,
            'realtime'=>8,
            'day'=>1,
            'week'=>2,
            'month'=>4,
            '1'=>1,
            '2'=>2,
            '4'=>4,
            '8'=>8
        ];
        $cycle=$cycle_array[$cycle];
        //1、指定日期
        switch($cycle){
            case 8:
                $startdate=$startdate?date('Y-m-d H:i:s',strtotime($startdate)):date('Y-m-d H:i:s',strtotime('-1 hour'));
                $enddate=$enddate?date('Y-m-d H:i:s',strtotime($enddate)):date('Y-m-d H:i:s');
                break;
            case 4:
                $startdate=$startdate?date('Y-m-01',strtotime($startdate)):date('Y-m-01',strtotime('-1 month'));
                $enddate=$enddate?date('Y-m-01',strtotime($enddate)):date('Y-m-01');
                break;
            case 2:
                $startdate=$startdate?date('Y-m-d',strtotime($startdate.' sunday -6 day')):date('Y-m-d',strtotime('last sunday -6 day'));
                $enddate=$enddate?date('Y-m-d',strtotime($enddate.' sunday -6 day')):date('Y-m-d',strtotime('sunday -6 day'));
                break;
            case 1:
            default:
                $startdate=$startdate?date('Y-m-d',strtotime($startdate)):date('Y-m-d',strtotime('-1 day'));
                $enddate=$enddate?date('Y-m-d',strtotime($enddate)):date('Y-m-d');
                break;
        }
        //2、获取sql中预埋的变量
        $sqlstr=$this->sqlstr;
        if($sqlstr){
            preg_match_all('/(?<=\$_)\w+/i',$sqlstr,$matches);
            $var_array=$matches[0];
        }
        $sys_vars=[];
        //3、解析每一个变量
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
        //增加的系统变量：用户ID
        $sys_vars['_user']=Auth::user();
        $sys_vars['_userid']=$sys_vars['_user']?$sys_vars['_user']->id:0;

        return $sys_vars;
    }
    
    //从临时表数据库查询数据
    protected function getThisData($form_array)
    {
        if(!$this->key_id_json){
            return [];
        }
        $key_id_json = $this->keyidjson();
        $res=[];
        foreach ($key_id_json as $kjson => $vjson) {
            $tname=$this->realtable().'_'.$kjson;
            if(Schema::hasTable($tname)){
                $data=DB::table($tname)
                    ->where('logtime','>=',$form_array['startdate'])
                    ->where('logtime','<',$form_array['enddate']);
                foreach($vjson as $k=>$v){
                    if(array_key_exists('order',$v)){
                        $data=$data->orderBy($k,$v->order);
                    }
                }
                $res[]=$data->get();
            }
        }
        return $res;
    }

    //解析sql语句
    protected function getSQL($form_array,$cycle=1)
    {
        extract($form_array);
        $arr=array_merge($form_array,$this->getSysVars($cycle,$startdate,$enddate));
        return KeySql::parseSQL($this->sqlstr,$arr);
    }

    //解析SQL
    static function parseSQL($sqlstr,$form_array=[]){
        extract($form_array);
        $value=preg_replace('/--.*$/m','',$sqlstr);#同时剔除注释行
        if(preg_match('/^%phpsql\s+/',$value)){
            $sqlstr=eval(preg_replace('/^%phpsql\s+/','',$value));
        }else{
            eval("\$sqlstr=\"$value\";");
        }
        return $sqlstr;
    }

    //从数据库查询数据 
    protected function getDbData($sqlstr)
    {
        $db_str=$this->conn;
        return KeySql::getDbRes($sqlstr,$db_str);
    }
  
    public function showFormat($res)
    {
        $key_id_json = $this->keyidjson();
        $new_res=[];
        $i=0;
        foreach($res as $result_raw){
            $result=[];
            foreach ($result_raw as $key_raw => $res_raw) {
                foreach ($res_raw as $k => $v) {
                    if($key_id_json[$i] && array_key_exists($k,$key_id_json[$i]) && array_key_exists('format',$key_id_json[$i]->{$k})){
                        $format=$key_id_json[$i]->{$k}->format;
                        $type=$key_id_json[$i]->{$k}->type;
                        switch($type){
                            case 'enum':
                                if(is_object($format) && property_exists($format,$v)){
                                    $v=$format->{$v};
                                }
                                break;
                            case 'date':
                            case 'datetime':
                                $v=date($format,strtotime($v));
                                break;
                            default:
                                if(strlen($v))
                                    $v=$v?sprintf($format,strpos($format,'%%')?($v*100):$v):0;
                        }
                    }else{
                        if(strlen($v))
                            $v=$v?preg_replace('/\.0+$/','',$v):0;
                    }
                    $result[$key_raw][$k] = $v;
                }
            }
            $new_res[]=$result;
            $i+=1;
        }
        
        return $new_res;
    }
  
    //获取数据
    public function getData($form_array=[])
    {
        $form_array=$this->parseFormArray($form_array);
        $data=$this->getThisData($form_array);
        if(!$data || !$data[0]){
            $sql=$this->getSQL($form_array);
            $data=$this->getDbData($sql);
            $this->intoTable($data);
        }
        
        $data=$this->showFormat($data);
        return $data;
    }

//其他逻辑——————————————————————————————————————————————————————————————
    //微信替换后的字符串
    public function getWxStr($form_array)
    {

        $data=$this->getData($form_array)[0];
        foreach($data as $dk=>$dv){
            foreach($dv as $k=>$v){
                $datas['data_'.$dk.'_'.$k]=$v;
            }
        }
        extract($datas);
        $wx_str=$this->wx_str;
        $wx_str=str_replace(chr(13),'<br/>',$wx_str);
        $wx_str=str_replace(chr(10),'<br/>',$wx_str);
        eval("\$wx_str=\"$wx_str\";");
        return $wx_str;
    }
    
    //获取字段描述
    public function getDescTable()
    {
        $result=[];
        $KeyIdJson=json_decode($this->key_id_json);
        if($KeyIdJson){
            $data=[];
            foreach($KeyIdJson as $keytag=>$keyjson){
                if(array_key_exists('desc',$keyjson)){
                    $data[]=['keytag'=>array_key_exists('name',$keyjson)?$keyjson->name:$keytag,'keydesc'=>$keyjson->desc];
                }
            }
            if($data){
                $columns = [['data' => 'keytag', 'title' => '指标'],['data' => 'keydesc', 'title' => '描述']];
                $result = compact('data','columns');
            }
        }
        return $result;
    }


//Datatables逻辑——————————————————————————————————————————————————————————————
    //Datatables的options
    protected function getOptions()
    {
        if(!$this->key_id_json){
            return [];
        }
        $res=[];
        $key_id_json=$this->keyidjson();
        foreach ($key_id_json as $kjson => $vjson){
            $columns = array();
            $order = array();
            $i = 0;
            foreach ($vjson as $k => $v) {
                $name=array_key_exists('name',$v)?$v->name:$k;
                $columns[] = ['data' => $k, 'title' => $name];
                if(array_key_exists('order',$v)){
                    $order[] = [$i,$v->order=='desc'?'desc':'asc'];
                }
                ++$i;
            }
            $res[]=compact('columns','order');
        }
        return $res;
    }
    
    //Datatables的数据包
    public function getTableData($form_array)
    {
        $datas=$this->getData($form_array);
        $options=$this->getOptions();
        $res=[];
        foreach($datas as $kd=>$data){
            $columns=[];
            $option=[];
            if($data){
                if($options){
                    $columns0=$options[$kd]['columns'];
                    $needcol=array_keys($data[0]);
                    foreach($columns0 as $kcol=>$vcol){
                        if(in_array($vcol['data'], $needcol)){
                            $columns[]=$vcol;
                        }
                    }
                    $option['order']=$options[$kd]['order'];
                }else{
                    foreach($data[0] as $k=>$v){
                        $columns[] = ['data' => $k, 'title' => $k];
                    }
                }
            }
            $res[]=compact('data','columns','option');
        }

        return $res;
    }

//从数据库查询数据————————————————————————————————————————————
    static function getDbRes($sqlstr,$db_str)
    {

        if(preg_match('/^%python\s+/',$sqlstr)){
            //查询python，获取数组
            $sql=preg_replace('/^%python\s+/','',$sql);
            $file_name='keysql_'.date('YmdHis').rand().'.py';
            Storage::disk('local')->put($file_name, $sql);
            $python='/home/didazxc/anaconda3/bin/python3 ';
            exec($python.config('filesystems.disks.local.root').'/'.$file_name,$out,$states);
            $data=[json_decode($out[0],true)];
            Storage::disk('local')->delete($file_name);
            return $data;
        }

        if($db_str =='qq_sqlsrv'){
            $sqlstr='SET ANSI_WARNINGS ON; SET ANSI_NULLS ON; '.$sqlstr;
        }
        
        //$local_coding=config('database.connections.'.config('database.default').'.charset');
        $coding=config('database.connections.'.$db_str.'.charset','GB2312');
        $local_coding='UTF-8';
        //windows本地调试请注释
//        if(strtoupper(substr(PHP_OS,0,3))!=='WIN'){
//            $sqlstr=mb_convert_encoding($sqlstr,$coding,$local_coding);
//        }
        $sqlstr_arr = explode(';', trim($sqlstr));
        $res=[];
        $pdo = DB::connection($db_str);
        
        foreach ($sqlstr_arr as $sql) {

            if(strlen(trim($sql))>0){
                $num_into_matches = preg_match('/\s*into\s*/i', $sql);
                $num_matches = preg_match('/^\s*select/i', $sql);
                if ($num_into_matches || !$num_matches) {
                    $pdo->statement($sql);
                } else {
                    $result_raw = $pdo->select($sql);
                    $result=[];
                    foreach ($result_raw as $key_raw => $res_raw) {
                        foreach ($res_raw as $k_raw => $v_raw) {
                            $encode = mb_detect_encoding($k_raw, array('ASCII','UTF-8','GB2312','GBK'),true);
                            
                            try{
                                
                                $k= mb_convert_encoding($k_raw,$local_coding,$encode);
                            }catch(Exception $e){
                                $k=$k_raw;
                            }
                            $encode = mb_detect_encoding($v_raw, array($encode,'UTF-8','GB2312','GBK'),true);
                            try{
                                if(!$encode){$encode="GBK";};
                                $v= mb_convert_encoding($v_raw,$local_coding,$encode);
                            }catch(Exception $e){
                                $v=$v_raw;
                            }
                            
                            $result[$key_raw][$k] = $v;
                        }
                    }
                    $res[]=$result;
                }
            }
        }
        return $res;
    }
    
    
//自动更新——————————————————————————————————————————
    //更新临时表内的数据
    public function updateData($cycle=1,$startdate='',$enddate='',$debug=false)
    {
        if(trim($this->realtable())){
            $form_array['startdate']=$startdate;
            $form_array['enddate']=$enddate;
            try{
                $form_array=$this->parseFormArray($form_array);
                var_dump($form_array);
                $sql=$this->getSQL($form_array,$cycle);
                $data=$this->getDbData($sql);
                if(!$data){
                    throw new Exception('空数据');
                }
                
                $this->intoTable($data);
            }catch(Exception $e){
                if($debug){
                    var_dump($data);
                    echo $e->getMessage().chr(10).chr(13);
                    echo '数据插入失败！'.chr(10).chr(13);
                }
            }
        }else{
            if($debug){
                echo '本地表名未定义！'.chr(13).chr(10);
            }
        }
    }
    
    /**
     * 遍历key_sql表中的SQL，查出数据插入对应表中，无表则创建
     * 所有表必须有logtime字段
     * @param string $cycle
     * @param string $startdate
     * @param string $enddate
     * @param array $id_array
     * @param bool|false $debug
     */
    static function updateByKeySql($cycle = 'daily', $startdate = '', $enddate = '',$id_array=[],$debug=false)
    {
        switch ($cycle) {
            case 'daily':
                $crons = [1, 3, 5, 7];
                break;
            case 'weekly':
                $crons = [2, 3, 6, 7];
                break;
            case 'monthly':
                $crons = [4, 5, 6, 7];
                break;
        }
        if($id_array){
            $sqls = KeySql::whereIn('cron', $crons)->whereIn('id',$id_array)->get();
        }else{
            $sqls = KeySql::whereIn('cron', $crons)->orderBy('id')->get();
        }
        echo chr(13).chr(10);
        foreach ($sqls as $sql_i) {
            if($debug){
                echo '执行id='.$sql_i->id.'的SQL语句：'.chr(13).chr(10);
            }
            $sql_i->updateData($cycle,$startdate,$enddate,$debug);
            if($debug){
                echo '执行成功！'.chr(13).chr(10);
            }
        }
    }

}
