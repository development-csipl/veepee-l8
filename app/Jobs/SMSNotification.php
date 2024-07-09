<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Requesters\OrderRequester;
use App\Mail\OrderEmail;
use Mail;

class SMSNotification implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $details;
    protected $buyer;
    protected $suplier;
    
    public function __construct($details){
        $this->details  =   $details;// (new OrderRequester())->order($id);
    }

    public function handle(){
        $this->buyer    = 'DEAR '.$this->details->buyer->name.' '.$this->details->buyer->veepeeuser_id.' ORD. NO-'.$this->details->order->vporder_id.'. BRAND- '.$this->details->brand->name.' HAS BEEN  '.$this->details->status.'.FOR MORE DETAILS '.url("order/show/".$this->details->order->id).' PLEASE CONTACT TO 7718277182';
        $this->suplier  = 'DEAR '.$this->details->suplier->name.', '.$this->details->suplier->veepeeuser_id.', ORD. NO-'.$this->details->order->vporder_id.', HAS BEEN '.$this->details->status.', '.$this->details->buyer->name.',FOR MORE DETAILS CLICK(<a href="'.url("order/show/".$this->details->order->id).'">LINK</a>),PLEASE CONTACT TO 7718277182';
        send_sms($details->buyer->number,$this->buyer);
        send_sms($details->suplier->number,$this->suplier);
        //send whatsapp notification
        $whatsappTemplate = 'veepe_ord';
        $whatsappParameters = array(
          array(
              "name" => "veepee_msg",
              "value" => $this->buyer,
            ),
        );
        whatsappCurl($whatsappTemplate, $details->buyer->number, $whatsappParameters);
        $whatsappParameters = array(
            array(
                "name" => "veepee_msg",
                "value" => $this->suplier,
              ),
          );
          whatsappCurl($whatsappTemplate, $details->suplier->number, $whatsappParameters);
    }

}
