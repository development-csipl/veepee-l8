<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
//use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser;
use App\Models\{BranchModel,TransportsModels,BrandModel,SuppliersModels,ItemModel,OrderModel,OrderItemModel,OrderGalleryModel,OrderTrackingModel,OrderCancelModel,OrderDeliveryModel,RejectReasonModel,BuyerModel,SiteInfoModel,CourierModel};
use App\Models\{OrderConfigurationModel};
use App\Jobs\{OrderNotification,OrderSmsNotification,SMSDeliveryNotification};
use App\User;
use Carbon\Carbon;
use DB;
use Hash;
use Mail;
use Auth;
use File;
use Laravel\Passport\Token;
use Requests;
use YoastSEO_Vendor\GuzzleHttp\Psr7\Request as Psr7Request;

class OrdersController extends Controller{

    public function CreateOrdersOld(Request $request){

        $value = $request->bearerToken();
        //$id = (new Parser())->parse($value)->getHeader('jti');
        $id = (new Parser(new JoseEncoder()))->parse($value)->claims()->all()['jti']; 
        $user_id = Token::find($id)->user_id;
 
        if($user_id){
            $validator = Validator::make($request->all(), [
                                        'Supplier_ID' => 'required',
                                        'Branch_ID' => 'required',
                                        'Brand_ID' => 'required',
                                        'TransportOne_ID' => 'required',
                                        'TransportTwo_ID' => 'required',
                                        'SupplyStartDate' => 'required',
                                        'OrderLastDate' => 'required',
                                        'Station' => 'required',
                                        'order_item' => 'required'
                                    ]);
                if($validator->fails()){
                    $response['message'] = $validator->messages()->first();
                   return response()->json(['response' => $response,'status' => false],401); 
                } else {
                  $userr          =  User::where('id',$user_id)->first();  
                    $amount         =  speneded_amount($user_id);
             
            
            $data           =  busy($userr->veepeeuser_id);
            $Samount        =  speneded_amount($user_id);
            $Oamount        =  ordered_amount($user_id);
            $remaining      =  ($data->amount-($Oamount-$Samount));
          
            //$Bamount     =  busy($userr->veepeeuser_id);
            //$remaining   =  $Bamount->amount-$amount;
            
            if($request->OrderAmount > $remaining){
               $response['message'] = 'Order amount should be less then '.$remaining;
                   return response()->json(['response' => $response,'status' => false],401);
                   exit;
            }
             
            if(50000 > $remaining){
                $response['message'] = 'Order amount should be atleast 50000';
                   return response()->json(['response' => $response,'status' => false],401);
                   exit;
            }
            
            if($remaining<50000){
                $response['message'] = 'Remaining amount should be atleast 50000';
                   return response()->json(['response' => $response,'status' => false],401);
                   exit;
            }
                    
                    
                    
                    
                    
                    
                  @$lastorder = OrderModel::latest()->first('id');
                  $oid = ($lastorder->id ?? 0)+1;
                  
                  $branch = strtoupper(substr(getBranch($request->Branch_ID),0,3));
                  if($request->order_id!='' || $request->order_id!=0){
                      $order = OrderModel::where('id',$request->order_id)->first();
                      $data = array( 
                                    'buyer_id' => $user_id,
                                    'supplier_id' => $request->Supplier_ID,
                                    'branch_id' => $request->Branch_ID,
                                    'brand_id' => $request->Brand_ID,
                                    'marka' => $request->Marka,
                                    'transport_one_id' => $request->TransportOne_ID,
                                    'transport_two_id' => $request->TransportTwo_ID,
                                    'supply_start_date' => date('Y-m-d h:i:s',strtotime($request->SupplyStartDate)),
                                    'orderlast_date' => date('Y-m-d h:i:s',strtotime($request->OrderLastDate)),
                                    'station' => $request->Station,
                                    'pkt_cases' => $request->CaseNo,
                                    'remaining_pkt' => NULL,
                                    'order_amount' => $request->OrderAmount,
                                    'status' => $request->Status,
                                    'users_id'=>$user_id,
                                    'reason' => null,
                                    'face'=>'app',
                                    'merchandiser_name'=> $request->merchandiser_name,
                                    'order_date' => date('Y-m-d',strtotime($request->OrderDate)),
                                    'created_at' => date('Y-m-d h:i:s')
                      );

            $order = $order->update($data);
             $order_cancelModel = DB::table('order_cancel')->where('order_id',$request->order_id)->delete();

            foreach ($request->order_item as $row) {
                $OrderModel = DB::table('order_items')->where('order_id',$request->order_id)->delete();
                OrderItemModel::create(['order_id' =>  $request->order_id, 'item_id' => $row['Item_ID'], 'name' => $row['Name'], 'quantity' => $row['Qty'], 'color' => $row['Color'], 'size' => $row['Size'], 'article_no' => $row['ArtNo'],'range'=>$row['range']]);    
            }

            foreach ($request->order_attachment as $row) {
                $OrderGalleryModel = DB::table('orders_gallery')->where('order_id',$request->order_id)->delete();
                $image = $this->createImageFromBase64($row['Links']);
                OrderGalleryModel::create(['order_id' =>  $request->order_id, 'image_name'=> $image]);    
            }
            
            //OrderTrackingModel::create(['order_id' => $order['id'], 'event' =>'New order placed','status' => $request->Status, 'user_id' => $user_id]);
  

            $buyer = getUser($user_id);
            $supplier = getUser($request->Supplier_ID);

            /*notification*/
            $data = array('buyer_name' =>$buyer->name,'supplier_name' => $supplier->name,'link' => route('supplier.orders.show', $request->order_id));
                      
                  }else{
                      @$lastorder = OrderModel::latest()->first('id');
                      $oid = ($lastorder->id ?? 0)+1;
                      if($lastorder->id==$oid){
                    $oid = ($lastorder->id ?? 0)+1;  
                  }
                    $data = array('vporder_id' => 'VP'.$branch.'-'.$oid,
                                    'buyer_id' => $user_id,
                                    'supplier_id' => $request->Supplier_ID,
                                    'branch_id' => $request->Branch_ID,
                                    'brand_id' => $request->Brand_ID,
                                    'marka' => $request->Marka,
                                    'transport_one_id' => $request->TransportOne_ID,
                                    'transport_two_id' => $request->TransportTwo_ID,
                                    'supply_start_date' => date('Y-m-d h:i:s',strtotime($request->SupplyStartDate)),
                                    'orderlast_date' => date('Y-m-d h:i:s',strtotime($request->OrderLastDate)),
                                    'station' => $request->Station,
                                    'pkt_cases' => $request->CaseNo,
                                    'remaining_pkt' => NULL,
                                    'order_amount' => $request->OrderAmount,
                                    'status' => $request->Status,
                                    'users_id'=>$user_id,
                                    'face'=>'app',
                                    'merchandiser_name'=> $request->merchandiser_name,
                                    'order_date' => date('Y-m-d',strtotime($request->OrderDate))
                      );

            $order = OrderModel::create($data);

            foreach ($request->order_item as $row) {
                OrderItemModel::create(['order_id' =>  $order['id'], 'item_id' => $row['Item_ID'], 'name' => $row['Name'], 'quantity' => $row['Qty'], 'color' => $row['Color'], 'size' => $row['Size'], 'article_no' => $row['ArtNo'],'range'=>$row['range']]);    
            }

            foreach ($request->order_attachment as $row) {
                $image = $this->createImageFromBase64($row['Links']);
                OrderGalleryModel::create(['order_id' =>  $order['id'], 'image_name'=> $image]);    
            }
            
            //OrderTrackingModel::create(['order_id' => $order['id'], 'event' =>'New order placed','status' => $request->Status, 'user_id' => $user_id]);
  

            $buyer = getUser($user_id);
            $supplier = getUser($request->Supplier_ID);

            /*notification*/
            $data = array('buyer_name' =>$buyer->name,'supplier_name' => $supplier->name,'link' => route('supplier.orders.show', $order['id']));
         }

            
            /*email to supplier*/
           /*Mail::send('emails.got_new_order',$data, function ($message) use($supplier) {
                $message->to($supplier->email)->subject('Veepee Internatonal- One more new order');
            });*/

            /*email to buyer*/
          /*Mail::send('emails.order_submitted',$data, function ($message) use($buyer) {
                $message->to($buyer->email)->subject('Veepee Internatonal- Order Submitted');
            });*/
            
             

            /*push to supplier*/
            notifyAndroid([$request->Supplier_ID],'Veepee Internatonal','You have got one new order');
            /*push to supplier*/
            notifyAndroid([$user_id],'Veepee Internatonal','You order request has been sent to the supplier successfully.');
            
             
            
                return response()->json(['message'=> 'Order has been created successfully','active' => $buyer->status,'block' => $buyer->block,'status' => true],200); 
            }

            } else {
                return response()->json(['message'=> 'Token not matched', 'status' => true],200); 
            }
    }
    
