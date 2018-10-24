<?php

namespace Zxc\Api\Http\Controllers;

use Illuminate\Http\Request;
use Zxc\Thrift\Clients\AppClient;
use Zxc\Thrift\Hbase;
use Closure;

class ProfileController extends ApiController
{
    //the type of imei and cols must be array
    public function userprofile_template(Request $request,$cols,Closure $callback=null)
    {
        //$start_time=microtime(true);
        //imei预处理
        if(!$request->exists("imei")){
            return $this->failed("Where is your imei !");
        }
        $imei=$request->input("imei");
        if(!is_array($imei)){
            $imei=explode(",",$imei);
        }
        $imei=array_unique($imei);
        if(count($imei)>10){
            return $this->failed("The number of imeis should not larger than 10 !");
        }elseif(count($imei)<1){
            return $this->failed("The number of imeis is at least 1 !");
        }

        //columns构建
        $columns=array_map(function($item){
            $items=explode(':',$item);
            if(count($items)<2){
                return new Hbase\TColumn(["family"=>"t","qualifier"=>$items[0]]);
            }else{
                return new Hbase\TColumn(["family"=>$items[0],"qualifier"=>$items[1]]);
            }
        },$cols);

        //get构建
        $gets=array_map(function($item)use($columns){
            return new Hbase\TGet([
                "row"=>$item,
                "columns"=>$columns
            ]);
        },$imei);//["row"=>"3c01b2067cd02e1f","columns"=>[ new Hbase\TColumn(["family"=>"t"]) ]]

        //查询
        try{
            $client=AppClient::getInstance();
            $results = $client->getMultiple("userProfile",$gets);
        }catch (\Exception $e){
            return $this->internalError();
        }

        //结果整理
        if($callback){
            $res=array_map($callback,$results);
        }else{
            $res=array_map(function($v){
                $cols["imei"]=$v->row;
                foreach($v->columnValues as $cv){
                    //$cols['date']=date('Y-m-d',$cv->timestamp/1000);
                    $cols[$cv->qualifier]=$cv->value;
                }
                return $cols;
            },$results);
        }
        //echo (microtime(true)-$start_time)*1000;
        return $this->success($res);
    }

    public function userprofile(Request $request)
    {
        $apiJson=$this->getApiJsonCollect();
        if($apiJson->isNotEmpty()){
            return $this->userprofile_template($request,$apiJson->all());
        }else{
            return $this->failed('所在用户组没有查询权限');
        }
    }

    public function applist(Request $request)
    {
        if($this->verifyPermission()) {
            return $this->userprofile_template($request, ["apps"],function($v){
                $cols["imei"]=$v->row;
                foreach($v->columnValues as $cv){
                    $cols[$cv->qualifier.'_date']=date('Y-m-d',$cv->timestamp/1000);
                    $cols[$cv->qualifier]=$cv->value;
                }
                return $cols;
            });
        }else{
            return $this->failed('所在用户组没有查询权限');
        }
    }

}

