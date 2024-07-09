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
use App\Models\BrandModel;

class OrderNotification implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $details  =   '';
    protected $order    =   '';




    
    public function __construct($id){
        $this->details  = (new OrderRequester())->order($id);
        $orders_data = [];
        foreach ($this->details->items as $key => $value) {
            $brands = BrandModel::find($value->brand_id);
            $a['id'] = $value->id;
            $a['item_name'] = $value->name;
            $a['brand_id'] = $brands->id;
            $a['brand_name'] = $brands->name;
            $orders_data[$key] = $a;
        }
        $this->details->items = $orders_data;
        SMSOrderNotification::dispatch($this->details);

        // $buyer_sms = getBuyers($this->details->buyer->id);
        // $supplier_sms = getSuplliers($this->details->buyer->id);
        
    }

    public function handle(){
        $email = new OrderEmail($this->details);
        //die("rrrrrr");
        //Mail::to('naresh@csipl.net')->send($email);
        //Mail::to('development@veepeeonline.com')->send($email);
        $buyer_sms = getBuyers($this->details->buyer->id);
        $supplier_sms = getSuplliers($this->details->suplier->id);
        try{

            // echo $buyer_sms->notify_email;
            // echo "<br/>";
            // echo $supplier_sms->notify_email;

            // die;

            Mail::to($buyer_sms->notify_email)->cc($supplier_sms->notify_email)->bcc('development@veepeeonline.com')->send($email);
        }
        catch(\Exception $e){
            //echo $e->getMessage(); die;
        }
        
        //Mail::to($this->details->buyer->email)->cc($this->details->suplier->email)->bcc('development@veepeeonline.com')->send($email);

         
         
    }
}