    public function CreateOrders(Request $request){
        
        $value = $request->bearerToken();
        //$id = (new Parser())->parse($value)->getHeader('jti');
        $id = (new Parser(new JoseEncoder()))->parse($value)->claims()->all()['jti']; 
        $user_id = Token::find($id)->user_id;
 
        if($user_id){
            $validator = Validator::make($request->all(), [
                                        'Supplier_ID' => 'required',
                                        'Branch_ID' => 'required',
                                        'Brand_ID' => 'required',
                                        'TransportOne_ID' => 'required',
                                        'TransportTwo_ID' => 'required',
                                        'SupplyStartDate' => 'required',
                                        'OrderLastDate' => 'required',
                                        'Station' => 'required',
                                        'order_item' => 'required'
                                    ]);
                if($validator->fails()){
                    $response['message'] = $validator->messages()->first();
                   return response()->json(['response' => $response,'status' => false],401); 
                } else {
                  $userr          =  User::where('id',$request->Buyer_ID)->first();  
                    $amount         =  speneded_amount($user_id);
                    $data           =  busy($userr->veepeeuser_id);
                    $Samount        =  speneded_amount($user_id);
                    $Oamount        =  ordered_amount($user_id);
                    $remaining      =  ($data->amount-($Oamount-$Samount));
          
            //$Bamount     =  busy($userr->veepeeuser_id);
            //$remaining   =  $Bamount->amount-$amount;
            //print_r($userr->veepeeuser_id); die;
            // if($request->amount > $remaining){
            //    $response['message'] = 'Order amount should be less then '.$remaining;
            //        return response()->json(['response' => $response,'status' => false],401);
            //        exit;
            // }
             
            // if(50000 > $remaining){
            //     $response['message'] = 'Order amount should be atleast 50000';
            //        return response()->json(['response' => $response,'status' => false],401);
            //        exit;
            // }
            
            // if($remaining<50000){
            //     $response['message'] = 'Remaining amount should be atleast 50000';
            //        return response()->json(['response' => $response,'status' => false],401);
            //        exit;
            // }
                     
                    
                  @$lastorder = OrderModel::latest()->first('id');
                  $oid = ($lastorder->id ?? 0)+1;
                  //print_r($request->Branch_ID); die;
                  $branch = strtoupper(substr(getBranch($request->Branch_ID),0,3));
                  if($request->order_id!='' || $request->order_id!=0){
                      $order = OrderModel::where('id',$request->order_id)->first();
                      $data = array( 
                                    'buyer_id' => $userr->user_id,
                                    'supplier_id' => $request->Supplier_ID,
                                    'branch_id' => $request->Branch_ID,
                                    'brand_id' => $request->Brand_ID,
                                    'marka' => $request->Marka,
                                    'transport_one_id' => $request->TransportOne_ID,
                                    'transport_two_id' => $request->TransportTwo_ID,
                                    'supply_start_date' => date('Y-m-d h:i:s',strtotime($request->SupplyStartDate)),
                                    'orderlast_date' => date('Y-m-d h:i:s',strtotime($request->OrderLastDate)),
                                    'station' => $request->Station,
                                    'pkt_cases' => $request->case_no,
                                    'remaining_pkt' => NULL,
                                    'order_amount' => $request->amount,
                                    'status' => $request->Status,
                                    'users_id'=>$user_id,
                                    'reason' => null,
                                    'face'=>'app',
                                    'color_size_range_etc'=>$request->color_size_range_etc,
                                    'optional'=>$request->optional,
                                    'case_no'=>$request->case_no,
                                    'amount'=>$request->amount,
                                    'remark_by_customer'=>$request->remark_by_customer,
                                    'order_by'=>$request->order_by,
                                    'merchandiser_name'=> $request->merchandiser_name,
                                    'order_date' => date('Y-m-d',strtotime($request->OrderDate)),
                                    'created_at' => date('Y-m-d h:i:s')
                      );

            $order = $order->update($data);
             $order_cancelModel = DB::table('order_cancel')->where('order_id',$request->order_id)->delete();

            foreach ($request->order_item as $row) {
                $OrderModel = DB::table('order_items')->where('order_id',$request->order_id)->delete();
                OrderItemModel::create(['order_id' =>  $request->order_id, 'item_id' => $row['Item_ID'], 'brand_id' => $row['brand_id'], 'brand_name' => $row['brand_name'], 'name' => $row['Name'], 'quantity' => $row['Qty'], 'color' => $row['Color'], 'size' => $row['Size'], 'article_no' => $row['ArtNo'],'range'=>$row['range']]);    
            }

            foreach ($request->order_attachment as $row) {
                $OrderGalleryModel = DB::table('orders_gallery')->where('order_id',$request->order_id)->delete();
                $image = $this->createImageFromBase64($row['Links']);
                OrderGalleryModel::create(['order_id' =>  $request->order_id, 'image_name'=> $image]);    
            }
            
            //OrderTrackingModel::create(['order_id' => $order['id'], 'event' =>'New order placed','status' => $request->Status, 'user_id' => $user_id]);
  

            $buyer = getUser($user_id);
            $supplier = getUser($request->Supplier_ID);

            /*notification*/
            $data = array('buyer_name' =>$buyer->name,'supplier_name' => $supplier->name,'link' => route('supplier.orders.show', $request->order_id));
                      
                  }else{
                        //       @$lastorder = OrderModel::latest()->first('id');
                        //       $oid = ($lastorder->id ?? 0)+1;
                        //       if($lastorder->id==$oid){
                        //     $oid = ($lastorder->id ?? 0)+1;  
                        //   }
                    $LastOrder  =    OrderConfigurationModel::select('order_increment_id')->where(['status'=>'1'])->first();//,'financial_year'=>'24-25'
                    $oid= ($LastOrder->order_increment_id + 1);
                    $vpOrderId = 'VP'.$branch.'-'.$oid;
                    $data = array('vporder_id' => $vpOrderId,//'VP'.$branch.'-'.$oid,
                                    'buyer_id' => $request->Buyer_ID,
                                    'supplier_id' => $request->Supplier_ID,
                                    'branch_id' => $request->Branch_ID,
                                    'brand_id' => $request->Brand_ID,
                                    'marka' => $request->Marka,
                                    'transport_one_id' => $request->TransportOne_ID,
                                    'transport_two_id' => $request->TransportTwo_ID,
                                    'supply_start_date' => date('Y-m-d h:i:s',strtotime($request->SupplyStartDate)),
                                    'orderlast_date' => date('Y-m-d h:i:s',strtotime($request->OrderLastDate)),
                                    'station' => $request->Station,
                                    // 'pkt_cases' => $request->CaseNo,
                                    'pkt_cases' => $request->case_no,
                                    'remaining_pkt' => NULL,
                                    //'order_amount' => $request->OrderAmount,
                                    'order_amount' => $request->amount,
                                    'status' => $request->Status,
                                    'users_id'=>$user_id,
                                    'face'=>'app',
                                    'color_size_range_etc'=>$request->color_size_range_etc,
                                    'optional'=>$request->optional,
                                    'case_no'=>$request->case_no,
                                    'amount'=>$request->amount,
                                    //'order_otp'=>mt_rand(100000, 999999),
                                    'remark_by_customer'=>$request->remark_by_customer,
                                    'order_by'=>$request->order_by,
                                    'merchandiser_name'=> $request->merchandiser_name,
                                    'order_date' => date('Y-m-d',strtotime($request->OrderDate))
                      );
            
            $loginUser = getUser($user_id);
            //$data['order_by'] = 0;
            if($loginUser->user_type == "supplier"){
                $data['supplier_status_flag'] = 1;
                $data['status'] = 'Waiting for approval';
                //$data['order_by'] = 1;

                //$msg = 'we get login request from your Veepee account. OTP : '.$data['order_otp'];
                //send_sms(8871025543, $msg);
                //send_sms($phonenumber, $msg);
            }
            //print_r($data);die;
            $order = OrderModel::create($data);
            OrderConfigurationModel::where('status',1)->update(['order_increment_id'=>$oid]);
            $datau = array('vporder_id' => $vpOrderId); 
            //OrderModel::where('id', $order->id)->update(['vporder_id' => 'VP'.$branch.'-'.$order->id]);
            OrderModel::where('id', $order->id)->update($datau);

            foreach ($request->order_item as $row) {
                OrderItemModel::create(['order_id' =>  $order['id'], 'item_id' => $row['Item_ID'], 'brand_id' => $row['brand_id'], 'brand_name' => $row['brand_name'], 'name' => $row['Name'], 'quantity' => $row['Qty'], 'color' => $row['Color'], 'size' => $row['Size'], 'article_no' => $row['ArtNo'],'range'=>$row['range']]);    
            }

            foreach ($request->order_attachment as $row) {
                $image = $this->createImageFromBase64($row['Links']);
                OrderGalleryModel::create(['order_id' =>  $order['id'], 'image_name'=> $image]);    
            }
            
            //OrderTrackingModel::create(['order_id' => $order['id'], 'event' =>'New order placed','status' => $request->Status, 'user_id' => $user_id]);
  

            $buyer = getUser($request->Buyer_ID);
            $supplier = getUser($request->Supplier_ID);

            /*notification*/
            $data = array('buyer_name' =>$buyer->name,'supplier_name' => $supplier->name,'link' => route('supplier.orders.show', $order['id']));
         }

            
            /*email to supplier*/
           /*Mail::send('emails.got_new_order',$data, function ($message) use($supplier) {
                $message->to($supplier->email)->subject('Veepee Internatonal- One more new order');
            });*/

            /*email to buyer*/
          /*Mail::send('emails.order_submitted',$data, function ($message) use($buyer) {
                $message->to($buyer->email)->subject('Veepee Internatonal- Order Submitted');
            });*/
            
                $supplier_sms = getSuplliers($request->Supplier_ID);
                $buyer_sms = getBuyers($request->Buyer_ID);
                /*push to supplier*/
                notifyAndroid([$request->Supplier_ID],'Veepee Internatonal','You have got one new order');
                //send whatsapp notification
                $whatsappTemplate = 'veepe_ord';
                $whatsappParameters = array(
                array(
                    "name" => "veepee_msg",
                    "value" => 'You have got one new order',
                    ),
                );
                whatsappCurl($whatsappTemplate, $supplier_sms->notify_whatsapp, $whatsappParameters);
                 /*push to supplier*/
                notifyAndroid([$request->Buyer_ID],'Veepee Internatonal','You order request has been sent to the supplier successfully.');
                 //send whatsapp notification
                $whatsappParameters = array(
                array(
                    "name" => "veepee_msg",
                    "value" => 'You order request has been sent to the supplier successfully.',
                    ),
                );
                whatsappCurl($whatsappTemplate, $buyer_sms->notify_whatsapp, $whatsappParameters);
            
             
            
                return response()->json(['message'=> 'Order has been created successfully','active' => $buyer->status,'block' => $buyer->block,'status' => true,'order_id' => $order['vporder_id']],200); 
            }

            } else {
                return response()->json(['message'=> 'Token not matched', 'status' => true],200); 
            }
    }
    
