<?php

namespace Zxc\Keylib\Traits;
use Illuminate\Console\Scheduling\Schedule;

class KeyLibSchedule
{
    public static function schedule(Schedule $schedule)
    {
        $logfile=storage_path('logs/keylib_update.log');
        $schedule->command('keylib:update realtime')->everyMinute()->withoutOverlapping()->sendOutputTo($logfile);
        $schedule->command('keylib:update daily')->dailyAt('6:00')->withoutOverlapping()->sendOutputTo($logfile);
        $schedule->command('keylib:update weekly')->weekly()->mondays()->at('6:00')->withoutOverlapping()->sendOutputTo($logfile);
        $schedule->command('keylib:update monthly')->cron('0 6 1 * *')->withoutOverlapping()->sendOutputTo($logfile);
    }
    
}
