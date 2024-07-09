<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\OrderEmail;
use Mail;

class SMSOrderNotification implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
    protected $bid;
    protected $sid;
    
    public function __construct($details){
        
        $this->bname        =   $details->buyer->name;
        $this->sname        =   $details->suplier->name;
        $this->bmobile      =   $details->buyer->number;
        $this->smobile      =   $details->suplier->number;
        $this->bveepeeid    =   $details->buyer->veepeeuser_id;
        $this->sveepeeid    =   $details->suplier->veepeeuser_id;
        $this->brand        =   $details->brand->name;
        $this->status       =   $details->order->status;
        $this->vporderid    =   $details->order->vporder_id;
        $this->link         =   url("order/show/".$details->order->id);
        $this->bid        =   $details->order->buyer_id;
        $this->sid        =   $details->order->supplier_id;
        
    }

    public function handle(){
        
        
        if($this->status=='Cancelled'){
            $dlt='1207163378123822273';
            $sdlt='1207163378133673210';
        }
        else if($this->status=='Confirm'){
            $dlt='1207163378106237130';
            $sdlt='1207163378110074486';
        }
        else if($this->status=='Completed'){
            $dlt='1207163378115864251';
            $sdlt='1207163378119302467';
        }else{
            $dlt='';
            $sdlt='';
            
        }
        $buyer_sms = getBuyers($this->bid);
        
        $supplier_sms = getSuplliers($this->sid);
        if($this->status=='Cancelled' || $this->status=='Rejected'){
        $this->buyer    = 'DEAR BUYER '.$this->bname.', '.$this->bveepeeid.', UNFORTUNATELY YOUR ORD. NO-'.$this->vporderid.', HAS BEEN '.$this->status.' DUE TO SOME REASON,BRAND-'.$this->brand.', FOR MORE DETAILS CLICK '.$this->link.', PLEASE CONTACT TO-7718277182 (VEEPEE)';
        
        /*$this->buyer    = 'DEAR '.$this->bname.', '.$this->bveepeeid.', ORD. NO-'.$this->vporderid.'. BRAND- '.$this->brand.' HAS BEEN  '.$this->status.'.FOR MORE DETAILS '.$this->link.',PLEASE CONTACT TO '.env('APP_ENQUIRY');*/
        send_sms($buyer_sms->notify_sms,$this->buyer,$dlt);
        //send whatsapp notification
        $whatsappTemplate = 'veepe_ord';
        $whatsappParameters = array(
          array(
              "name" => "veepee_msg",
              "value" => $this->buyer,
            ),
        );
        whatsappCurl($whatsappTemplate, $buyer_sms->notify_whatsapp, $whatsappParameters);
        //send_sms($this->bmobile,$this->buyer,$dlt);
         //send_sms($this->bmobile,$this->buyer);
      
        
        
        $this->suplier  = 'DEAR SUPLIER '.$this->sname.', '.$this->sveepeeid.', UNFORTUNATELY YOUR ORD. NO-'.$this->vporderid.', HAS BEEN '.$this->status.' DUE TO SOME REASON, BUYER-'.$this->bname.', '.$this->bveepeeid.', FOR MORE DETAILS CLICK '.$this->link.', PLEASE CONTACT TO-7718277182 (VEEPEE)';
        //send whatsapp notification
        $whatsappTemplate = 'veepe_ord';
        $whatsappParameters = array(
          array(
              "name" => "veepee_msg",
              "value" => $this->suplier,
            ),
        );
        whatsappCurl($whatsappTemplate, $supplier_sms->notify_whatsapp, $whatsappParameters);

        }
        if($this->status=='Confirm'){
            
            $this->buyer    = 'DEAR BUYER ('.$this->bname.', '.$this->bveepeeid.'), WE HAVE RECEIVED YOUR CONFIRMED ORD. NO-'.$this->vporderid.', BRAND-'.$this->brand.',FOR MORE DETAILS CLICK '.$this->link.', PLEASE CONTACT TO-7718277182(VEEPEE)';
            $this->suplier  = 'CONGRATULATION SUPPLIER ('.$this->sname.', '.$this->sveepeeid.'),YOU HAVE RECEIVED OUR CONFIRMED ORD. NO.-'.$this->vporderid.',BUYER-'.$this->bname.',PLEASE DISPATCH ORDER BEFORE DUE DATE,FOR MORE DETAILS CLICK '.$this->link.',PLEASE CONTACT TO 7718277182(VEEPEE)';
        
        /*$this->buyer    = 'DEAR '.$this->bname.', '.$this->bveepeeid.', ORD. NO-'.$this->vporderid.'. BRAND- '.$this->brand.' HAS BEEN  '.$this->status.'.FOR MORE DETAILS '.$this->link.',PLEASE CONTACT TO '.env('APP_ENQUIRY');*/
        send_sms($buyer_sms->notify_sms,$this->buyer,$dlt);
        send_sms($supplier_sms->notify_sms,$this->suplier,$sdlt);
        //send whatsapp notification
        $whatsappTemplate = 'veepe_ord';
        $whatsappParameters = array(
          array(
              "name" => "veepee_msg",
              "value" => $this->buyer,
            ),
        );
        whatsappCurl($whatsappTemplate, $buyer_sms->notify_whatsapp, $whatsappParameters);

        //send whatsapp notification
        $whatsappTemplate = 'veepe_ord';
        $whatsappParameters = array(
          array(
              "name" => "veepee_msg",
              "value" => $this->suplier,
            ),
        );
        whatsappCurl($whatsappTemplate, $supplier_sms->notify_whatsapp, $whatsappParameters);
            
        }
        
        if($this->status=='Completed'){
            
            $this->buyer    = 'DEAR BUYER'.$this->bname.', '.$this->bveepeeid.', ORD. NO-'.$this->vporderid.', BRAND-'.$this->brand.', HAS BEEN CLOSED. FOR MORE DETAILS CLICK '.$this->link.', PLEASE CONTACT TO 7718277182 (VEEPEE)';
        
        /*$this->buyer    = 'DEAR '.$this->bname.', '.$this->bveepeeid.', ORD. NO-'.$this->vporderid.'. BRAND- '.$this->brand.' HAS BEEN  '.$this->status.'.FOR MORE DETAILS '.$this->link.',PLEASE CONTACT TO '.env('APP_ENQUIRY');*/
        send_sms($buyer_sms->notify_sms,$this->buyer,$dlt);
        send_sms($supplier_sms->notify_sms,$this->buyer,$sdlt);
        //send whatsapp notification
        $whatsappTemplate = 'veepe_ord';
        $whatsappParameters = array(
          array(
              "name" => "veepee_msg",
              "value" => $this->buyer,
            ),
        );
        whatsappCurl($whatsappTemplate, $buyer_sms->notify_whatsapp, $whatsappParameters);
        //send_sms($this->bmobile,$this->buyer,$dlt);
         //send_sms($this->bmobile,$this->buyer);
        
        $this->suplier  = 'DEAR SUPPLIER '.$this->sname.', '.$this->bveepeeid.', ORD. NO-'.$this->vporderid.', HAS BEEN CLOSED, BUYER-'.$this->bname.', FOR MORE DETAILS CLICK '.$this->link.', PLEASE CONTACT TO 7718277182 (VEEPEE)';

        $whatsappParameters = array(
            array(
                "name" => "veepee_msg",
                "value" => $this->suplier,
              ),
          );
        whatsappCurl($whatsappTemplate, $supplier_sms->notify_whatsapp, $whatsappParameters);
            
        }
        
        
        /*$this->suplier  = 'DEAR '.$this->sname.', '.$this->sveepeeid.', ORD. NO-'.$this->vporderid.', HAS BEEN '.$this->status.', '.$this->bname.',FOR MORE DETAILS '.$this->link.',PLEASE CONTACT TO '.env('APP_ENQUIRY');*/
        //send_sms($supplier_sms->notify_sms,$this->suplier,$sdlt);
        //send whatsapp notification
        //$whatsappTemplate = 'veepe_ord';
        // $whatsappParameters = array(
        //   array(
        //       "name" => "veepee_msg",
        //       "value" => $this->suplier,
        //     ),
        // );
        // whatsappCurl($whatsappTemplate, $buyer_sms->notify_sms, $whatsappParameters);
        //send_sms($this->smobile,$this->suplier,$sdlt);
        //send_sms($this->smobile,$this->suplier);
    }

}
 