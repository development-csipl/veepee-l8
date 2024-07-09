<?php
namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\OrderModel;
use App\Models\TransportsModels;
use App\Models\OrderTrackingModel;
use App\Models\OrderCancelModel;
use App\Models\BrandModel;
use App\Models\OrderDeliveryModel;
use App\Requesters\OrderRequester;
use Gate;
use Str;
use DB;
use Auth;
use App\User;
use Mail;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
 
class OrderController extends Controller{


    public function index(Request $request){
         //print_r($request->all()); die;
       // print_r(trans('email.accept_order')); die;
       $params = $request->all();
        $user = User::where('id',Auth::id())->first();

        $cancel_order = OrderCancelModel::where('supplier_id',$user->id)->get('order_id');

        if(@$user->user_type == 'supplier'){
             $orders1 = User::join('orders', 'orders.supplier_id', '=', 'users.id')
            ->select('users.*', 'orders.*');

            //$orders1->whereNotIn('id',$cancel_order);
            if($request->name != ''){
                $orders1->where('users.name','like','%'.$request->name.'%');
            }


            if($request->veepeeuser_id != ''){
                $orders1->where('users.veepeeuser_id',$request->veepeeuser_id);
            }

            if($request->veepee_order_number != ''){
                $orders1->where('orders.vporder_id',$request->veepee_order_number);
            }

            if($request->start_date != '' && $request->end_date != ''){
               $orders1->where('orders.created_at','>=',date('Y-m-d 23:59:59',strtotime($request->start_date)))->where('orders.created_at','<=',date('Y-m-d 23:59:59',strtotime($request->end_date)));
            }
            if($request->status == 'orderDetails'){
                $orders1->whereIn('orders.status',['Confirm','Processing']);
                //$orders1->where('orders.status','Confirm');
            }
           if($request->status != '' && $request->status != 'orderDetails'){
                $orders1->where('orders.status',$request->status);
            }else{
                $orders1->whereIn('orders.status',['Confirm','Processing']);
            }
            
        	$user_id =  Auth::id();
	       	$orders = $orders1->where('supplier_id',$user_id)->orderby('orders.status','DESC')->paginate(20);
            $orders->appends($params);
	        return view('supplier.orders.index', compact('orders','params'));
        }
        
    }