    public function repeatOrders(Request $request){
        $value = $request->bearerToken();
        //$id = (new Parser())->parse($value)->getHeader('jti');
        $id = (new Parser(new JoseEncoder()))->parse($value)->claims()->all()['jti']; 
        $user_id = Token::find($id)->user_id;
        if($user_id){
            $validator = Validator::make($request->all(), [
                                        'order_id' => 'required',
                                        'SupplyStartDate' => 'required',
                                        'OrderLastDate' => 'required',
                                    ]);
                if($validator->fails()){
                    $response['message'] = $validator->messages()->first();
                   return response()->json(['response' => $response,'status' => false],401); 
                } else {
                    
                    $userr          =  User::where('id',$repeatorder->buyer_id)->first();  
                    $amount         =  speneded_amount($repeatorder->buyer_id);
             
            
            $data           =  busy($userr->veepeeuser_id);
            $Samount        =  speneded_amount($repeatorder->buyer_id);
            $Oamount        =  ordered_amount($repeatorder->buyer_id);
            $remaining      =  ($data->amount-($Oamount-$Samount));
          
            //$Bamount     =  busy($userr->veepeeuser_id);
            //$remaining   =  $Bamount->amount-$amount;
            
            if($request->OrderAmount > $remaining){
               $response['message'] = 'Order amount should be less then '.$remaining;
                   return response()->json(['response' => $response,'status' => false],401);
                   exit;
            }
             
            if(50000 > $remaining){
                $response['message'] = 'Order amount should be atleast 50000';
                   return response()->json(['response' => $response,'status' => false],401);
                   exit;
            }
            
            if($remaining<50000){
                $response['message'] = 'Remaining amount should be atleast 50000';
                   return response()->json(['response' => $response,'status' => false],401);
                   exit;
            }
                    
                    
                    
                    
                  @$lastorder = OrderModel::latest()->first('id');
                  $oid = ($lastorder->id ?? 0)+1;
                $LastOrder  =    OrderConfigurationModel::select('order_increment_id')->where(['status'=>'1'])->first();//,'financial_year'=>'24-25'
                $oid= ($LastOrder->order_increment_id + 1);
                $vpOrderId = 'VP'.$branch.'-'.$oid;        
                  $repeatorder =  OrderModel::where('id',$request->order_id)->first();
                  $branch = strtoupper(substr(getBranch($repeatorder->branch_id),0,3));
                    $data = array('vporder_id' => $vpOrderId,//'VP'.$branch.'-'.$oid,
                                    'buyer_id' => $repeatorder->buyer_id,
                                    'supplier_id' => $repeatorder->supplier_id,
                                    'branch_id' => $repeatorder->branch_id,
                                    'brand_id' => $repeatorder->brand_id,
                                    'marka' => $repeatorder->marka,
                                    'transport_one_id' => $repeatorder->transport_one_id,
                                    'transport_two_id' => $repeatorder->transport_two_id,
                                    'supply_start_date' => date('Y-m-d h:i:s',strtotime($request->SupplyStartDate)),
                                    'orderlast_date' => date('Y-m-d h:i:s',strtotime($request->OrderLastDate)),
                                    'station' => $repeatorder->station,
                                    'pkt_cases' => $repeatorder->pkt_cases,
                                    'remaining_pkt' => NULL,
                                    'order_amount' => $repeatorder->order_amount,
                                    'status' => NULL,
                                    'merchandiser_name'=> $repeatorder->merchandiser_name,
                                    'order_date' => date('Y-m-d',strtotime($repeatorder->order_date))
                      );

            $order = OrderModel::create($data);
            OrderConfigurationModel::where('status',1)->update(['order_increment_id'=>$oid]);
            $items = OrderItemModel::where('order_id',$request->order_id)->get();

            foreach ($items as $row) {
                OrderItemModel::create(['order_id' =>  $order['order_id'], 'item_id' => $row['item_id'], 'name' => $row['name'], 'quantity' => $row['quantity'], 'color' => $row['color'], 'size' => $row['size'], 'article_no' => $row['article_no'],'range'=>$row['range']]);    
            }

            $images = OrderGalleryModel::where('order_id',$request->order_id)->get();
            foreach ($images as $row) {
                
                OrderGalleryModel::create(['order_id' =>  $row['id'], 'image_name'=> $row['image_name']]);    
            }

            OrderTrackingModel::create(['order_id' => $order['id'], 'event' =>'New order placed','status' => $request->Status, 'user_id' => $user_id]);


            $buyer = getUser($user_id);
            $supplier = getUser($request->Supplier_ID);

            /*notification*/
            $data = array('buyer_name' =>$buyer->name,'supplier_name' => $supplier->name,'link' => route('supplier.orders.show', $order['id']));
            /*email to supplier*/
            Mail::send('emails.got_new_order',$data, function ($message) use($supplier) {
                $message->to($supplier->email)->subject('Veepee Internatonal- One more new order');
            });

            /*email to buyer*/
            Mail::send('emails.order_submitted',$data, function ($message) use($buyer) {
                $message->to($buyer->email)->subject('Veepee Internatonal- Order Submitted');
            });
            
           

                $supplier_sms = getSuplliers($request->Supplier_ID);
                $buyer_sms = getBuyers($request->Buyer_ID);
                /*push to supplier*/
                notifyAndroid([$request->Supplier_ID],'Veepee Internatonal','You have got one new order');
                //send whatsapp notification
                $whatsappTemplate = 'veepe_ord';
                $whatsappParameters = array(
                array(
                    "name" => "veepee_msg",
                    "value" => 'You have got one new order',
                    ),
                );
                whatsappCurl($whatsappTemplate, $supplier_sms->notify_whatsapp, $whatsappParameters);
                 /*push to supplier*/
                notifyAndroid([$request->Buyer_ID],'Veepee Internatonal','You order request has been sent to the supplier successfully.');
                 //send whatsapp notification
                $whatsappParameters = array(
                array(
                    "name" => "veepee_msg",
                    "value" => 'You order request has been sent to the supplier successfully.',
                    ),
                );
                whatsappCurl($whatsappTemplate, $buyer_sms->notify_whatsapp, $whatsappParameters);
            
                return response()->json(['message'=> 'Order has been created successfully','active' => $buyer->status,'block' => $buyer->block,'status' => true],200); 
            }

            } else {
                return response()->json(['message'=> 'Token not matched', 'status' => true],200); 
            }
    }

    protected function createImageFromBase64($image){
       $file_data = $image;
       $file_name = time().''.uniqid() . '.png'; //generating unique file name;
       $destinationPath =public_path('/images/order_request');
       if ($file_data != "") { // storing image in storage/app/public Folder
          // Storage::disk('public')->put($file_name, base64_decode($file_data));
           File::put($destinationPath. '/' . $file_name, base64_decode($file_data));
           return $file_name;
       }
    }

