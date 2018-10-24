<?php

namespace Zxc\Keysql\Console\Commands;

use Illuminate\Console\Command;
use DB;
use \Zxc\Keysql\Models\KeySql;
use Log;

class UpdateKeySql extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'keysql:update {cycle=daily : 运行周期} {startdate? : 起始日期} {enddate? : 结束日期} {id_array? : ID数组}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update data from key_sql.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $logfile=storage_path('logs/keysql_update_'.date('Y-m-d').'.log');
        $writable=is_writable($logfile);
        echo '更新KeySql数据...'.chr(13).chr(10);
        
        if($writable){
            echo '可写入';
            ob_start();
        }
        
        $t1=time();
        $id_array=json_decode($this->argument('id_array'));
        KeySql::updateByKeySql($this->argument('cycle'),$this->argument('startdate'),$this->argument('enddate'),$id_array,true);
        $t2=time();
        echo 'KeySql数据更新完毕，用时'.($t2-$t1).'秒';
        
        if($writable){
            $string = ob_get_contents();
            $title='更新KeySql数据: '.implode(' ',$this->argument()).' 用时'.($t2-$t1).'秒';
            $logstr=$title.chr(13).chr(10).$string;
            if(strpos($string,'失败')){
                Log::warning($logstr);
            }elseif($this->argument('cycle')!='realtime'){
                Log::info($logstr);
            }
            ob_flush();
            ob_end_clean();
        }
    }
    
    
    
}
