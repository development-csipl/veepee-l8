<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderEmail extends Mailable{
    
    use Queueable, SerializesModels;

    protected $details;
    
    public function __construct($details){
        $this->details = $details;
    }

    public function build(){
        return $this->view('emails.place_order')->with(['data' =>$this->details])->subject('VeePee International '.$this->details->order->status.' Order#:'.$this->details->order->vporder_id);
    }
    
}