    public function OrderList(Request $request){
        $value = $request->bearerToken();
        //$id = (new Parser())->parse($value)->getHeader('jti');
        $id = (new Parser(new JoseEncoder()))->parse($value)->claims()->all()['jti']; 
        $user_id = Token::find($id)->user_id;
         
        if($user_id){
            $userchk = getUser($user_id);
            $request_mode = $request->request_mode;
            $order_type = $request->order_type;
            $orders_list =[];
            $rejdate = date('Y-m-d',strtotime(date('Y-m-d') .' -365 day'));
            $from = date('Y-m-d',strtotime(date('Y-m-d') .' -180 day'));
            $to =  date('Y-m-d',strtotime(date('Y-m-d'))); //order_date
            if($request_mode == 'supplier'){
                // where('created_at','>=',date('Y-m-d 00:00:00',strtotime('-6 month')))->
                //            where('created_at','<=',date('Y-m-d 23:59:59'))
                // $orders = OrderModel::where('supplier_id',$user_id)->where('status',$order_type)->get();
                 if($order_type=='Rejected'){
                   $orders = OrderModel::where('supplier_id',$user_id)->where('status',$order_type)->where('updated_at','>=', $rejdate)->whereNull('deleted_at')->orderby('orders.created_at','DESC')->get();
                }elseif($order_type=='Waiting for approval'){
                   // print_r($order_type) die;
                    $orders = OrderModel::where('supplier_id',$user_id)->where('status',$order_type)->where('supplier_status_flag',1)->whereNull('deleted_at')->where('created_at','>=',date('Y-m-d 00:00:00',strtotime('-6 month')))->where('created_at','<=',date('Y-m-d 23:59:59'))->orderby('orders.created_at','DESC')->get();
                }elseif($order_type=='Confirm'){
                    $orders = OrderModel::where('supplier_id',$user_id)->whereIn('status',['Confirm','Processing'])->whereNull('deleted_at')->where('created_at','>=',date('Y-m-d 00:00:00',strtotime('-6 month')))->where('created_at','<=',date('Y-m-d 23:59:59'))->orderby('orders.created_at','DESC')->get();
                }else{
                    $orders = OrderModel::where('supplier_id',$user_id)->where('status',$order_type)->whereNull('deleted_at')->where('created_at','>=',date('Y-m-d 00:00:00',strtotime('-6 month')))->where('created_at','<=',date('Y-m-d 23:59:59'))->orderby('orders.created_at','DESC')->get();
                }
                 
                foreach($orders as $key=>$value){
                     $item = OrderItemModel::where('order_id',$value['id'])->get();
                     $delivery = OrderDeliveryModel::orderby('created_at','desc')->where('order_id',$value['id'])->get();
                     $images = OrderGalleryModel::where('order_id',$value['id'])->get();
                     $Gimages=[];
                     foreach ($images as $row) {
                
                $Gimages[]=url('/images/order_request/'.$row['image_name']);    
            }
                    
                    
            
                    $rejected_by = '';
                    if($value['status'] == 'Rejected'){ 
                        $rejected_by = ($value['supplier_accept'] == 0) ? 'By Supplier' : 'By Buyer';
                    }          
                    $orders_list[$key]['id']= $value['id'];
                    $orders_list[$key]['vporder_id']= $value['vporder_id'];
                    @$orders_list[$key]['buyer_name']= getUser($value['buyer_id'])['name'];
                    $orders_list[$key]['brand_name']= getBrand($value['brand_id']);
                    $orders_list[$key]['orderlast_date']= date('d-m-Y',strtotime($value['orderlast_date']));
                    $orders_list[$key]['supplier_id']= $value['supplier_id'];
                    $orders_list[$key]['branch_name']= getBranch($value['branch_id']);
                    $orders_list[$key]['branch_id']= $value['branch_id'];
                    //$orders_list[$key]['station_name']= getCity($value['supplier_id'])[['city_name'];
                    $orders_list[$key]['brand_name']= getBrand($value['brand_id']);
                    $orders_list[$key]['brand_id']= $value['brand_id'];
                    @$orders_list[$key]['buyer_station'] = getCity(getBuyers(@$value['buyer_id'])['station_id'])['city_name'];
                    $orders_list[$key]['pkt_cases']= $value['pkt_cases'];
                    //$orders_list[$key]['remaining_pkt']= $value['remaining_pkt'];
                    $orders_list[$key]['order_amount']= $value['order_amount'];
                    $orders_list[$key]['supplier_accept']= $value['supplier_accept'];
                    $orders_list[$key]['buyer_accept']= $value['buyer_accept'];
                    $orders_list[$key]['status']= $value['status'];
                    $orders_list[$key]['station_name']= $value['station'];
                    @$orders_list[$key]['reason']= @$value->ordercancel['reason'];
                    $orders_list[$key]['rejected_by']= $rejected_by;
                    $orders_list[$key]['marka']= $value['marka'];
                    $orders_list[$key]['transport_one']= getAllTransport($value['transport_one_id']);
                     
                    $orders_list[$key]['transport_two']= getAllTransport($value['transport_two_id']);
                    $orders_list[$key]['supply_start_date ']= $value['supply_start_date'];
                    $orders_list[$key]['supply_start_date ']= $value['supply_start_date'];
                    $orders_list[$key]['orderimages'] =$value['orderimages'];
                    $orders_list[$key]['order_gallery_images'] =$Gimages;
                    $orders_list[$key]['courier_date'] =$value['courier_date'];
                    $orders_list[$key]['merchandiser_name'] =$value['merchandiser_name'];
                    $orders_list[$key]['order_item'] =$item;
                    $orders_list[$key]['order_delivery'] =$delivery;
                    $orders_list[$key]['link'] = $value['LINKS'];
                    $orders_list[$key]['remark'] = $value['remark'];
                    $orders_list[$key]['rejected_date'] = $value['updated_at'];
                    $orders_list[$key]['color_size_range_etc'] = $value['color_size_range_etc'];
                    $orders_list[$key]['optional'] = $value['optional'];
                    $orders_list[$key]['case_no'] = $value['case_no'];
                    $orders_list[$key]['amount'] = $value['amount'];
                    $orders_list[$key]['order_by'] = $value['order_by'];
                     
                    
                }
            }else if($request_mode == 'buyer') {
            
               
                if($order_type=='Rejected'){
                     $orders = OrderModel::where('buyer_id',$user_id)->where('status',$order_type)->where('updated_at','>=', $rejdate)->whereNull('deleted_at')->orderby('orders.created_at','DESC')->get();
                }elseif($order_type=='Confirm'){
                    $orders = OrderModel::where('buyer_id',$user_id)->whereNull('deleted_at')->whereIn('status',['Confirm','Processing'])
                    ->where('created_at','>=',date('Y-m-d 00:00:00',strtotime('-6 month')))
                    ->where('created_at','<=',date('Y-m-d 23:59:59'))
                    ->orderby('orders.created_at','DESC')
                    ->get();
                }else{
                    $orders = OrderModel::where('buyer_id',$user_id)->where('status',$order_type)->whereNull('deleted_at')//->whereBetween('order_date', [$from, $to])
                    ->where('created_at','>=',date('Y-m-d 00:00:00',strtotime('-6 month')))
                    ->where('created_at','<=',date('Y-m-d 23:59:59'))
                    ->orderby('orders.created_at','DESC')
                    ->get();
                }
                foreach($orders as $key=>$value){
                    $item = OrderItemModel::where('order_id',$value['id'])->get();
                     $delivery = OrderDeliveryModel::orderby('created_at','desc')->where('order_id',$value['id'])->get();
                     $images = OrderGalleryModel::where('order_id',$value['id'])->get();
                     $Gimages=[];
                     foreach ($images as $row) {
                
                $Gimages[]=url('/images/order_request/'.$row['image_name']);    
            }
            
                    $rejected_by = '';
                    if($value['status'] == 'Rejected'){ 
                        $rejected_by = ($value['supplier_accept'] == 0) ? 'By Supplier' : 'By Buyer';
                    }
                    $orders_list[$key]['id']= $value['id'];
                    $orders_list[$key]['vporder_id']= $value['vporder_id'];
                    @$orders_list[$key]['supplier_name']= getUser($value['supplier_id'])['name'];
                    $orders_list[$key]['brand_name']= getBrand($value['brand_id']);
                    $orders_list[$key]['orderlast_date']= date('d-m-Y',strtotime($value['orderlast_date']));
                    $orders_list[$key]['supplier_id']= $value['supplier_id'];
                    $orders_list[$key]['branch_name']= getBranch($value['branch_id']);
                    $orders_list[$key]['branch_id']= $value['branch_id'];
                    $orders_list[$key]['pkt_cases']= $value['pkt_cases'];
                    //$orders_list[$key]['remaining_pkt']= $value['remaining_pkt'];
                    $orders_list[$key]['order_amount']= $value['order_amount'];
                    $orders_list[$key]['brand_name']= getBrand($value['brand_id']);
                    $orders_list[$key]['brand_id']= $value['brand_id'];
                    @$orders_list[$key]['buyer_station'] = getCity(getBuyers(@$value['buyer_id'])['station_id'])['city_name'];
                    $orders_list[$key]['station_name'] = $value['station'];
                    $orders_list[$key]['reason']= @$value->ordercancel['reason'];
                    $orders_list[$key]['rejected_by']= $rejected_by;
                    $orders_list[$key]['marka']= $value['marka'];
                    $orders_list[$key]['transport_one']= getAllTransport($value['transport_one_id']);
                     
                    $orders_list[$key]['transport_two']= getAllTransport($value['transport_two_id']);
                    $orders_list[$key]['supply_start_date ']= $value['supply_start_date'];
                    $orders_list[$key]['orderimages'] =$value['orderimages'];
                    $orders_list[$key]['order_gallery_images'] =$Gimages;
                    $orders_list[$key]['courier_date'] =$value['courier_date'];
                    $orders_list[$key]['merchandiser_name'] =$value['merchandiser_name'];
                    $orders_list[$key]['order_item'] =$item;
                    $orders_list[$key]['order_delivery'] =$delivery;
                    $orders_list[$key]['link'] = $value['LINKS'];
                    $orders_list[$key]['remark'] = $value['remark'];
                    $orders_list[$key]['rejected_date'] = $value['updated_at'];
                    $orders_list[$key]['status']= $value['status'];
                    $orders_list[$key]['color_size_range_etc'] = $value['color_size_range_etc'];
                    $orders_list[$key]['optional'] = $value['optional'];
                    $orders_list[$key]['case_no'] = $value['case_no'];
                    $orders_list[$key]['amount'] = $value['amount'];
                     
                    
                    
                }
            }
            return response()->json(['message'=> 'Order list','status' => true, 'active' => $userchk->status,'block' => $userchk->block, 'orders'=>$orders_list],200); 
        } else {
            return response()->json(['message'=> 'Token not matched', 'status' => true,'orders'=>$orders],200); 
        }
} 
    
