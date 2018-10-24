<?php

namespace Zxc\Keylib\Console\Commands;

use Illuminate\Console\Command;
use Zxc\Keylib\Models\KeyLibSql;
use Log;
use Exception;

class UpdateKeyLib extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'keylib:update {cycle=daily : 运行周期} {startdate? : 起始日期} {enddate? : 结束日期} {id_array? : ID数组}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update data from key_lib_sql.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $logfile=storage_path('logs/keylib_update_'.date('Y-m-d').'.log');
        $writable=is_writable($logfile);
        echo '更新KeyLib数据'.chr(13).chr(10);
        if($writable){
            ob_start();
        }
        
            $t1=time();
            $id_array=json_decode($this->argument('id_array'));
            KeyLibSql::updateKeyLib($this->argument('cycle'),$this->argument('startdate'),$this->argument('enddate'),$id_array,true,true);
            $t2=time();
            echo 'KeyLib数据更新完毕，用时'.($t2-$t1).'秒';
        
        if($writable){
            $string = ob_get_contents();
            $title='更新KeyLib数据: '.implode(' ',$this->argument()).' 用时'.($t2-$t1).'秒';
            $logstr=$title.chr(13).chr(10).$string;
            if(strpos($string,'失败')){
                Log::warning($logstr);
            }elseif($this->argument('cycle')!='realtime'){
                Log::info($logstr);
            }else{
                Log::notice($logstr);
            }
            ob_flush();
            ob_end_clean();
        }
    }


}
