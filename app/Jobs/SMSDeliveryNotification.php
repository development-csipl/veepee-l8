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

class SMSDeliveryNotification implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $details;
    protected $buyer;
    protected $suplier;
    protected $bname;
    protected $sname;
    protected $bmobile;
    protected $smobile;
    protected $bveepeeid;
    protected $sveepeeid;
    protected $brand;
    protected $status;
    protected $vporderid;
    protected $link;
    
    public function __construct($id){
        @$this->details      =   (new OrderRequester())->order($id);
        $this->bname        =   $this->details->buyer->name;
        $this->sname        =   $this->details->suplier->name;
        $this->bmobile      =   $this->details->buyer->number;
        $this->smobile      =   $this->details->suplier->number;
        $this->bveepeeid    =   $this->details->buyer->veepeeuser_id;
        $this->sveepeeid    =   $this->details->suplier->veepeeuser_id;
        $this->brand        =   $this->details->brand->name;
        $this->status       =   $this->details->order->status;
        $this->vporderid    =   $this->details->order->vporder_id;
        $this->link         =   url("order/show/".$this->details->order->id);
    }

    /*public function handle(){
        $this->buyer   = 'DEAR '.$this->bname.', '.$this->bveepeeid.', ORD. NO- '.$this->vporderid.'. , BRAND- '.$this->brand.', HAS BEEN CLOSED.FOR MORE DETAILS '.$this->link.',PLEASE CONTACT TO  '.env('APP_ENQUIRY');
        send_sms($this->bmobile,$this->buyer);
        $this->suplier = 'DEAR '.$this->sname.','.$this->bveepeeid.', ORD. NO- '.$this->vporderid.', HAS BEEN CLOSED, '.$this->bname.',FOR MORE DETAILS '.$this->link.',PLEASE CONTACT TO  '.env('APP_ENQUIRY');
        send_sms($this->smobile,$this->suplier);        
    }*/
    
    public function handle(){
        $buyerdlt='1207161536904262699';
        $supplierdlt='1207161552358103277';
        $this->buyer   = 'DEAR '.$this->bname.', '.$this->bveepeeid.', ORD. NO- '.$this->vporderid.'. , BRAND- '.$this->brand.', HAS BEEN CLOSED.FOR MORE DETAILS '.$this->link.',PLEASE CONTACT TO  '.env('APP_ENQUIRY');
        send_sms($this->bmobile,$this->buyer);
        //send whatsapp notification
        $whatsappTemplate = 'veepe_ord';
        $whatsappParameters = array(
          array(
              "name" => "veepee_msg",
              "value" => $this->buyer,
            ),
        );
        whatsappCurl($whatsappTemplate, $this->bmobile, $whatsappParameters);
        $this->suplier = 'DEAR '.$this->sname.','.$this->bveepeeid.', ORD. NO- '.$this->vporderid.', HAS BEEN CLOSED, '.$this->bname.',FOR MORE DETAILS '.$this->link.',PLEASE CONTACT TO  '.env('APP_ENQUIRY');
        send_sms($this->smobile,$this->suplier);
        //send whatsapp notification
        $whatsappTemplate = 'veepe_ord';
        $whatsappParameters = array(
          array(
              "name" => "veepee_msg",
              "value" => $this->suplier,
            ),
        );
        whatsappCurl($whatsappTemplate, $this->smobile, $whatsappParameters);        
    }

}
 