    public function OrderDetails(Request $request){
        
        $value = $request->bearerToken();
        //$id = (new Parser())->parse($value)->getHeader('jti');
        $id = (new Parser(new JoseEncoder()))->parse($value)->claims()->all()['jti']; 
        $user_id = Token::find($id)->user_id;
        //print_r($user_id); die;
        //return response()->json(['message'=> 'Order details','status' => true,'orders'=>$user_id],200);
         
        if($user_id){
            try{
            $userchk  = getUser($user_id);
            $order_id = $request->order_id;
            
            $orders = OrderModel::where('id',$order_id)->first();   
            $orders->supply_start_date = date('d-m-Y',strtotime($orders->supply_start_date));
            $orders->orderlast_date = date('d-m-Y',strtotime($orders->orderlast_date));     
            $orders->order_date = date('d-m-Y',strtotime($orders->order_date));  
            $orders->created = date('d-m-Y h:i:s A',strtotime($orders->created_at)); 
            $orders->station_name = @getCity($orders->station)->city_name; 
            $orders->buyer  =  $orders->buyer;
            $orders->buyer_personal = getBuyers($orders->buyer_id);
            $orders->supplier = $orders->supplier;
            $orders->supplier_personal = getSuplliers($orders->supplier_id);
            $orders->branch_name = getBranch($orders->branch_id);
            $orders->branch_id = $orders->branch_id;
            $orders->brand_name = getBrand($orders->brand_id);
            $orders->brand_id = $orders->brand_id;
            $orders->reason = @$orders->ordercancel->reason;
            $orders->remark = $orders->remark;
            $orders->transport_one_id = getAllTransport($orders->transport_one_id);
            $orders->transport_two_id = getAllTransport($orders->transport_two_id);
            $orders->orderimages;  
            $orders->orderitems;
            //$orders->orderitems = getOrderItems($orders->id);
                
            //$orders->ordertracking;   
            $orders->remaining_packet = $orders->pkt_cases - delivered_cases($request->order_id);  
            $orders->remaining_pkt    = $orders->order_amount - remaing_amount($orders->id) ; 
            $pkt_cases                = OrderDeliveryModel::where('order_id',$order_id)->get();
            
            //dd($pkt_cases->count());
            $delivery=[];
            if($pkt_cases->count() > 0){
                  
                foreach($pkt_cases as $keys=>$cases){
                    $tracking =[];
                    
                    $courier=[];
                    $cases_id = $cases['id'];    
                    
                    $orderstracker =OrderTrackingModel::where(['order_id'=>$order_id,'delivery_id'=>$cases_id])->get();
                    
                    $courierData = CourierModel::where(['order_id'=>$order_id,'delivery_id'=>$cases_id])->get();
                    
                    $delivery[$keys]['supplier_bill'] =($cases['supplier_bill']!='')? @url('/images/order_request/')."/".$cases['supplier_bill']:'';
                     $delivery[$keys]['transport_bill'] = ($cases['transport_bill']!='')?@url('/images/order_request/')."/".$cases['transport_bill']:'';
                     $delivery[$keys]['invoice'] = ($cases['invoice']!='')?@url('/images/order_request/')."/".$cases['invoice']:'';
                   
                    foreach($courierData as $key=>$value){                          
                             $courier[$key]['courier_name'] = $value['courier_name'];
                            
                            $courier[$key]['courier_doc'] = ($value['courier_doc']!='')?url('/images/order_request/')."/".$value['courier_doc']:'';
                             
                            $courier[$key]['courier_id'] = $value['courier_id'];
                            $courier[$key]['courier_date'] = $value['courier_date'];
                            $courier[$key]['remarks'] = $value['remarks'];
                             
                        } 
                    
                     foreach($orderstracker as $key=>$value){                          
                            $tracking[$key]['id'] = $value['id'];
                            $tracking[$key]['order_id'] = $value['order_id'];
                            $tracking[$key]['delivery_id'] = $value['delivery_id'];
                            $tracking[$key]['events'] = $value['event'];
                            $tracking[$key]['user_name'] = getUser($value->user_id)->name;//$value['user_id'];
                            $tracking[$key]['status'] = $value['status'];
                            $tracking[$key]['created_at'] = date('d-m-Y h:i:s A',strtotime($value['created_at']));
                        } 
                    $pkt_cases[$keys]['casesDetails']=$tracking;
                    $pkt_cases[$keys]['deliveryDetails']=[end($delivery)];
                     
                    $pkt_cases[$keys]['courierDetails']=$courier; 
                } 
            }
            
             
            
            
            $orders->cases_ordertracking = $pkt_cases;
            
            $basicorderstracker         =   OrderTrackingModel::where(['order_id'=>$order_id])->whereNull('delivery_id')->get(['id','order_id','delivery_id','event as events','user_id','status','created_at']);
             
            $orders->basicorderstracker =   $basicorderstracker;
           
            return response()->json(['message'=> 'Order details',
                                    'status'  => true,
                                    'orders'  => $orders,
                                    'active'  => $userchk->status,
                                    'block'   => $userchk->block,
                                    'path'    => url('/images/order_request')
                                    ],200); 
                                    
            }catch(\Exception $e){
                return response()->json(['message'=> 'Order details','status'=> false,'orders'=> $e],200);
            }
        }else {
            return response()->json(['message'=> 'Token not matched', 'status' => true,'orders'=>$orders, 'path'=>url('/images/order_request')],200); 
        }
    }

