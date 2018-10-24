<?php

namespace Zxc\Keysql\Console\Commands;

use Illuminate\Console\Command;
use Mockery\Exception;
use DB;
use Log;
use Mail;
use Zxc\Keysql\Models\KeySql;
use Zxc\Keysql\Models\KeySqlMail;

class MailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'keysql:mail {cycle=daily : 周期} {ids=[] : 邮件id数组}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto send mail';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $cycle=$this->argument('cycle');
        $ids=json_decode($this->argument('ids'));
        switch($cycle){
            case 1:
            case 'daily':
                $crons=[1];
                break;
            case 2:
            case 'weekly':
                $crons=[2];
                break;
            case 3:
                $crons=[1,2];
                break;
            case 4:
            case 'monthly':
                $crons=[4];
                break;
            case 5:
                $crons=[1,4];
                break;
            case 6:
                $crons=[2,4];
                break;
            case 7:
                $crons=[1,2,4];
                break;
            default:
                Log::warning('cycle值不合适');
                return;
        }
        
        $cids=KeySqlMail::whereIn('cron',$crons)->lists('id')->toArray();

        $ids=$ids?array_intersect($ids,$cids):$cids;
        
        if(!$ids){
            Log::warning('没有满足条件的ids');
            return;
        }else{
            $this->mailreport($ids);
        }
        
    }

    public function mailreport($ids=[])
    {        
        $logfile='/home/didazxc/www/laravel-v5.2.15/storage/logs/laravel-'.date('Y-m-d').'.log';
        $writable=is_writable($logfile);
        echo '开始发送邮件...'.chr(13).chr(10);
        
        if($writable){
            echo '可写入';
            ob_start();
        }
        $t1=time();
        
        foreach($ids as $id){
            echo '发送id='.$id.'的邮件：'.chr(13).chr(10);
            $m=KeySqlMail::find($id);
            if($m){
                try{
                    $this->report($m->sqlstr,$m->tos,$m->ccs,$m->subject,$m->connstr);
                }catch(Exception $e){
                    Log::warning('id='.$id.': 邮件发送失败');
                    continue;
                }
            }
        }
        
        $t2=time();
        echo '邮件发送完毕，用时'.($t2-$t1).'秒'.chr(13).chr(10);
        
        if($writable){
            $string = ob_get_contents();
            $title='邮件数据: '.implode(' ',$this->argument()).' 用时'.($t2-$t1).'秒';
            $logstr=$title.chr(13).chr(10).$string;
            if(strpos($string,'失败')){
                Log::warning($logstr);
            }
            ob_flush();
            ob_end_clean();
        }
        
    }
    
    private function report($sql,$to,$cc=[],$subject='齐齐数据，请查收！',$connstr='qq_sqlsrv')
    {
        $sqlstr=KeySql::parseSQL($sql);
        $res=KeySql::getDbRes($sqlstr,$connstr)[0];
        
        foreach($res[0] as $k=>$v){
            $head[]=$k;
        }
        
        Mail::send('keysql::emails.mail_table', ['title'=>'齐齐数据汇报','data'=>$res,'head'=>$head], function($message) use($to,$cc,$subject)
        {
            if(count($cc)){
                $message->to($to)->cc($cc)->subject($subject);
            }else{
                $message->to($to)->subject($subject);
            }
        });
    }
    
    public function tencent(){
        $sql=<<<SQL
SELECT  [日期]
      ,[当天总注册人数]
      ,[PC注册]
      ,[移动注册]
      ,[安卓注册]
      ,[手Q新增]
  FROM [QIQI_Stats].[dbo].[t_mail_tencent_report]
SQL;
        
        $to=[
            'shuweizhang@tencent.com',
            'wangxue@17guagua.com',
            'wanghaifeng@17guagua.com',
            'dongmei@17guagua.com'
        ];
        $cc=[
            'v_minwen@tencent.com',
            'chloezhu@tencent.com',
            'rainzywang@tencent.com',
            'yintianwen@17guagua.com',
            'zhangxiaochuan@17guagua.com'
        ];
        $subject='齐齐数据，请查收！';
        $to=['zhangxiaochuan@17guagua.com'];
        $cc=[];
        $this->report($sql,$to,$cc,$subject);
    }
    
    public function yingyongbao(){
        $starttime=strtotime('-7 day');
        $endtime=strtotime('-1 day');
        $startdate=date('Y-m-d',$starttime);
        $enddate=date('Y-m-d',$endtime);
        $starttime=strtotime('-7 day')*1000;
        $endtime=strtotime('-1 day')*1000;
        $sql=<<<SQL
        
IF OBJECT_ID('tempdb.dbo.##act', 'U') IS NOT NULL
  DROP TABLE ##act
IF OBJECT_ID('tempdb.dbo.##new', 'U') IS NOT NULL
  DROP TABLE ##new
IF OBJECT_ID('tempdb.dbo.##act9', 'U') IS NOT NULL
  DROP TABLE ##act9
IF OBJECT_ID('tempdb.dbo.##new9', 'U') IS NOT NULL
  DROP TABLE ##new9
;
select  CONVERT(VARCHAR(10),DATEADD(SECOND,reg_time/1000,'1970-01-01 08:00:00'),120)  logtime
        ,count(distinct userid) new
        into ##new
from [login_stat_qq].[dbo].[t_tencent_user_reg_record] with (nolock)
where reg_time>=".(strtotime('-7 day')*1000)."  and reg_time<".(time()*1000)."
group by CONVERT(VARCHAR(10),DATEADD(SECOND,reg_time/1000,'1970-01-01 08:00:00'),120)

select  CONVERT(VARCHAR(10),DATEADD(SECOND,reg_time/1000,'1970-01-01 08:00:00'),120)  logtime
        ,count(distinct userid) new9
        into ##new9
from [login_stat_qq].[dbo].[t_tencent_user_reg_record] with (nolock)
where sourcetype=52 and reg_time>=".(strtotime('-7 day')*1000)."  and reg_time<".(time()*1000)."
group by CONVERT(VARCHAR(10),DATEADD(SECOND,reg_time/1000,'1970-01-01 08:00:00'),120)

select  logtime,count(distinct userid) active,sum(pay) pay
       into ##act
from [QIQI_Stats].[dbo].[t_account_status_day]  with (nolock)
where  logtime>='".date('Y-m-d',strtotime('-7 day'))."' and logtime<'".date('Y-m-d')."'
group by logtime 

select a.logtime,COUNT(distinct a.userid) active9,SUM(pay) pay9
       into ##act9
       from
(select  distinct CONVERT(VARCHAR(10),DATEADD(SECOND,login_time/1000,'1970-01-01 08:00:00'),120)  logtime
       ,userid
from [login_stat_qq].[dbo].[t_tencent_user_login_record] with (nolock)
where sourcetype=52 and login_time>=".(strtotime('-7 day')*1000)."  and login_time<".(time()*1000).")a
left join
(select  logtime,userid,pay
from [QIQI_Stats].[dbo].[t_account_status_day]  with (nolock)
where  pay>0 and logtime>='".date('Y-m-d',strtotime('-7 day'))."' and logtime<'".date('Y-m-d')."')b
on a.logtime=b.logtime and a.userid=b.userid 
group by a.logtime;

select a.logtime '日期'
    ,b.new '新增'
    ,c.new9 '应用宝新增'
    ,a.active '活跃'
    ,d.active9 '应用宝活跃'
    ,convert(int,a.pay) '付费'
    ,convert(int,d.pay9) '应用宝付费'
from ##act a
left join ##new b
on a.logtime=b.logtime
left join ##new9 c
on a.logtime=c.logtime
left join ##act9 d
on a.logtime=d.logtime
;
IF OBJECT_ID('tempdb.dbo.##act', 'U') IS NOT NULL
  DROP TABLE ##act
IF OBJECT_ID('tempdb.dbo.##new', 'U') IS NOT NULL
  DROP TABLE ##new
IF OBJECT_ID('tempdb.dbo.##act9', 'U') IS NOT NULL
  DROP TABLE ##act9
IF OBJECT_ID('tempdb.dbo.##new9', 'U') IS NOT NULL
  DROP TABLE ##new9

SQL;
        $to=[
            'shuweizhang@tencent.com',
            'xuchunlin@17guagua.com',
            'wangxue@17guagua.com',
            'wanghaifeng@17guagua.com'
        ];
        $cc=[
            'chloezhu@tencent.com',
            'akiraliu@tencent.com',
            'yintianwen@17guagua.com',
            'zhangxiaochuan@17guagua.com'
        ];
        $subject='齐齐-应用宝数据日报';
        
        $this->report($sql,$to,$cc,$subject);
    }
    
    

}

