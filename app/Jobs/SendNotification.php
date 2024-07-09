<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\SendEmail;
use Mail;

class SendNotification implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $details;
    
    public function __construct($details){
        $this->details = $details;
    }

    public function handle(){
        $email = new SendEmail($this->details);
        Mail::to($this->details['email'])->send($email);
    }
}
