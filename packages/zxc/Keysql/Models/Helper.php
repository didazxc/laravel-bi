<?php
namespace Zxc\Keysql\Models;

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

    static function getSqlId($path){
        $end=array_pop(explode('/',$path));
        if(is_numeric($end)){
            return $end;
        }else{
            return 0;
        }
    }

}