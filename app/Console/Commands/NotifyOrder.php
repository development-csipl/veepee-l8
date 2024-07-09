<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\OrderModel;
//use App\Jobs\SendNotification;
use Mail;
use App\User;
use App\Http\Controllers\Traits\PushNotificationTrait;

class NotifyOrder extends Command {
    
    protected $signature = 'send:notifyorder';

    protected $description = 'Command for notify order before 7 days';


    public function __construct() {
        parent::__construct();
    }

    public function handle(){
        
         
         //$notification   = "Remaining amount is not sufficient ";
         //notifyAndroid('6733','Veepee Internatonal',  $notification);
       fcmnotifyAndroid();
    }
}
