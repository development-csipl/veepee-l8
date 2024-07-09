<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\{OrderModel,Setting};
use App\Jobs\SendNotification;
use Mail;

class AutoCancelOrderAfterDays extends Command{

    protected $signature = 'days:ordercancel';


    protected $description = 'Command for auto cancel order after specific days';


    public function __construct(){
        parent::__construct();
    }

    public function handle(){
        
        $days       =   (Setting::where('name','autocancel')->first())->content;
        $orders     =    OrderModel::where('status','Confirm')->where('orderlast_date','<',date('Y-m-d', strtotime('-'.$days.' days')))->get();
  
        if($orders != null && !$orders->isEmpty()){
            foreach ($orders as $row) {
                $Orders =   OrderModel::where('id',$row->id)->first();
                $Orders->status  =   'Cancelled';
                $Orders->save();
                
                $data   =   [
                    'Msg'   => 'We have cancelled your order because your due date expired.', 
                    'ODate' => date('d-m-Y',strtotime($row->created_at)), 
                    'BName' => $row->buyer->name
                ];
                
                $buyer  =   $row->buyer->email;
                Mail::send('emails.order_cancelled',$data,function($message) use ($buyer) {
                    $message->to($buyer)->subject('Veepee Internatonal- Order Cancelled');
                });
            }
        }
    }
    
}
