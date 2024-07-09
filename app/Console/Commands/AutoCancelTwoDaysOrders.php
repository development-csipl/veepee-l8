<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\OrderModel;
use App\Jobs\SendNotification;
use Mail;

class AutoCancelTwoDaysOrders extends Command{

    protected $signature = 'send:ordertowdayscancel';


    protected $description = 'Auto cancel tow days old orders';
    
    protected $orders = [];


    public function __construct(){
        parent::__construct();
    }

    public function handle(){
         
    }
    
}
