<?php

namespace App\Console;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;
use App\Models\OrderModel;
//use App\Jobs\SendNotification;
use Mail;
use App\User;

class Kernel extends ConsoleKernel{

    protected $commands = [
        Commands\NotifyOrder::class,
        Commands\AutoCancelOrder::class,
        Commands\AutoCancelOrderAfterDays::class,
        Commands\AutoCancelTwoDaysOrders::class,
    ];

    protected function schedule(Schedule $schedule){
        if(env('CRON') == TRUE){
            date_default_timezone_set('Asia/Kolkata');
             
            if (date('H') == 9 && date('i') == 30) {
            fcmnotifyAndroid();
            //For 3 days previous notification on bilty date amount due
            //fcmnotifyAndroidforbiltydue();
            }
            
            if (date('H') == 18 && date('i') == 00) {
            fcmnotifyAndroid();
            }
            
            if (date('H') == 11 && date('i') == 00) {
            fcmnotifyAndroid2();
             fcmnotifyAndroidforbiltydue();
            }
            
            if (date('H') == 16 && date('i') == 00) {
            fcmnotifyAndroid2();
            fcmnotifyAndroidforbiltydue();
            }
            //$schedule->call(function(){ Log::info('Cron Job running'); })->everyMinute();
            $schedule->command('send:autocanceleorder')->dailyAt('17:44')->timezone('Asia/Kolkata');
            //$schedule->command('send:notifyorder')->daily();
            //$schedule->command('send:notifyorder')->dailyAt('20:01')->timezone('Asia/Kolkata');
            //$schedule->command('send:notifyorder')->dailyAt('18:00')->timezone('Asia/Kolkata');
            //$schedule->command('days:ordercancel')->everyThirtyMinutes()->withoutOverlapping();
            //$schedule->command('days:ordertowdayscancel')->everyMinute()->withoutOverlapping();
        }
    }

    protected function commands(){
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
