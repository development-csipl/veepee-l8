<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\OrderModel;
use App\Models\OrderCancelModel;
use App\Jobs\{OrderNotification,SendNotification};
use Mail;
use DB;

class AutoCancelOrder extends Command{

    protected $signature = 'send:autocanceleorder';


    protected $description = 'Command for notify order cacel for specific amount';


    public function __construct(){
        parent::__construct();
    }

    public function handle(){
        
         //Auto cancel after 3 days
        $dayss=3;
        
        
        $orders     =    OrderModel::whereIn('status',['Waiting for approval','New'])->where('order_date','<=',date('Y-m-d', strtotime('-'.$dayss.' days')))->get();
  
        if($orders != null && !$orders->isEmpty()){
            foreach ($orders as $row) {
                
                if($row->status=='New'){
                    $cancelledBy=$row->buyer->id;
                $reasons='Order expired beacause approval not received, please contact order helpline';
                }
                if($row->status=='Waiting for approval'){
                    $cancelledBy=$row->supplier->id;
                $reasons='Order expired beacause approval not received, please contact order helpline '.$row->supplier->name.'';
                }
                $Orders =   OrderModel::where('id',$row->id)->first();
                $Orders->status  =   'Rejected';
                $Orders->reason  =   @$reasons;
                $Orders->save();
                
                OrderCancelModel::insert(['order_id'=>$Orders->id,'supplier_id'=>$Orders->supplier_id,'reason'=>$reasons,'status'=>'Rejected','cancelled_by'=>@$cancelledBy ]);
                
                 
            }
        }
        
        //End Auto cancel after 3 days
        $days       =   DB::table('settings')->where('name','autocancel')->value('content');
        $reason       =   DB::table('settings')->where('name','autocancel')->value('reason');
        if($days>0){
        $orders     =    OrderModel::where('status','Confirm')->where('orderlast_date','<=',date('Y-m-d', strtotime('-'.$days.' days')))->get();
  
        if($orders != null && !$orders->isEmpty()){
            foreach ($orders as $row) {
                if($row->status!='Completed'){
                $Orders =   OrderModel::where('id',$row->id)->first();
                $Orders->status  =   'Cancelled';
                $Orders->reason  =   $reason;
                $Orders->save();
                
                OrderCancelModel::insert(['order_id'=>$Orders->id,'supplier_id'=>$Orders->supplier_id,'reason'=>$Orders->reason,'status'=>'Cancelled','cancelled_by'=>1 ]);
                
                $data   =   [
                    'Msg'   => $reason, 
                    'ODate' => date('d-m-Y',strtotime($row->created_at)), 
                    'BName' => $row->buyer->name
                ];
                
                $buyer  =   $row->buyer->email;
                /*Mail::send('emails.order_cancelled',$data,function($message) use ($buyer) {
                    $message->to($buyer)->subject('Veepee Internatonal- Order Cancelled');
                });*/
                
                OrderNotification::dispatch($Orders->id);
                }
            }
        }
    }
    }
    
}
