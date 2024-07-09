<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;

use App\Models\BrandModel;
use App\Models\OrderModel;
use App\Models\SiteInfoModel;
use App\Models\OrderDeliveryModel;
use App\Requesters\OrderRequester;
use App\Models\EnquiryGalleryModel;
use DB;
use Mail;
use App\Jobs\{OrderNotification};
class HomeController extends Controller
{

    public function index()
    {
        return view('home');
    }
    
    public function privacy_policy(){
        $site_info = SiteInfoModel::first();
        return view('privacy_policy',compact('site_info'));
    }
    
    public function show($id){
    
       
	        /* $order = OrderModel::where('id',$id)->first();
	        if($order){
                $dispatch = OrderDeliveryModel::where('id',$id)->where('status',1)->first();
                $buyer = getUser($order->buyer_id);
                $supplier = getUser($order->supplier_id);
                $transport = getTransportDetail($order->transport_one_id);
               // $pdfdata = array('order' => $order,'buyer' => $buyer, 'supplier' => $supplier,'dispatch' => $dispatched, 'transport' => $transport);
 	        	return view('supplier.orders.showout', compact('order','buyer', 'supplier','dispatch', 'transport'));
 	        	 
	        
    	}*/
    	$details  = (new OrderRequester())->order($id);
        return view('supplier.orders.showout')->with('data',$details);
    }
    
    public function print_order($id){
        
        //$details  = OrderModel::select('orders.*','brands.name as brand_name')->leftjoin('brands', 'brands.id', '=', 'orders.brand_id')->where('orders.id', $id)->first();
        $details  = (new OrderRequester())->order($id);
        $orders_data = [];
        foreach ($details->items as $key => $value) {
            $brands = BrandModel::find($value->brand_id);
            $a['id'] = $value->id;
            $a['item_name'] = $value->name;
            $a['brand_id'] = $brands->id;
            $a['brand_name'] = $brands->name;
            $orders_data[$key] = $a;
        }
        $details->items = $orders_data;
        //print_r($details->items); die;
        
        return view('emails.place_order')->with('data',$details);
    }

    public function print_enquiry($id){
        
        
        //$details  = (new OrderRequester())->order($id);
        $details = DB::table('enquiries_data')->leftJoin('users', 'enquiries_data.user_id', '=', 'users.id')->where('enquiries_data.id', $id)
            ->select(['enquiries_data.*', 'users.name as user_firm_name','users.veepeeuser_id'])->first();
            //print_r($details->id); die;
            if($details) {
                $images = EnquiryGalleryModel::where('enquiries_id',$details->id)->get();
                
                    $Gimages=[];
                    if($images){
                        foreach ($images as $key1=>$row) {
                            
                            $Gimages[$key1]=url('/images/enquiry_request/'.$row['image_name']);    
                        }
                    }
                    //print_r($Gimages); die;
                    //$details['image'] = $Gimages;
                    $dataa = array(
                        'data' => $details,
                        'image' => $Gimages
                    );
            }
            //print_r($dataa); die;
        return view('emails.enquiry_print')->with('data',$dataa);
    }
    
     public function testEmails(){  
         //mail('sotamk@synapseco.com','subjecttt','my message');
        try{ 
            $data = ["name"=>"sotam", "data"=>"Hello Sotam"];
            $user["to"] = "sotamk@synapseco.com";
            Mail::send('mail',$data,function($message) use($user){
                $message->to("sotamk@synapseco.com");
                $message->subject("Hello Sotam");
            });
            //dd(Mail::failures());
            print_r("done vvv");
        }
        catch(\Exception $e){
            echo $e->getMessage(); die;
        }
    }
    function sendOrderDetailMail($order_id) {
        OrderNotification::dispatch($order_id);
    }
     
}
