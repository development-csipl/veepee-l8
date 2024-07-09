<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Requesters\OrderRequester;

class OrderSmsNotification implements ShouldQueue {
    
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $details;
    
    public function __construct($id){
        $this->details  = (new OrderRequester())->order($id);
        SMSOrderNotification::dispatch($this->details);
    }

    public function handle(){
        //
    }

}