    public function updateOrderDetails(Request $request){
        
        $value      = $request->bearerToken();
        //$id         = (new Parser())->parse($value)->getHeader('jti');
        $id = (new Parser(new JoseEncoder()))->parse($value)->claims()->all()['jti']; 
        $user_idd    = Token::find($id)->user_id;
        if($user_idd){
            $orderData = OrderModel::where(['id'=> $request->order_id])->first();
            $user_id = $orderData->buyer_id;
            $userchk        = getUser($user_id);
            $notification   = 'Error Try Again !!!';
            $order_id       = $request->order_id;
            $supplier_id    = $request->supplier_id;
            $user_type      = $request->request_mode;
            $is_accepted    = $request->is_accepted;
            $pkt_cases      = $request->pkt_cases;
            $order_amount   = $request->order_amount;
            $reason         = $request->reason;
            $order          = OrderModel::where('id',$order_id)->first();
            $buyer          = getUser($order->buyer_id);
            $supplier       = getUser($order->supplier_id);
            // if($pkt_cases=='' || $pkt_cases==0){
            //     return response()->json(['message'=> 'Packet Cases Required', 'status' => true],200); 
            //     exit;
            // }
            if($reason == '' || $reason == null){
                if($pkt_cases=='' || $pkt_cases==0){
                    return response()->json(['message'=> 'Packet Cases Required', 'status' => true],200); 
                    exit;
                }
            }

            if($user_type =='supplier'){
                $status =  ($is_accepted==1) ? "Confirm" : "Rejected";
                //$status =  !empty($is_accepted) ? "Confirm" : "Rejected";
                if($is_accepted==1){                   
                    
                    if($request->order_amount >= getSiteData()->min_order_amount){
                        $notification = 'Your order has been accepted by supplier. Order price : '. $request->order_amount.' and number of cases are '.$request->pkt_cases;
                        $order->update(['supplier_accept' => $is_accepted,'supplier_id' =>$supplier_id,'pkt_cases' => $pkt_cases,'order_amount' =>$order_amount,'status' => $status,'remark'=>@$request->remark]);
                        OrderNotification::dispatch($order->id);
                    } else {
                        $notification = 'We can not accept this order because of minimum amount order. Order amount should be atleast INR '.getSiteData()->min_order_amount;         
                        $res          = OrderModel::where(['id'=> $order_id])->update(['supplier_accept' => $is_accepted,'supplier_id' =>$supplier_id,'pkt_cases' => $pkt_cases,'order_amount' =>$order_amount,'status' => $status,'remark'=>@$request->remark,'supplier_status_flag' =>0]);
                        //send_sms('9555699534',$notification);
                    }
                    
                }else{
                    if($status=='Rejected'){
                         //print_r($status); die;
                        $notification = 'DEAR '.$supplier->name.', '.$supplier->veepeeuser_id.',  UNFORTUNATELY YOUR ORD. NO- '.$order->vporder_id.',HAS BEEN  CANCELLED DUE TO '.$reason.',BUYER-'.$buyer->name.',FOR MORE DETAILS CLICK('.url("order/show/".$order->id).' ), PLEASE CONTACT TO-7718277182';
                        OrderCancelModel::insert(['order_id'=>$order_id,'supplier_id'=>$supplier_id,'reason'=>$reason,'status'=>$status,'cancelled_by'=>$user_id]);
                        //send_sms('9555699534',$notification);
                        OrderModel::where(['id'=> $order_id])->update(['supplier_accept' => $is_accepted,'status' => $status,'reason'=>$reason,'remark'=>@$request->remark]);
                    }
                    OrderNotification::dispatch($order->id);
                    /*
                    else if(@$res){
                        $notification = 'CONGRATULATION '.$supplier->name.', '.$supplier->veepeeuser_id.', YOU HAVE RECEIVED OUR CONFIRMED ORD. NO.- '.$order->vporder_id.' ,BUYER-'.$buyer->name.', PLEASE DISPATCH ORDER BEFORE '.date("d-m-Y",strtotime($order->orderlast_date)).',FOR MORE DETAILS CLICK('.url("order/show/".$order->id).'), PLEASE CONTACT TO 7718277182';
                        OrderTrackingModel::insert(['order_id'=>$order_id,'event'=>'Accepted by supplier','flag'=>1,'status'=>'Waiting for approval','user_id'=>$user_id ]);
                        send_sms('9555699534',$notification);
                    }         
                    // order tacking update
                    $notification = !empty($res) ? "Status updated " : "Something error try again";*/
        
                    // $data = array('name' =>$buyer->name,  'msg' => $notification );
                    /* 
                    Mail::send('emails.accept_reject_order',$data, function ($message) use($buyer) {
                        $message->to($buyer->email)->subject('Veepee Internatonal- Order Status Changed');
                    });
                    */
                    /*push to buyer and supplier*/
                    //notifyAndroid([$order->buyer_id,$supplier->id],'Veepee Internatonal',  $notification);
                }
            }else if($user_type=='buyer'){
                /* Add conditon for days & bypass */
                $userr      =  User::where('id',$user_idd)->first();
                $data       =  busy($userr->veepeeuser_id);
                $bypass = getBuyers($userr->id)->bypass;
                if($data->days>75 && !$bypass){
                  return response()->json(['message'=> 'Please update app from playstore', 'status' => false,'days'=>$data->days,'bypass'=>$bypass],401); exit;
                }
                $status =  !empty($is_accepted) ? "Confirm" : "Rejected";
                $res          = OrderModel::where(['id'=> $order_id])->update(['supplier_accept' => $is_accepted, 'buyer_accept' => $is_accepted,'supplier_id' =>$supplier_id,'pkt_cases' => $pkt_cases,'order_amount' =>$order_amount,'status' => $status,'remark'=>@$request->remark]);
                // order cancel insert
                if($status=='Rejected' && $res){
                    $notification = 'DEAR '.$buyer->name.','. $buyer->veepeeuser_id.', UNFORTUNATELY YOUR ORD. NO- '.$order->vporder_id.',HAS BEEN CANCELLED DUE TO SOME '.$reason.',FOR MORE DETAILS CLICK( '.url("order/show/".$order->id).'), PLEASE CONTACT TO-7718277182';   
                    OrderCancelModel::insert(['order_id'=>$order_id,'supplier_id'=>$supplier_id,'reason'=>$reason,'status'=>$status,'cancelled_by'=>$user_id]);
                    //send_sms('9555699534',$notification);
                }else if($res){
                    $notification =  'DEAR '.$buyer->name.','. $buyer->veepeeuser_id.', WE HAVE RECEIVED YOUR CONFIRMED ORD. NO- '.$order->vporder_id.',FOR MORE DETAILS CLICK('.url("order/show/".$order->id).'),PLEASE CONTACT TO-7718277182';  
                    OrderTrackingModel::insert(['order_id'=>$order_id,'event'=>'Accepted by Buyer','flag'=>1,'status'=>'Accepted','user_id'=>$user_id]);
                    
                     OrderNotification::dispatch($order->id);
                    // $buyerdlt='1207163378106237130';
                    // $supdlt='1207163378110074486';
                    // $vpordid=$order->vporder_id;
                    // $brand = BrandModel::where('id',$order->brand_id)->first();
                    //             $bb=$brand->name;
                    // $links         =   url("order/show/".$order->id);
                    // $buyermsg = 'DEAR BUYER ('.$buyer->name.', '.$buyer->veepeeuser_id.'), WE HAVE RECEIVED YOUR CONFIRMED ORD. NO-'.$vpordid.', BRAND-'.$bb.',FOR MORE DETAILS CLICK '.$links.', PLEASE CONTACT TO-7718277182(VEEPEE)';
                    // $suppliermsg = 'CONGRATULATION SUPPLIER ('.$supplier->name.', '.$supplier->veepeeuser_id.'),YOU HAVE RECEIVED OUR CONFIRMED ORD. NO.-'.$vpordid.',BUYER-'.$buyer->name.',PLEASE DISPATCH ORDER BEFORE DUE DATE,FOR MORE DETAILS CLICK '.$links.',PLEASE CONTACT TO-7718277182(VEEPEE)';
                    //$buyer_sms = getBuyers($user_id);
                    //$supplier_sms = getSuplliers($supplier_id);
                    //send_sms($buyer_sms->notify_sms,$buyermsg,$buyerdlt);
                    //send_sms($supplier_sms->notify_sms,$suppliermsg,$supdlt);
                    //send whatsapp notification
                    // $whatsappTemplate = 'veepe_ord';
                    // $whatsappParameters = array(
                    // array(
                    //     "name" => "veepee_msg",
                    //     "value" => $buyermsg,
                    //     ),
                    // );
                    // whatsappCurl($whatsappTemplate, $buyer_sms->notify_sms, $whatsappParameters);
                    // $whatsappParameters = array(
                    //     array(
                    //         "name" => "veepee_msg",
                    //         "value" => $suppliermsg,
                    //     ),
                    // );
                    // whatsappCurl($whatsappTemplate, $supplier_sms->notify_sms, $whatsappParameters);
                }  
                // order tacking update
                $notification   = !empty($res) ? "Status updated " : "Something error try again";
                $data           = array('name' =>$buyer->name,  'msg' => $notification );
                /* 
                Mail::send('emails.accept_reject_order',$data, function ($message) use($supplier) {
                    $message->to($supplier->email)->subject('Veepee Internatonal- Order Status Changed');
                });
                */
                /*push to buyer and supplier*/
                notifyAndroid([$user_id,$supplier_id],'Veepee Internatonal',  $notification);
            }
            return response()->json(['message'=> $notification,'status' => true,'active' => $userchk->status,'block' => $userchk->block],200); 
        }else{
            return response()->json(['message'=> 'Token not matched', 'status' => true],200); 
        }
    }
    
    public function BuyerFeedback(Request $request){
        $value = $request->bearerToken();
        //$id = (new Parser())->parse($value)->getHeader('jti');
        $id = (new Parser(new JoseEncoder()))->parse($value)->claims()->all()['jti']; 
        $user_id = Token::find($id)->user_id;
        $msg='';
        if($user_id){
            $userchk = getUser($user_id);
            $order_id = $request->order_id;
            $feedback = $request->feedback;
            $buyer_id =  $request->buyer_id;
        $res = OrderModel::where(['id'=> $order_id,'buyer_id'=>$buyer_id])
                            ->update([
                                'feedback' => $feedback,                            
                            ]);
            if($res){
                $msg = 'Feedback submitted';
                return response()->json(['message'=> $msg,
                'active' => $userchk->status,
                'block' => $userchk->block,
                'status' => true,                              
            ],200); 
            }else{
                $msg = 'Error!!!';
                return response()->json(['message'=> $msg,
                'active' => $userchk->status,
                'block' => $userchk->block,
                'status' => true,                              
            ],200); 
                            }
        }else{
            return response()->json(['message'=> 'Token not matched', 'status' => true],200); 
        }
}
    
    //remaining balance List  'New','Accepted','Rejected','Processing','Cancelled','Completed','NA','Accepted by supplier' 
    
