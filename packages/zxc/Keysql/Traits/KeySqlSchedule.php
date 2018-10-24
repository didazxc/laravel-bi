<?php

namespace Zxc\Keysql\Traits;
use Illuminate\Console\Scheduling\Schedule;

class KeySqlSchedule
{
    public static function schedule(Schedule $schedule)
    {
        $logfile=storage_path('logs/keysql_update.log');
        $schedule->command('keysql:update daily')->dailyAt('9:00')->withoutOverlapping()->sendOutputTo($logfile);
        $schedule->command('keysql:update weekly')->weekly()->mondays()->at('7:00')->withoutOverlapping()->sendOutputTo($logfile);
        $schedule->command('keysql:update monthly')->cron('0 7 1 * *')->withoutOverlapping()->sendOutputTo($logfile);
        
        $schedule->command('keysql:mail daily')->dailyAt('8:00')->withoutOverlapping()->sendOutputTo($logfile);
        $schedule->command('keysql:mail weekly')->weekly()->mondays()->at('10:00')->withoutOverlapping()->sendOutputTo($logfile);

    }
}