    public function edit($id)
    {
        abort_if(Gate::denies('order_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$order = OrderModel::where('id','=',$id)->first();
        $suppliers = User::where('branch_id',$order->branch_id)->where('user_type','supplier')->get();
        $transports = TransportsModels::where('branch_id',$order->branch_id)->where('status',1)->get('transport_name','id');
        return view('admin.orders.edit', compact('order','suppliers','transports'));
    }

    public function update($id){
        $OrderModel = OrderModel::find($id);
        $OrderModel->update(['name' => $request->name]);
        return redirect()->route('admin.orders.index');
    }

 
    
    public function accept(Request $request, $id){
    	$user = Auth::user();
        if($user->user_type === 'supplier'){
        	$order = OrderModel::where('supplier_id',$user->id)->where('supplier_accept',NULL)->where('id',$id)->first();
            //print_r($order); die;
        	if(isset($order->id)){
	        	if($request->all()){
                    $buyer_sms = getBuyers($order->buyer_id);
                    $buyer      = getUser($order->buyer_id);
                    $supplier   = $user;
                    if($request->order_amount <= getSiteData()->min_order_amount){
                        $msg  = 'We can not accept this order because of minimum amount order. Order amount should be atleast INR '.getSiteData()->min_order_amount;  
                        $data = array('name' =>$buyer->name,  'msg' => $msg );
                        Mail::send('emails.accept_reject_order',$data,function($message) use($buyer) {
                            $message->to($buyer->email)->subject('Veepee Internatonal- Order Status Changed');
                        });
                        /*push to buyer and supplier*/
                        notifyAndroid([$order->buyer_id,$supplier->id],'Veepee Internatonal',  $msg);
                        //send whatsapp notification
                        $whatsappTemplate = 'veepe_ord';
                        $whatsappParameters = array(
                        array(
                            "name" => "veepee_msg",
                            "value" => $msg,
                            ),
                        );
                        whatsappCurl($whatsappTemplate, $buyer_sms->notify_whatsapp, $whatsappParameters);
                        return redirect()->route('supplier.orders.index')->withError($msg);
                    }else{
                        $msg = 'Your order has been accepted by supplier. Order price : '. $request->order_amount.' and number of cases are '.$request->pkt_cases;
                        $order->update(['supplier_id' => $request->sister_firm,'order_amount' => $request->order_amount, 'spend_order_amount' => 0, 'pkt_cases' => $request->pkt_cases, 'supplier_accept' => 1,'status' => 'Waiting for approval']);
                        OrderTrackingModel::create(['order_id' => $order->id, 'event' =>'Waiting for approval','status' => 'Accepted', 'user_id' => $user->id]);   

                        $data = array('name' =>$buyer->name,  'msg' => $msg );
                        Mail::send('emails.accept_reject_order',$data, function ($message) use($buyer) {
                            $message->to($buyer->email)->subject('Veepee Internatonal- Order Status Changed');
                        });

                        /*push to buyer and supplier*/
                        notifyAndroid([$order->buyer_id,$supplier->id],'Veepee Internatonal',  $msg);
                        //send whatsapp notification
                        $whatsappTemplate = 'veepe_ord';
                        $whatsappParameters = array(
                        array(
                            "name" => "veepee_msg",
                            "value" => $msg,
                            ),
                        );
                        whatsappCurl($whatsappTemplate, $buyer_sms->notify_whatsapp, $whatsappParameters);
                        return redirect()->route('supplier.orders.index')->withSuccess($msg);
                    }
	        	}else {
	        		$status = 'accept';
                    $firm = User::where('id',$user->id)->first();
                    $sisterfirms = $firm->supplier->sister_firm;
	        		return view('supplier.orders.accept_reject', compact('order','status','sisterfirms','firm'));
	        	}
	        }else{
	        	return redirect()->back();
	        }
        }
    }

    public function reject(Request $request, $id){
        $user = Auth::user();
        if($user->user_type == 'supplier'){
            $order = OrderModel::where('supplier_id',$user->id)->where('supplier_accept',NULL)->where('id',$id)->first();
            if($order){
                if($request->all()){
                    $buyer_sms = getBuyers($order->buyer_id);
                    $buyer = getUser($order->buyer_id);
                    $supplier = $user;

                    $order->update(['supplier_accept' => 0, 'status' => 'Cancelled']);

                    OrderCancelModel::insert([
                                    'order_id'=>$order->id,
                                    'supplier_id'=>$order->supplier_id,
                                    'reason'=>$request->reason,
                                    'status'=>'Rejected',
                                    'cancelled_by'=>$user->id
                    ]);

                    OrderTrackingModel::create(['order_id' => $order->id, 'event' => $request->reason,'status' => 'Cancelled', 'user_id' => $user->id]);

                    $data = array('name' =>$buyer->name,  'msg' => 'Order has been rejected by supplier. Please place new order.' );
                    Mail::send('emails.accept_reject_order',$data, function ($message) use($buyer) {
                        $message->to($buyer->email)->subject('Veepee Internatonal- Order Rejected');
                    });

                        /*push to buyer and supplier*/
                    notifyAndroid([$order->buyer_id,$supplier->id],'Veepee Internatonal', 'Order request has been rejected.');
                    //send whatsapp notification
                    $whatsappTemplate = 'veepe_ord';
                    $whatsappParameters = array(
                    array(
                        "name" => "veepee_msg",
                        "value" => "Order request has been rejected.",
                        ),
                    );
                    whatsappCurl($whatsappTemplate, $buyer_sms->notify_whatsapp, $whatsappParameters);

                    return redirect()->route('supplier.orders.index')->withSuccess('Order request has been rejected successfully.');


                } else {
                    $status = 'reject';
                    return view('supplier.orders.accept_reject', compact('order','status'));
                }
            } else {
                return redirect()->back()->withError('We are not able to reject this order. Please try again later.');
            }
        }
    }

    public function show($id){
    	$user = Auth::user();
        if($user->user_type == 'supplier'){
	        /*$order = OrderModel::where('supplier_id',$user->id)->where('id',$id)->first();
	        if($order){
                $dispatch = OrderDeliveryModel::where('id',$id)->where('status',1)->first();
                $buyer = getUser($order->buyer_id);
                $supplier = getUser($order->supplier_id);
                $transport = getTransportDetail($order->transport_one_id);
               // $pdfdata = array('order' => $order,'buyer' => $buyer, 'supplier' => $supplier,'dispatch' => $dispatched, 'transport' => $transport);
 	        	return view('supplier.orders.show', compact('order','buyer', 'supplier','dispatch', 'transport'));
 	        	
 	        	
	        } else {
	        	return redirect()->back();
	        }*/
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
        return view('supplier.orders.show')->with('data',$details); 
    	}
    }
    public function add_delivery(Request $request){
        return $request->all();
        $order = OrderModel::where('id','=',$request->id)->first();
        /*check order cases completed or not*/
        //print_r(checkOrderStatus($id)); die;
        if(checkOrderStatus($request->id) == false){
            
            if($request->all()){
                
            $user_id = Auth::id();

            $delivered_case = OrderDeliveryModel::where('order_id',$request->id)->where('status',1)->sum('no_of_case');
            
            
            $delivered_case_amount  = OrderDeliveryModel::where('order_id',$request->id)->where('status',1)->sum('price');
            $remaining_case         = $order->pkt_cases-$delivered_case;
            $remaining_amount       = $order->order_amount-$delivered_case_amount;
            
            $validation_rule = Validator::make($request->all(), [
                'no_of_case'=>'lte:'.$remaining_case,
                'transport_bill'=>'required',
                'supplier_bill'=>'required',
            ]);
            
            $validation_rule->validate();
            
            
            $delivery = OrderDeliveryModel::create(['order_id' => $order->id,'created_by' => $user_id]);
            $destinationPath = public_path('/images/order_request');

            if ($files = $request->file('order_form')) {
                $data = array('order_id' => $order->id, 'event' => 'Order form uploaded', 'user_id' => $user_id, 'status' => 'File Uploaded', 'delivery_id' => $delivery->id,'flag' => 1);
                $order_form = $this->saveImage($request->file('order_form'),$destinationPath,$data);
             }

            if ($files = $request->file('supplier_bill')) {
                $data = array('order_id' => $order->id, 'event' => 'Order form uploaded', 'user_id' => $user_id, 'status' => 'File Uploaded', 'delivery_id' => $delivery->id,'flag' => 1);
               $supplier_bill = $this->saveImage($request->file('supplier_bill'),$destinationPath,$data);
            }

            if ($files = $request->file('transport_bill')) {
                 $data = array('order_id' => $order->id, 'event' => 'Transport bill uploaded','delivery_id' => $delivery->id, 'user_id' => $user_id, 'status' => 'File Uploaded', 'flag' => 1);
                $transport_bill = $this->saveImage($request->file('transport_bill'),$destinationPath,$data);
                
            }
            if ($files = $request->file('eway_bill')) {
                $data = array('order_id' => $order->id, 'event' => 'E-Way bill uploaded', 'user_id' => $user_id,'delivery_id' => $delivery->id, 'status' => 'File Uploaded', 'flag' => 1);
               $eway_bill = $this->saveImage($request->file('eway_bill'),$destinationPath,$data);
               
           }

           if ($files = $request->file('credit_note')) {
                $data = array('order_id' => $order->id, 'event' => 'Credit note uploaded', 'user_id' => $user_id,'delivery_id' => $delivery->id, 'status' => 'File Uploaded', 'flag' => 1);
               $credit_note = $this->saveImage($request->file('credit_note'),$destinationPath,$data);
  
           }

           $data = array(
                    'order_id' =>$order->id,
                    'no_of_case' => $request->no_of_case ?? $delivery->no_of_case,
                    'order_form' => @$order_form ?? $delivery->order_form,
                    'supplier_bill' => @$supplier_bill ?? $delivery->supplier_bill,
                    'transport_bill' => @$transport_bill ?? $delivery->transport_bill,
                    'eway_bill' => @$eway_bill ?? $delivery->eway_bill,
                    'credit_note' => @$credit_note ?? $delivery->credit_note,       
             );
            $delivery->update($data);
            $order->update(['status' =>$request->status ]);
            $result['status'] = true;
            $result['message'] = 'This order has been created successfully';
            return $result;

            } else {
                $result['status'] = false;
                $result['message'] = 'Somthing want wrong';
                return $result;
            }
        } else {
            $result['status'] = true;
            $result['message'] = 'All cases for this order has been dispatched successfully.So you are not able to create new delivery.';
            return $result;
        }
    }
    public function saveImage($file,$destinationPath,$data){
        $filename = '';
        if($file != '' || NULL){
            $catalog = $file;
            $filename = $catalog->getClientOriginalName();
            //$filename = time().''.uniqid().'.'.$catalog->getClientOriginalExtension();
            $catalog->move($destinationPath, $filename);
        }
        return $filename;
    }
    
    public function six_month_history(Request $request, $supplier_id){
        // $orders = OrderModel::where('supplier_id',$supplier_id)->where('orders.created_at','>=',date('Y-m-d 00:00:00',strtotime('-6 month')))->where('orders.created_at','<=',date('Y-m-d 23:59:59'))
        // ->whereIn('status',['Cancelled','Completed'])->orderby('created_at','desc')->paginate(20);
        // return view('supplier.orders.last_six_month_history',compact('orders'));

        $orders1 = OrderModel::join('users', 'users.id', '=', 'orders.supplier_id')->where('orders.supplier_id',$supplier_id)->where('orders.created_at','>=',date('Y-m-d 00:00:00',strtotime('-6 month')))->where('orders.created_at','<=',date('Y-m-d 23:59:59'))
        ->whereIn('orders.status',['Cancelled','Completed'])->select('orders.*');

        // $orders1 = User::join('orders', 'orders.supplier_id', '=', 'users.id')
         //->select('orders.*');
        // $orders1 = User::join('users', 'users.id', '=', 'orders.supplier_id')
        // ->select('orders.*');

        if($request->name != ''){
            $orders1->where('users.name','like','%'.$request->name.'%');
        }

        if($request->veepeeuser_id != ''){
            $orders1->where('users.veepeeuser_id',$request->veepeeuser_id);
        }
        if($request->vporder_id != ''){
            $orders1->where('orders.vporder_id',$request->vporder_id);
        }
        $orders = $orders1->orderby('orders.created_at','desc')->paginate(20);
        return view('supplier.orders.last_six_month_history',compact('orders'));
        
    }

}