    public function remainingAmount(Request $request){
        $value      = $request->bearerToken();
        //$id         = (new Parser())->parse($value)->getHeader('jti');
        $id         = (new Parser(new JoseEncoder()))->parse($value)->claims()->all()['jti']; 
        $user_id    = Token::find($id)->user_id;
        
        if($user_id){
            try{
                if($request->user_id){
                    $user_id = $request->user_id;
                }
                $userchk = getUser($user_id);
                $orders  = OrderModel::where('buyer_id',$user_id)->whereIn('status',['Confirm','New'])->get();
                $order   = $orders->pluck('id')->toArray();
                $result['credit_limit']     = (BuyerModel::where('user_id',$user_id)->first('credit_limit'))->credit_limit;
                $result['order_amount']     = $orders->sum('order_amount');
                //$result['pkt_cases']      = $orders->sum('pkt_cases');
                //$result['remaining_pkt']  = $orders->sum('remaining_pkt');
                //$result['spend_amount']   = OrderDeliveryModel::where('status',1)->wherein('order_id',$order)->where('veepee_invoice_number','!=',NULL)->get()->sum('price');
                $result['spend_amount']     = OrderDeliveryModel::where('status',1)->wherein('order_id',$order)->get()->sum('price');
                $result['required_balance'] = (SiteInfoModel::first())->min_order_amount;
                //New Added Code By Me
                $userr          =  User::where('id',$user_id)->first();
                $data           =  busy($userr->veepeeuser_id);
            $Samount        =  speneded_amount($user_id);
            $Oamount        =  ordered_amount($user_id);
            $remaining      =  ($data->amount-($Oamount-$Samount));
            $pAmount = processing_amount($user_id); 
            $result['processing_amount'] =$pAmount; 
            //End//
            $result['remaining_amount'] = round($remaining-$pAmount); 
                return response()->json(['status'=>true,'message'=> 'Success','active' => $userchk->status,'block' => $userchk->block,'result'=>$result], 200);            
                
            }catch(\Exception $e){
                return response()->json(['status'=>false,'message'=> 'Success','result'=>$e], 200);
            }
            
            //$orders         = OrderModel::where('buyer_id',$user_id)->where('status','Confirm')->sum('order_amount');
            //$order_amount   = $orders ?? 0;
            //$order_amount   = $orders->sum('order_amount');
            /* foreach ($orders as $key => $value) { $total_amount = $total_amount+ OrderDeliveryModel::where('order_id',$value->id)->where('status',0)->sum('price'); } */
            //$buyer      = BuyerModel::where('user_id',$user_id)->first('credit_limit');
            //$sitedata   = SiteInfoModel::first();
            //print_r($buyer['credit_limit']); die;
            //$result['city_name'] = $value->city->city_name;
            
        }else{
            return response()->json(['status'=>true,'message'=> 'Token not matched','result'=>null,], 200);
        }
    }

    public function pending_order(Request $request){
        $value = $request->bearerToken();
        //$id = (new Parser())->parse($value)->getHeader('jti');
        $id = (new Parser(new JoseEncoder()))->parse($value)->claims()->all()['jti']; 
        $user_id = Token::find($id)->user_id;
        if($user_id){
            $userchk = getUser($user_id);
            $result = [];
            $orders  =  OrderModel::where('buyer_id',$user_id)->where('status','Confirm')->get();
            
            foreach ($orders as $key => $value) {
                $result[] = $value->brand_id;
            }
    
            return response()->json([
                'status'=>true,
                'message'=> 'Success',
                'result'=>$result, 
                'active' => $userchk->status,
                'block' => $userchk->block,
                ], 201);
        }else{
            return response()->json([
                'status'=>true,
                'message'=> 'Token not matched', 
                'result'=>[],
                ], 201);
        }
        
    }
    
    public function cancel_order(Request $request){
        $value      = $request->bearerToken();
        //$id         = (new Parser())->parse($value)->getHeader('jti');
        $id = (new Parser(new JoseEncoder()))->parse($value)->claims()->all()['jti']; 
        $user_id    = Token::find($id)->user_id;
        
        if($user_id){
            $userchk = getUser($user_id);
            return response()->json(['status'=>true,'message'=> 'Success', 'active' => $userchk->status,
                'block' => $userchk->block,'result'=>(object)cancel_order_rating($user_id)],200);
        }else{
            return response()->json(['status'=>true,'message'=> 'Token not matched','result'=>null],200);
        }
    }
    
    public function orders($status,$type,$user_id){
        return OrderModel::where('status',$status)->where($type,$user_id)->
                           where('created_at','>=',date('Y-m-d 00:00:00',strtotime('-6 month')))->
                           where('created_at','<=',date('Y-m-d 23:59:59'))->count();
    }
    
    public function statuscount($usertype, $user_id){
        $result = [];
        $userchk = getUser($user_id);
        $type   =   ($usertype == 'Buyer') ? 'buyer_id' : 'supplier_id';
        $rejdate = date('Y-m-d',strtotime(date('Y-m-d') .' -365 day'));
        $result['neworder']           = OrderModel::where('status','New')->where($type,$user_id)->count();
        $result['waitingforresponse'] = OrderModel::where('status','Waiting for approval')->where($type,$user_id)->count();
        $result['rejected']           = OrderModel::where('status','Rejected')->where('updated_at','>=', $rejdate)->where($type,$user_id)->count();
        $result['accepted']           = OrderModel::whereIn('status',['Confirm','Processing'])->where($type,$user_id)->where('created_at','>=',date('Y-m-d 00:00:00',strtotime('-6 month')))->where('created_at','<=',date('Y-m-d 23:59:59'))->count();
        $result['completed']          = $this->orders('Completed',$type,$user_id);
        $result['cancelled']          = $this->orders('Cancelled',$type,$user_id);
        /*
        $result['lastsixmonth']       = OrderModel::where('status','Completed')->where($type,$user_id)->
                                                    where('orders.created_at','>=',date('Y-m-d 00:00:00',strtotime('-6 month')))->
                                                    where('orders.created_at','<=',date('Y-m-d 23:59:59'))->count();
        */                                                
        return response()->json(['status'=>true,'message'=> 'Success','active' => $userchk->status,
                'block' => $userchk->block,'result'=>$result],200);
        
        /*
        if($usertype == 'Buyer'){
            
            $new                = OrderModel::where('status','New')->where('buyer_id',$user_id)->count();
            $waitingforresponse = OrderModel::where('supplier_accept',NULL)->where('buyer_id',$user_id)->count();
            $rejected           = OrderModel::where('status','Rejected')->where('buyer_id',$user_id)->count();
            $accepted           = OrderModel::where('status','Confirm')->where('buyer_id',$user_id)->count();
            $lastsixmonth       = OrderModel::where('status','Completed')->where('buyer_id',$user_id)->
                                              where('orders.created_at','>=',date('Y-m-d 00:00:00',strtotime('-6 month')))->
                                              where('orders.created_at','<=',date('Y-m-d 23:59:59'))->count();
            
            $result['neworder']             = $new;
            $result['waitingforresponse']   = $waitingforresponse;
            $result['rejected']             = $rejected;
            $result['accepted']             = $accepted;
            $result['lastsixmonth']         = $lastsixmonth;

        }else{
            
            $new                =  OrderModel::where('status','New')->where('supplier_id',$user_id)->count();
            $waitingforresponse =  OrderModel::where('buyer_accept',NULL)->where('supplier_id',$user_id)->count();
            $rejected           =  OrderModel::where('status','Rejected')->where('supplier_id',$user_id)->count();
            $accepted           =  OrderModel::where('status','Confirm')->where('supplier_id',$user_id)->count();
            $lastsixmonth       =  OrderModel::where('status','Completed')->where('supplier_id',$user_id)->
                                               where('orders.created_at','>=',date('Y-m-d 00:00:00',strtotime('-6 month')))->
                                               where('orders.created_at','<=',date('Y-m-d 23:59:59'))->count();

            $result['neworder'] = $new;
            $result['waitingforresponse'] = $waitingforresponse;
            $result['rejected'] = $rejected;
            $result['accepted'] = $accepted;
            $result['lastsixmonth'] = $lastsixmonth;
        }
        
        return response()->json(['status'=>true,'message'=> 'Success','result'=>$result],201);
        */
            
    }
    
    public function rejectReason($usertype){
         
       $resaons = RejectReasonModel::where('usertype',$usertype)->where('status',1)->get();
        
       $result = [];
       foreach($resaons as $row){
           $result[] = $row->reason;
       }
       
       return response()->json(['status'=>true,'message'=> 'Success','result'=>$result],200);
        
    }

