<?php
namespace Zxc\Keylib\Models;

class Helper
{
    /**
     * 深度转换obj为arr
     * @param $obj
     * @return array
     */
    static function my_objectToArray($obj){
        $arr = is_object($obj) ? get_object_vars($obj) : $obj;
        if(is_array($arr)){
            return array_map(array('Zxc\Keysql\Models\Helper',"my_objectToArray"), $arr);
        }else{
            return $arr;
        }
    }

    /**
     * 生成replace形式的插入数据SQL
     * @param string $table
     * @param array $array
     * @return string
     */
    static function my_ReplaceSql($table,$array){
        if(count($array)==0){return '';};
        $values_arr=[];
        foreach($array as $arr){
            foreach($arr as $k=>$v){
                $arr[$k]=addslashes($v);
            }
            $values_arr[]='("'.implode('","',$arr).'")';
        };
        $keys='(`'.implode('`,`',array_keys($arr)).'`)';
        $values=implode(',',$values_arr);
        $sql=<<<SQL
REPLACE INTO $table $keys
VALUES
$values
SQL;
        return $sql;
    }

    static function jsonerror($json_str){
        //header("charset=utf-8");
        $json_str = preg_replace('/{/i', '', $json_str, 1);
        $json_str = preg_replace('/}\s*$/i', '', $json_str, 1);
        $json_arr = preg_split('/(?<=})\s*,\s*/i', $json_str);
        var_dump($json_arr);
        foreach ($json_arr as $json_i) {
            if (!json_decode('{' . $json_i . '}')) {
                echo 'json 出错位置：';
                var_dump($json_i);
            };
        }
    }

    /**
     * 获取数组的中位数
     * @param $array
     * @return float
     */
    static function median($array){
        if(count($array)){
            sort($array);
            $mid1 = floor((count($array) - 1) / 2);
            if(count($array)%2){
                $median=floatval($array[$mid1]);
            }else{
                $mid2 = ceil((count($array) - 1) / 2);
                $median=floatval(($array[$mid1]+$array[$mid2])/2);
            }
        }else{
            $median=0;
        }
        return $median;
    }

    /**
     * 4分位点数
     * @param $array
     * @param $n
     * @return float
     */
    static function quartile($array,$n){
        if(count($array)){
            sort($array);
            $pos=$n/4*(count($array)-1);
            $floor=floor($pos);
            if($pos==$floor){
                $quartile=$array[$pos];
            }else{
                $ceil=ceil($pos);
                $quartile= ($pos-$floor)*$array[$floor]+($ceil-$pos)*$array[$ceil];
            }
        }else{
            $quartile=0;
        }

        return $quartile;
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
     * @param $sqlstr
     * @param $startdate
     * @param $enddate
     * @return array
     */
    static function getSQLSysVars($sqlstr,$startdate,$enddate){
        if($sqlstr){
            preg_match_all('/(?<=\$_)\w+/i',$sqlstr,$matches);
            $var_array=$matches[0];
        }
        $sys_vars=[];
        foreach($var_array as $var){
            $vs=explode('_',$var);
            $vsn=count($vs);
            if($vsn<6){
                for($i=0;$i<$vsn;++$i){
                    $vs[$i+6-$vsn]=$vs[$i];
                }
                for($i=0;$i<6-$vsn;$i++){
                    $vs[$i]='_null_';
                }
            }

            $opt=$vs[1]=='next'?'+':'-';
            $intcrement=($vs[2] && $vs[2]!='_null_')?$vs[2]:'0';
            $interval=($vs[3]&& $vs[2]!='_null_')?$vs[3]:'day';
            if($vs[4]=='start'){
                $enddate=$startdate;
            }
            switch($vs[0]){
                case 'monthly':
                    $thisv=strtotime(date('Y-m-1',strtotime("$enddate $opt$intcrement $interval")));
                    break;
                case 'weekly':
                    $thisv=strtotime("$enddate $opt$intcrement sunday -6 day");
                    break;
                case 'daily':
                default:
                    $thisv=strtotime("$enddate $opt$intcrement $interval");
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
    
    /**
     * SQL周期替换函数
     * $tflag_logtime_last0_false
     */
    static function tflag($cycle = 'daily', $tag='logtime',  $operation = '', $timestamp = false){

        if($operation===0){
            $operation='';
        }else{
            $operation=str_replace('last','+',$operation);
            $operation=str_replace('next','-',$operation);
        }

        if ($timestamp) {
            $data0 = "DATEADD(SECOND,$tag/1000,'1970-01-01 08:00:00')";
        } else {
            $data0 = $tag;
        }
        switch ($cycle) {
            case 2:
                $tflag = <<<SQL
case datepart(dw,$data0)
  when 1 then CONVERT(VARCHAR(10),DATEADD(wk,DATEDIFF(wk,0,$data0)$operation-1,0),120)
  else CONVERT(VARCHAR(10),DATEADD(wk,DATEDIFF(wk,0,$data0)$operation,0),120)
end
SQL;
                break;
            case 4:
                $date = "DATEADD(month, DATEDIFF(month, 0, $data0)$operation,0)";
                $tflag = "CONVERT(VARCHAR(10),$date,120)";
                break;
            case 1:
            default:
                $date = $operation ? "DATEADD(day, DATEDIFF(day, 0, $data0)$operation,0)" : $data0;
                $tflag = "CONVERT(VARCHAR(10),$date,120)";
        }

        return $tflag;
    }
    
    

}