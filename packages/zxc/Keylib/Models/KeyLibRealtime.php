<?php

namespace Zxc\Keylib\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class KeyLibRealtime extends Model
{
    protected $table = 'zxc__key_lib_realtime';
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('keylib.keylib_realtime_table','zxc__key_lib_realtime');
    }

    public function keyLibDic(){
        return $this->belongsTo('Zxc\Keylib\Models\KeyLibDic');
        
    }

    static function getData($id_arr,$terminal,$need_last=false,$startdate='',$enddate=''){
        $startdate=$startdate?$startdate:date('Y-m-d 00:00:00');
        $enddate=$enddate?$enddate:date('Y-m-d H:i:00');
        $res_now=KeyLib::getAllData($id_arr,8,$terminal,$startdate,$enddate,true,'zxc__key_lib_realtime');
        if($need_last){
            $startdate_last_day=date('Y-m-d',strtotime($startdate.' -1 day'));
            $enddate_last_day=date('Y-m-d 24:00:00',strtotime($enddate.' -1 day'));
            $res_last_day=KeyLib::getAllData($id_arr,8,$terminal,$startdate_last_day,$enddate_last_day,true,'zxc__key_lib_realtime');
            $startdate_last_week=date('Y-m-d',strtotime($startdate.' -7 day'));
            $enddate_last_week=date('Y-m-d 24:00:00',strtotime($enddate.' -7 day'));
            $res_last_week=KeyLib::getAllData($id_arr,8,$terminal,$startdate_last_week,$enddate_last_week,true,'zxc__key_lib_realtime');
            $res=['now'=>$res_now,'last_day'=>$res_last_day,'last_week'=>$res_last_week];
        }else{
            $res=['now'=>$res_now];
        }
        return $res;
    }

    static function alertData_ol($datetime){
        $alert=array();
        //在线预警 频率:10分钟 注释:diff9-9，最近9个点的中位数-之前9个点（前18～前10）的中位数，3标准差判断准则
        $ol_alert_threshold=5600;//(20151101-20151109)3*stdev.p
        $starttime=date('Y-m-d H:i:00',strtotime($datetime.' -8 minute'));
        $endtime=$datetime;
        $res= KeyLibRealtime::whereBetween('logtime',[$starttime,$endtime])->where('key_id',9)->lists('key_value')->toArray();
        $this_mid=Helper::median($res);

        $starttime=date('Y-m-d H:i:00',strtotime($datetime.' -17 minute'));
        $endtime=date('Y-m-d H:i:00',strtotime($datetime.' -9 minute'));
        $res= KeyLibRealtime::whereBetween('logtime',[$starttime,$endtime])->where('key_id',9)->lists('key_value')->toArray();
        $last_mid=Helper::median($res);
        $diff=$this_mid-$last_mid;

        $data_10=KeyLibRealtime::where('logtime',date('Y-m-d H:i:00',strtotime($datetime.' -9 minute')))->where('key_id',9)->pluck('key_value');
        $data_0=KeyLibRealtime::where('logtime',date('Y-m-d H:i:00',strtotime($datetime.' -2 minute')))->where('key_id',9)->pluck('key_value');
        $data_yestoday=KeyLibRealtime::where('logtime',date('Y-m-d H:i:00',strtotime($datetime.' -1 day')))->where('key_id',9)->pluck('key_value');
        $data_lastweek=KeyLibRealtime::where('logtime',date('Y-m-d H:i:00',strtotime($datetime.' -1 week')))->where('key_id',9)->pluck('key_value');
        $moredesc='10分钟前'. number_format($data_10,0)
            .'人，目前'. number_format($data_0,0)
            .'人，昨日同期'. number_format($data_yestoday,0)
            .'人，上周同期'.number_format($data_lastweek,0).'人';

        if(-$diff>$ol_alert_threshold){//掉人
            $alert['online']=array(
                'logtime'=>$datetime,
                'alert_type'=>'online',
                'cycle'=>'realtime',
                'threshold'=>(-$ol_alert_threshold),
                'data'=>$diff,
                'alert_desc'=>'严重掉线：10分钟内掉线人数超过'.(-$diff)
                    .'人，'.$moredesc);
        }elseif($diff>$ol_alert_threshold){
            $alert['online']=array(
                'logtime'=>$datetime,
                'alert_type'=>'online',
                'cycle'=>'realtime',
                'threshold'=>$ol_alert_threshold,
                'data'=>$diff,
                'alert_desc'=>'人数激增：10分钟内增加人数超过'.($diff).'人，'.$moredesc);
        }
        return $alert;
    }

    static function alertData_pay($datetime){
        $alert=array();
        //充值预警 频率:1小时 注释:近7天每日同期充值的4分位点异常判断模型
        $starttime=date('Y-m-d H:00:00',strtotime($datetime.' -7 day -1 hour'));
        $endtime=date('Y-m-d H:59:59',strtotime($datetime.' -1 day -1 hour'));
        $hour=date('H',strtotime($datetime.' -1 hour'));
        $res=KeyLibRealtime::select(DB::raw("sum(key_value) as pay"))
            ->whereBetween('logtime',[$starttime,$endtime])
            ->where('key_id',18)
            ->whereRaw("DATE_FORMAT(logtime ,'%H')='$hour'")
            ->groupBy(DB::raw("DATE_FORMAT(logtime ,'%%Y-%m-%d')"))
            ->lists('pay')->toArray();
        $q1=Helper::quartile($res,1);
        $q2=Helper::quartile($res,2);
        $q3=Helper::quartile($res,3);
        $up_threshold=$q3+3*($q3-$q1);
        $down_threshold=max([$q3-3*($q3-$q1),5000]);
        $starttime=date('Y-m-d H:00:00',strtotime($datetime.' -1 hour'));
        $endtime=date('Y-m-d H:59:59',strtotime($datetime.' -1 hour'));
        $data=KeyLibRealtime::whereBetween('logtime',[$starttime,$endtime])
            ->whereRaw("DATE_FORMAT(logtime ,'%H')='$hour'")
            ->where('key_id',18)
            ->sum('key_value');
        $data_sum_today=KeyLibRealtime::whereBetween('logtime',[
            date('Y-m-d 00:00:00'),
            $endtime])
            ->where('key_id',18)
            ->sum('key_value');
        $data_yestoday=KeyLibRealtime::whereBetween('logtime',
            [
                date('Y-m-d H:00:00',strtotime($starttime.' -1 day')),
                date('Y-m-d H:59:59',strtotime($endtime.' -1 day'))
            ])
            ->whereRaw("DATE_FORMAT(logtime ,'%H')='$hour'")
            ->where('key_id',18)
            ->sum('key_value');
        $data_lastweek=KeyLibRealtime::whereBetween('logtime',
            [
                date('Y-m-d H:00:00',strtotime($starttime.' -1 week')),
                date('Y-m-d H:59:59 ',strtotime($endtime.' -1 week'))
            ])
            ->whereRaw("DATE_FORMAT(logtime ,'%H')='$hour'")
            ->where('key_id',18)
            ->sum('key_value');
        $moredesc='昨日同期'. number_format($data_yestoday,0)
            .'，上周同期'.number_format($data_lastweek,0).'，今日充值总额'
            .number_format($data_sum_today,0);

        if($data-$up_threshold>0){
            $alert['pay']=array(
                'logtime'=>date('Y-m-d H:00:00',strtotime($datetime)),
                'alert_type'=>'pay',
                'cycle'=>'realtime',
                'data'=>$data,
                'threshold'=>$up_threshold,
                'alert_desc'=>"充值激增：小时充值".number_format($data,0)."，大幅超过近7天中值".number_format($q2,0).'，'.$moredesc
            );
        }else if($down_threshold-$data>0){
            $alert['pay']=array(
                'logtime'=>date('Y-m-d H:00:00',strtotime($datetime)),
                'alert_type'=>'pay',
                'cycle'=>'realtime',
                'data'=>$data,
                'threshold'=>$down_threshold,
                'alert_desc'=>"充值锐减：充值".number_format($data,0)."，大幅跌破近7天中值".number_format($q2,0).'，'.$moredesc
            );
        }
        return $alert;
    }

}