    public function uploadOrderImages(Request $request){
         //return $request->all();
        if(!$request->hasFile('fileName')) {
            return response()->json(['upload_file_not_found'], 400);
        }else{
            $allowedfileExtension=['pdf','jpg','png'];
            $files = $request->fileName; 
            $errors = [];
            foreach ($files as $file) {      
                $extension = $file->getClientOriginalExtension();
        
                $check = in_array($extension,$allowedfileExtension);
        
                if($check) {
        
                    //print_r($request->file('fileName')); die;
                    foreach($request->file('fileName') as $image)
                    {
                        $rdm = uniqid(5);
                        $name= $rdm .'-'.$image->getClientOriginalName();
                        $image->move(public_path().'/images/order_request/', $name);
                        //$data[] = $name;
                        //$OrderGalleryModel = DB::table('orders_gallery')->where('order_id',$request->orderId)->delete();
                        OrderGalleryModel::create(['order_id' =>  $request->orderId, 'image_name'=> $name]);

                    }
                } else {
                    return response()->json(['invalid_file_format'], 422);
                }
        
                return response()->json(['file_uploaded'], 200);
        
            }
        }
         
     }
    public function otpVerifications(Request $request){
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'buyer_id' => 'required',
            'supplier_id' => 'required',
        ]);
        if($validator->fails()){
        $response['message'] = $validator->messages()->first();
        return response()->json(['response' => $response,'status' => false],401); 
        }
        if($request->type == "generate_otp"){
                $order_otp = mt_rand(100000, 999999);
                //$loginUser = getUser($request->buyer_id);
                $loginUser = BuyerModel::select('notify_sms')->where('user_id',$request->buyer_id)->first();

                //print_r($loginUser->notify_sms); die;
                $phonenumber = $loginUser->notify_sms;
                $order  = OrderModel::where(['id'=> $request->order_id,'buyer_id'=> $request->buyer_id,'supplier_id'=> $request->supplier_id])->first();
                if($order){
                    $order->order_otp = $order_otp;
                    $order->save();
                    $msg = 'we get login request from your Veepee account. OTP : '.$order_otp;
                    //send_sms(9827889867, $msg);
                    send_sms($phonenumber, $msg);
                    return response()->json(['status'=>true,'message'=> 'Order OTP successfully sent to buyer registered mobile number'],200);
                }else{
                    return response()->json(['status'=>false,'message'=> 'Order not found'],401);
                }

        }else if($request->type == "verify_otp"){
            $validator = Validator::make($request->all(), [
                'otp' => 'required',
            ]);
            if($validator->fails()){
                $response['message'] = $validator->messages()->first();
                return response()->json(['response' => $response,'status' => false],401); 
            }else{
                $order  = OrderModel::where(['id'=> $request->order_id,'buyer_id'=> $request->buyer_id,'supplier_id'=> $request->supplier_id,'order_otp'=> $request->otp])->first();
                if($order){
                    //$this->customer_accept_order($request->order_id);
                    $order->order_otp = null;
                    //$order->supplier_status_flag = 0;
                    //$order->status = "Confirm";
                    $order->save();
                    return response()->json(['status'=>true,'message'=> 'Otp verified'],200);
                }else{
                    return response()->json(['status'=>false,'message'=> 'Order otp mismatch'],401);
                }
            }
        }
        
        return response()->json(['status'=>true,'message'=> 'Success','result'=>$result],200);
         
     }

    public function customer_accept_order($order_id){
        if($order_id!=''){
               
             $order = OrderModel::where('id',$order_id)->first();
        }
        else{
                return redirect()->back()->withErrors('There is no order selected.'); 
            }
        if($order){
         
            
 
           
            $data = array('buyer_accept' =>1,'status' => 'Confirm');
        
                    
            $order->update($data);
            $brand      =   BrandModel::where('id',$order->brand_id)->first();
            $branch     =   strtoupper(substr(getBranch($order->branch_id),0,3));
            $buyer = getUser($order->buyer_id);
            $supplier = getUser($brand->user_id);
            $buyerdlt='1207163378106237130';
            $supdlt='1207163378110074486';
            $vpordid='VP'.$branch.'-'.$order->id;
            $links         =   url("order/show/".$order->id);
            $buyermsg = 'DEAR BUYER ('.$buyer->name.', '.$buyer->veepeeuser_id.'), WE HAVE RECEIVED YOUR CONFIRMED ORD. NO-'.$vpordid.', BRAND-'.getBrand($order->brand_id).',FOR MORE DETAILS CLICK '.$links.', PLEASE CONTACT TO-7718277182(VEEPEE)';
                $suppliermsg = 'CONGRATULATION SUPPLIER ('.$supplier->name.', '.$supplier->veepeeuser_id.'),YOU HAVE RECEIVED OUR CONFIRMED ORD. NO.-'.$vpordid.',BUYER-'.$buyer->name.',PLEASE DISPATCH ORDER BEFORE DUE DATE,FOR MORE DETAILS CLICK '.$links.',PLEASE CONTACT TO 7718277182(VEEPEE)';
                /*$suppliermsg = 'DEAR {{$supplier->name}}, {{$supplier->veepeeuser_id}}, ORD. NO- {{$order->vporder_id}}, HAS BEEN CLOSED, {{$buyer->name}},FOR MORE DETAILS CLICK(<a href="{{url("order/show/".$order->id)}}">LINK</a>),PLEASE CONTACT TO 7718277182';*/
               $buyer_sms = getBuyers($order->buyer_id);
                $supplier_sms = getSuplliers($order->supplier_id);
                //echo $buyer_sms->notify_sms;
                //echo "-".$supplier_sms->notify_sms;
                //exit;
                send_sms($buyer_sms->notify_sms,$buyermsg,$buyerdlt);
                send_sms($supplier_sms->notify_sms,$suppliermsg,$supdlt);

                //send whatsapp notification
                $whatsappTemplate = 'veepe_ord';
                $whatsappParameters = array(
                  array(
                      "name" => "veepee_msg",
                      "value" => $buyermsg,
                    ),
                );
                whatsappCurl($whatsappTemplate, $buyer_sms->notify_whatsapp, $whatsappParameters);
                $whatsappParameters = array(
                    array(
                        "name" => "veepee_msg",
                        "value" => $suppliermsg,
                      ),
                  );
                  whatsappCurl($whatsappTemplate, $supplier_sms->notify_whatsapp, $whatsappParameters);
            OrderTrackingModel::create(['order_id' => $order->id, 'event' =>'Confirm','status' => 'Accepted', 'user_id' => $order->buyer_id]); 
            OrderNotification::dispatch($order->id);
            return true;
            return redirect('admin/orders?status=Waiting for approval')->withSuccess('Order has been updated successfully.');
        }  
    }

    public function DownloadOrderPdf(Request $request){
        //return $request->all();
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'fileName' => 'required',
        ]);
        if($validator->fails()){
        $response['message'] = $validator->messages()->first();
        return response()->json(['response' => $response,'status' => false],401); 
        }
       if(!$request->hasFile('fileName')) {
           return response()->json(['upload_file_not_found'], 400);
       }else{
            $allowedfileExtension=['pdf','jpg','png'];
            $files = $request->fileName; 
            $extension = $files->getClientOriginalExtension();
    
            $check = in_array($extension,$allowedfileExtension);
    
            if($check) {
                $rdm = uniqid(5);
                $name= $files->getClientOriginalName();
                $files->move(public_path().'/images/download_order_pdf/', $name);

                $order = OrderModel::where('id',$request->order_id)->first();
                $buyer_sms = getBuyers($order->buyer_id);//send whatsapp notification
                $whatsappTemplate = 'veepee_order_pdf_cnew';
                $whatsappParameters = array(
                    array(
                        "name" => "order_id",
                        "value" => $order->vporder_id,
                        ),
                    array(
                        "name" => "order_pdf",
                        "value" => url('/').'/images/download_order_pdf/'.$name,
                        ),
                );
                whatsappCurl($whatsappTemplate, $buyer_sms->notify_sms, $whatsappParameters);

            } else {
                return response()->json(['invalid_file_format'], 422);
            }
    
            return response()->json(['file_uploaded'], 200);
       }
        
    }

    public function DownloadLedgerPdf(Request $request){
        //return $request->all();
        $validator = Validator::make($request->all(), [
            'fileName' => 'required',
            'user_id' => 'required',
            'user_type' => 'required',
        ]);
        if($validator->fails()){
        $response['message'] = $validator->messages()->first();
        return response()->json(['response' => $response,'status' => false],401); 
        }
       if(!$request->hasFile('fileName')) {
           return response()->json(['upload_file_not_found'], 400);
       }else{
            $allowedfileExtension=['pdf','jpg','png'];
            $files = $request->fileName; 
            $extension = $files->getClientOriginalExtension();
    
            $check = in_array($extension,$allowedfileExtension);
    
            if($check) {
                $rdm = uniqid(5);
                $name= $files->getClientOriginalName();
                $files->move(public_path().'/images/download_ledger_pdf/', $name);
                //$user = User::where('veepeeuser_id',$request->user_id)->first();
                if($request->user_type == "buyer"){
                    $buyer_sms = getBuyers($request->user_id);
                }else{
                    $buyer_sms = getSuplliers($request->user_id);
                }
                
                //send whatsapp notification
                $whatsappTemplate = 'vpee_ledger';
                $whatsappParameters = array(
                    array(
                        "name" => "ledger_pdf",
                        "value" => url('/').'/images/download_ledger_pdf/'.$name,
                        ),
                );
                whatsappCurl($whatsappTemplate, $buyer_sms->notify_sms, $whatsappParameters);

            } else {
                return response()->json(['invalid_file_format'], 422);
            }
    
            return response()->json(['file_uploaded'], 200);
       }
        
    }
    
}