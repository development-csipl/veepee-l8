<?php
namespace App\Http\Controllers\Supplier;
use App\Http\Controllers\Controller;
use App\Models\{BuyerModel,OrderModel,TransportsModels,OrderItemModel,ItemModel,BrandModel,OrderTrackingModel,OrderDeliveryModel,SuppliersModels,SupplierBillModel,OrderGalleryModel,CourierModel,OrderCancelModel,BranchModel,OrderImageDownload,RejectReasonModel};
use App\Models\{OrderConfigurationModel};
use App\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Traits\PushNotificationTrait;
use App\Jobs\{OrderNotification,OrderSmsNotification,SMSDeliveryNotification};
use Gate;
use Auth;
use Mail;
use PDF;
use DB;
 
class OrderNewController extends Controller{
    use PushNotificationTrait;
  
    public function index(Request $request){
        
        //$this->sendNotification();
        abort_if(Gate::denies('order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $user       =  Auth::user();
        $role       =  getRole($user->id);
        $user_type  =  '';
        $branches   =  BranchModel::where('status',1)->get();
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=report.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );
         if($request->all()){
             //$orders1 = User::select('users.*', 'orders.*')->join("orders",function($join){$join->on("orders.supplier_id","=","users.id")->orOn("orders.buyer_id","=","users.id");});
            $orders1 = User::select('users.*', 'orders.*')->join("orders",function($join){$join->on("orders.supplier_id","=","users.id");});
             
            if($request->name != ''){
                $orders1->where('users.name','like','%'.$request->name.'%');
            }
            if($request->account_number != ''){
                $orders1->where('users.veepeeuser_id',$request->account_number);
            }
            if($request->branch != ''){
                $orders1->where('orders.branch_id',$request->branch);
            }
            if($request->vporder_id != ''){
                $orders1->where('orders.vporder_id',$request->vporder_id);
            }
            if($request->supplier_accept != ''){
                $orders1->where('orders.supplier_accept',$request->supplier_accept)->where('orders.buyer_accept',0);
            }
            if($request->start_date != '' && $request->end_date != ''){
               $orders1->where('orders.created_at','>=',date('Y-m-d 00:00:00',strtotime($request->start_date)))->where('orders.created_at','<=',date('Y-m-d 23:59:59',strtotime($request->end_date)));
            }
            if($request->status != ''){
                $orders1->where('orders.status',$request->status);
            }
            if($request->status=='Rejected'){
                
                $rejdate = date('Y-m-d',strtotime(date('Y-m-d') .' -30 day'));
                   $orders1->where('orders.updated_at','>=', $rejdate);
                }
            $orders = $orders1->orderby('orders.created_at','desc');
            if($role == 'Admin'){
                $user_type = 'Admin';
                /*$orders = $orders1->orderby('orders.updated_at','desc');*/
            }elseif ($role == 'Branch Operator') {
                $user_type = 'Branch Operator';
                /*$orders = $orders1->wherein('orders.status',['Confirm','Cancelled','Completed'])->where('users.branch_id',$user->branch_id)->orderby('orders.updated_at','desc');*/
            }elseif ($role == 'Mix Branches') {
                $user_type = 'Mix Branches';
                /*$orders = $orders1->wherein('orders.status',['Confirm','Cancelled','Completed'])->where('users.branch_id',$user->branch_id)->orderby('orders.updated_at','desc');*/
            }elseif ($role == 'Head Office Operator') {
                $user_type = 'Head Office Operator';
                /*$orders = $orders1->wherein('orders.status',['Confirm','Cancelled','Completed'])->orderby('orders.updated_at','desc');*/
            }else {
                $user_type = 'N/A';
                
                /*$orders = $orders1->wherein('orders.status',['Confirm','Cancelled','Completed'])->orderby('orders.updated_at','desc');*/
            }
            $order = $orders->get();
            if(count($order)<=0){
                 $orders1 = User::select('users.*', 'orders.*')->join("orders",function($join){$join->on("orders.buyer_id","=","users.id");});
             
            if($request->name != ''){
                $orders1->where('users.name','like','%'.$request->name.'%');
            }
            if($request->account_number != ''){
                $orders1->where('users.veepeeuser_id',$request->account_number);
            }
            if($request->branch != ''){
                $orders1->where('orders.branch_id',$request->branch);
            }
            if($request->vporder_id != ''){
                $orders1->where('orders.vporder_id',$request->vporder_id);
            }
            if($request->supplier_accept != ''){
                $orders1->where('orders.supplier_accept',$request->supplier_accept)->where('orders.buyer_accept',0);
            }
            if($request->start_date != '' && $request->end_date != ''){
               $orders1->where('orders.created_at','>=',date('Y-m-d 00:00:00',strtotime($request->start_date)))->where('orders.created_at','<=',date('Y-m-d 23:59:59',strtotime($request->end_date)));
            }
            if($request->status != ''){
                $orders1->where('orders.status',$request->status);
            }
            if($request->status=='Rejected'){
                
                $rejdate = date('Y-m-d',strtotime(date('Y-m-d') .' -30 day'));
                   $orders1->where('orders.created_at','>=', $rejdate);
                }
            //$orders = $orders1->orderby('orders.updated_at','desc');
            if($role == 'Admin'){
                $user_type = 'Admin';
                /*$orders = $orders1->orderby('orders.updated_at','desc');*/
            }elseif ($role == 'Branch Operator') {
                $user_type = 'Branch Operator';
                /*$orders = $orders1->wherein('orders.status',['Confirm','Cancelled','Completed'])->where('users.branch_id',$user->branch_id)->orderby('orders.updated_at','desc');*/
            }elseif ($role == 'Mix Branches') {
                $user_type = 'Mix Branches';
                /*$orders = $orders1->wherein('orders.status',['Confirm','Cancelled','Completed'])->where('users.branch_id',$user->branch_id)->orderby('orders.updated_at','desc');*/
            }elseif ($role == 'Head Office Operator') {
                $user_type = 'Head Office Operator';
                /*$orders = $orders1->wherein('orders.status',['Confirm','Cancelled','Completed'])->orderby('orders.updated_at','desc');*/
            }else {
                $user_type = 'N/A';
                
                /*$orders = $orders1->wherein('orders.status',['Confirm','Cancelled','Completed'])->orderby('orders.updated_at','desc');*/
            }
            $order = $orders->get();
                
            }
            if($request->export == 'export'){
                
                $columns = array('Sr. No.','VPOrder Id','Buyer Acc No','Buyer Id','Supplier Acc No','Supplier Id','Branch Id','Brand Id','Marka','Order Date','Transport One Id','Transport Two Id','Supply Start Date',
                'Orderlast Date','Station','Pkt Cases','Remaining Pkt','Order Amount','Remaining Amount','Supplier Accept','Buyer Accept','Veepee Invoice Number',
                'Invoice','Status','Reason','Remark','Remarked By','Remark Date','Merchandiser Name');
                
                $callback = function() use ($order, $columns) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);
                    $n=1;
                    $TRPCase   = null ;
                    foreach($order->unique('vporder_id') as $row) {
                        
                        $RPCase   = $row->pkt_cases - (delivered_cases($row->id) ?? 0);
                                    $TRPCase += $RPCase;
                        fputcsv($file, array(
                            $n,
                            $row->vporder_id,
                            @getUser($row->buyer_id)->veepeeuser_id,
                            @getUser($row->buyer_id)->name,
                            @getUser($row->supplier_id)->veepeeuser_id,
                            @getUser($row->supplier_id)->name,
                            @getBranch($row->branch_id),
                            @getBrand($row->brand_id),
                            $row->marka,
                            $row->order_date,
                            @getTransport($row->transport_one_id),
                            @getTransport($row->transport_two_id),
                            $row->supply_start_date,
                            $row->orderlast_date,
                            @getCity($row->station),
                            $row->pkt_cases,
                            @$RPCase,
                            $row->order_amount,
                            ($row->order_amount - remaing_amount($row->id)),
                            ($row->supplier_accept == 0 || $row->supplier_accept == null ) ? 'Pending' : 'Accepted',
                            ($row->buyer_accept == 0 || $row->buyer_accept == null) ? 'Pending' : 'Accepted',
                            $row->veepee_invoice_number,
                            $row->invoice,
                            $row->status,
                            $row->reason,
                            $row->remark,
                            @getUser(ordercancelremark($row->id)->cancelled_by)->name ?? '',
                            $row->updated_at,
                            
                            @getUser($row->users_id)->name ?? ''
                        ));
                        $n++;
                    }
                    fclose($file);
              };
                return \Response::stream($callback, 200, $headers);
            }
            
            
            $orders = $orders->whereNull('orders.deleted_at')->groupby('orders.vporder_id')->paginate(20);
            return view('admin.orders.index', compact('orders','branches','user_type'));
        }else {
            if($role == 'Admin'){
                $user_type = 'Admin';
                $orders = OrderModel::orderby('created_at','desc')->wherein('orders.status',['Confirm','Cancelled','Completed']);
            }elseif ($role == 'Branch Operator') {
                $user_type = 'Branch Operator';
                $orders = OrderModel::where('branch_id',$user->branch_id)->wherein('orders.status',['Confirm','Cancelled','Completed'])->orderby('created_at','desc');
            }
            elseif ($role == 'Mix Branches') {
                $user_type = 'Mix Branches';
                $orders = OrderModel::wherein('orders.status',['Confirm','Cancelled','Completed'])->orderby('created_at','desc');
            }
            elseif($role == 'Head Office Operator') {
                $user_type = 'Head Office Operator';
                $orders = OrderModel::wherein('orders.status',['Confirm','Cancelled','Completed'])->orderby('created_at','desc');
            }else {
                $user_type = 'N/A';
                $orders = OrderModel::orderby('created_at','desc');
            }
            
            if($request->export == 'export'){
                $order = $orders->get();
                $columns = array('Sr. No.','VPOrder Id','Buyer Acc No','Buyer Id','Supplier Acc No','Supplier Id','Branch Id','Brand Id','Marka','Order Date','Transport One Id','Transport Two Id','Supply Start Date',
                'Orderlast Date','Station','Pkt Cases','Remaining Pkt','Order Amount','Remaining Amount','Supplier Accept','Buyer Accept','Veepee Invoice Number',
                'Invoice','Status','Remark','Remarked By','Remark Date','Merchandiser Name');
                
                $callback = function() use ($order, $columns) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);
                    $n=1;
                    $TRPCase   = null ;
                    foreach($order->unique('vporder_id') as $row) {
                        $RPCase   = $row->pkt_cases - (delivered_cases($row->id) ?? 0);
                                    $TRPCase += $RPCase;
                        fputcsv($file, array(
                            $n,
                            $row->vporder_id,
                            @getUser($row->buyer_id)->veepeeuser_id,
                            @getUser($row->buyer_id)->name,
                            @getUser($row->supplier_id)->veepeeuser_id,
                            @getUser($row->supplier_id)->name,
                            @getBranch($row->branch_id),
                            @getBrand($row->brand_id),
                            $row->marka,
                            $row->order_date,
                            @getTransport($row->transport_one_id),
                            @getTransport($row->transport_two_id),
                            $row->supply_start_date,
                            $row->orderlast_date,
                            @getCity($row->station),
                            $row->pkt_cases,
                            @$RPCase,
                            $row->order_amount,
                            ($row->order_amount - remaing_amount($row->id)),
                            ($row->supplier_accept == 0) ? 'Rejected' : 'Accepted',
                            ($row->buyer_accept == 0) ? 'Rejected' : 'Accepted',
                            $row->veepee_invoice_number,
                            $row->invoice,
                            $row->status,
                            $row->reason,
                            @getUser(ordercancelremark($row->id)->cancelled_by)->name ?? '',
                            $row->updated_at,
                            
                            @getUser($row->users_id)->name ?? ''
                        ));
                        $n++;
                    }
                    fclose($file);
              };
                return \Response::stream($callback, 200, $headers);
            }
            
            $orders = $orders->groupby('orders.vporder_id')->paginate(20);
            return view('admin.orders.index', compact('orders','branches','user_type'));
        }
    }
    
    public function accept_order(Request $request, $usertype){
        
        //$this->sendNotification();
       // abort_if(Gate::denies('order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $user =  Auth::user();
        $role = getRole($user->id);
        $user_type = '';
        $branches = BranchModel::where('status',1)->get();
      // echo '<pre>'; print_r($branches); die;
       //  if($request->all()){

            $orders1 = User::join('orders', 'orders.supplier_id', '=', 'users.id')
            ->select('users.*', 'orders.*');
            if($usertype == 'suppplier'){
                $orders1->where('orders.supplier_accept',NULL);
            } else {
               $orders1->where('orders.buyer_accept',NULL); 
            }

            

            if($request->start_date != '' && $request->end_date != ''){
               $orders1->where('orders.created_at','>=',date('Y-m-d 00:00:00',strtotime($request->start_date)))->where('orders.created_at','<=',date('Y-m-d 23:59:59',strtotime($request->end_date)));
            }

            if($request->status != ''){
                $orders1->where('orders.status',$request->status);
            }
       
            
            $orders = $orders1->orderby('orders.created_at','desc')->paginate(20);
            
            
            return view('admin.orders.unaccept_order_list', compact('orders','branches','user_type'));
         
        
    }

    public function pending_invoice(Request $request){
        $user =  Auth::user();
        if($request->all()){
            $users1 = OrderDeliveryModel::where('veepee_invoice_number',NULL)->where('created_by',$user->id)->where('status','!=',0)->orderby('created_at','asc');
            if($request->veepee_invoice_number != ''){
                $users1->where('veepee_invoice_number',$request->veepee_invoice_number);
            }
            if($request->supplier_invoice != ''){
                $users1->where('supplier_invoice',$request->supplier_invoice);
            }
            $delivery = $users1->paginate(20);
            return view('admin.orders.pending_invoice', compact('delivery'));
        } else {
            $delivery = OrderDeliveryModel::where('veepee_invoice_number',NULL)->where('created_by',$user->id)->where('status','!=',0)->orderby('created_at','asc')->paginate(20);
            return view('admin.orders.pending_invoice', compact('delivery'));
        }
    }

    public function check_document(Request $request){
        if($request->all()){
            $user   =   Auth::user();
            $users1 =   OrderDeliveryModel::where('veepee_invoice_number',NULL)->where('status',NULL);
            
            if($request->branch != ''){
                $orders = OrderModel::where('branch_id',$request->branch)->get();
                $branch = array();
                foreach($orders as $row){
                    $branch[] = $row->id;
                }
                $users1->whereIn('order_id',$branch);
            }
            
            if($request->veepee_order_number != ''){
                $ONumbers = OrderModel::where('vporder_id',$request->veepee_order_number)->get();
                $numbers = array();
                foreach($ONumbers as $row){
                    $numbers[] = $row->id;
                }
                $users1->whereIn('order_id',$numbers);
            }
            
            if($request->veepee_invoice_number != ''){
                $users1->where('veepee_invoice_number',$request->veepee_invoice_number);
            }

            if($request->supplier_invoice != ''){
                $users1->where('supplier_invoice',$request->supplier_invoice);
            }
            
            $delivery = $users1->orderby('created_at','desc')->paginate(20); 

            return view('admin.orders.checkdocument', compact('delivery'));
        } else {
            $user       =   Auth::user();
            $delivery   =   OrderDeliveryModel::with(['downloads'])->where('veepee_invoice_number',NULL)->where('status',NULL)->orderby('created_at','desc')->paginate(20);
            return view('admin.orders.checkdocument', compact('delivery'));
        }
    }

    public function pending_bill(Request $request){
        if($request->all()){
            $user       =  Auth::user();
            $delivery   =  OrderDeliveryModel::where('status',1)->where('dispatch',0); //where('veepee_invoice_number',NULL)->
            
            if($request->branch != ''){
                $Orders = OrderModel::where('branch_id',$request->branch)->get();
                if($Orders != null && count($Orders)>0){
                    $delivery->whereIn('order_id',$Orders->pluck('id')->toArray());
                }
            }
            
            if($request->order_number != ''){
                $ONumbers = OrderModel::where('vporder_id',$request->order_number)->get();
                if($ONumbers != null && count($ONumbers)>0){
                    $delivery->whereIn('order_id',$ONumbers->pluck('id')->toArray());
                }
            }
            
            if($request->account_number != ''){
                $User = User::where('veepeeuser_id',$request->account_number)->first();
                if($User != null){
                    $Accounts = OrderModel::where('buyer_id',$User->id)->orWhere('supplier_id',$User->id)->get();
                    if($Accounts != null && count($Accounts)>0){
                        $delivery->whereIn('order_id',$Accounts->pluck('id')->toArray());
                    }
                }
            }
            
            $delivery = $delivery->orderby('created_at','desc')->where('veepee_invoice_number',NULL)->paginate(20); 
            
            //$delivery = OrderDeliveryModel::where('veepee_invoice_number',NULL)->whereIn('order_id',$branch)->where('status',NULL)->orderby('created_at','desc')->paginate(20); 
            return view('admin.orders.check_document',compact('delivery'));
        } else {
            $user       =  Auth::user();
            $delivery   =  OrderDeliveryModel::where('status',1)->where('dispatch',0)->orderby('created_at','desc')->where('veepee_invoice_number',NULL)->paginate(20); //where('veepee_invoice_number',NULL)->
            return view('admin.orders.check_document', compact('delivery'));
        }
    }

    public function complete_bill(Request $request){
         if($request->all()){
            $user =  Auth::user();
            
            $delivery   =  OrderDeliveryModel::where('veepee_invoice_number','<>',NULL)->where('status','1');
            /*
            if($request->status != '' && $request->status != null){
                $delivery->where('status',$request->status);
            }else{
                $delivery->where('status',1);
            }
            */
            if($request->branch != ''){
                $Orders = OrderModel::where('branch_id',$request->branch)->get();
                if($Orders != null && count($Orders)>0){
                    $delivery->whereIn('order_id',$Orders->pluck('id')->toArray());
                }
            }
            
            if($request->order_number != ''){
                $ONumbers = OrderModel::where('vporder_id',$request->order_number)->get();
                if($ONumbers != null && count($ONumbers)>0){
                    $delivery->whereIn('order_id',$ONumbers->pluck('id')->toArray());
                }
            }
            
            if($request->account_number != ''){
                $User = User::where('veepeeuser_id',$request->account_number)->first();
                if($User != null){
                    $Accounts = OrderModel::where('buyer_id',$User->id)->orWhere('supplier_id',$User->id)->get();
                    if($Accounts != null && count($Accounts)>0){
                        $delivery->whereIn('order_id',$Accounts->pluck('id')->toArray());
                    }
                }
            }
            
            if($request->invoice_number != ''){
                $delivery->where('veepee_invoice_number',$request->invoice_number)->orWhere('supplier_invoice',$request->invoice_number);
            }
            
            $delivery = $delivery->orderby('created_at','desc')->paginate(20);
            /*
            $orders = OrderModel::where('branch_id',$request->branch)->get();
            $branch = array();
            foreach($orders as $row){
                $branch[] = $row->id;
            }
            $delivery = OrderDeliveryModel::where('status',1)->where('veepee_invoice_number','!=',NULL)->whereIn('order_id',$branch)->orwhere('status',0)->orderby('created_at','asc')->paginate(20);
            */
            //$delivery = OrderDeliveryModel::where('veepee_invoice_number',NULL)->whereIn('order_id',$branch)->where('status',1)->where('dispatch',0)->orderby('created_at','desc')->paginate(20);
            return view('admin.orders.complete_bill', compact('delivery'));
        }else {
            $user       =  Auth::user();
            $delivery   =  OrderDeliveryModel::with(['downloads'])->where('status',1)->where('veepee_invoice_number','!=',NULL)->orwhere('status',0)->orderby('created_at','desc')->paginate(20);
            return view('admin.orders.complete_bill', compact('delivery'));
        }
    }
    
    public function reject_order(){
        $user =  Auth::user();
        $delivery = OrderDeliveryModel::where('status',0)->where('created_by',$user->id)->orderby('created_at','desc')->paginate(20);
        return view('admin.orders.cancel_order', compact('delivery'));
    }

    public function courier_detail(Request $request){
        if($request->all()){
            $user =  Auth::user();
             $user_id = 0;
            if($request->account_number !=''){
                $users = User::where('veepeeuser_id',$request->account_number)->first();
                $user_id = $users->id;
            }
            $orders = OrderModel::where('branch_id',$user->branch_id);
            
            if($request->vporder_id!=''){
                $orders->where('vporder_id',$request->vporder_id);
            }
             
            if($request->account_number){
                $orders->where('supplier_id',$user_id)->orwhere('buyer_id',$user_id);
            } 
           $orderss =   $orders->get();
             
            $branch = array();
            foreach($orderss as $row){
                $branch[] = $row->id;
            }
            $deliveri = OrderDeliveryModel::where('status',1)->where('created_by',$user->id);
            if($request->invoice_id!=''){
                $deliveri->where('veepee_invoice_number',$request->invoice_id);
            }
            
            
            $deliveri->whereIn('order_id',$branch);
            
          $delivery =  $deliveri->orderby('created_at','asc')->paginate(20);
          //  where('status',1)->where('veepee_invoice_number',$request->invoice_id)->whereIn('order_id',$branch)->orwhere('status',0)->orderby('created_at','desc')->paginate(20);
           // $delivery = OrderDeliveryModel::where('veepee_invoice_number',NULL)->whereIn('order_id',$branch)->where('status',1)->where('dispatch',0)->orderby('created_at','desc')->paginate(20);
            return view('admin.orders.courier_detail', compact('delivery'));
        } else {
            $user =  Auth::user();
            $delivery = OrderDeliveryModel::where('veepee_invoice_number','!=',NULL)->where('status',1)->where('created_by',$user->id)->orderby('created_at','asc')->paginate(20);
            return view('admin.orders.courier_detail', compact('delivery'));
        }
    }

    public function delivery($id){
      //  print_r($id); die;
        if(checkOrderAccept($id) == true){
            abort_if(Gate::denies('order_delivery'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            $delivery = OrderDeliveryModel::orderby('created_at','desc')->where('order_id',$id)->get();
            $order_id = $id;
            //'orders',
            return view('admin.orders.delivery', compact('delivery','order_id'));
        }
    }
    
    public function check_head_delivery(Request $request, $id){
        
        abort_if(Gate::denies('add_order_delivery'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $delivery = OrderDeliveryModel::where('id',$id)->first();
        // echo '<pre>'; print_r($delivery); die;
        $order = OrderModel::where('id','=',$delivery->order_id)->first();
        /*check order cases completed or not*/
        //  print_r(checkOrderStatus($id)); die;
        //  if(checkOrderStatus($id) == false){

        if($request->all()){
            $user_id = Auth::id();
            //dd($request->status, $id);
           // $delivered_case = OrderDeliveryModel::where('order_id',$id)->where('status',1)->sum('no_of_case');
           // $delivered_case_amount = OrderDeliveryModel::where('order_id',$id)->where('status',1)->sum('price');
            $remaining_case = @$order->pkt_cases- delivered_cases($delivery->order_id);
            $remaining_amount = @$order->order_amount-remaing_amount($delivery->order_id);
            
          // echo '<pre>'; print_r($order);  die;
            $validation_rule = Validator::make($request->all(), [
                'no_of_case'=>'lte:'.$remaining_case,
                //'price'=>'lte:'. $remaining_amount,
            ]);
            
            if($request->status != 0){
                $validation_rule = Validator::make($request->all(), [
                    'no_of_case'=>'lte:'.$remaining_case,
                    'price'=>'lte:'. $remaining_amount,
                ]); 
            }
            
            $validation_rule->validate();
            /*
            if(!empty($request->veepee_invoice_number) && $request->veepee_invoice_number !=null){
                if(OrderDeliveryModel::checkDuplicate($request->veepee_invoice_number,$id)) {
                    return redirect(route('admin.check_document'))->withError('This inovice number already assinged.');
                }
            }

            */
            $destinationPath = public_path('/images/order_request');
            
            

            if ($files = $request->file('order_form')) {
               $data = array('order_id' => $order->id, 'event' => 'Order form uploaded', 'user_id' => $user_id, 'status' => 'File Uploaded', 'delivery_id' => $delivery->id,'flag' => 1);
               $order_form = $this->saveImage($request->file('order_form'),$destinationPath,$data);
            }

            if ($filess = $request->file('supplier_bill')) {
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

            if ($files = $request->file('debit_note')) {
                 $data = array('order_id' => $order->id, 'event' => 'Debit note uploaded', 'user_id' => $user_id,'delivery_id' => $delivery->id, 'status' => 'File Uploaded', 'flag' => 1);
                $debit_note = $this->saveImage($request->file('debit_note'),$destinationPath,$data);
                
            }

            if ($files = $request->file('courier_doc')) {
                 $data = array('order_id' => $order->id, 'event' => 'Courier document uploaded', 'user_id' => $user_id,'delivery_id' => $delivery->id, 'status' => 'File Uploaded', 'flag' => 1);
                $courier_doc = $this->saveImage($request->file('courier_doc'),$destinationPath,$data);
                
            }

            if ($files = $request->file('invoice')) {
                 $data = array('order_id' => $order->id, 'event' => 'Invoice uploaded', 'user_id' => $user_id,'delivery_id' => $delivery->id, 'status' => 'File Uploaded', 'flag' => 1);
                $invoice = $this->saveImage($request->file('invoice'),$destinationPath,$data);
                
            }

            if ($request->supplier_firm_status) {
                 $data = array('order_id' => $order->id, 'event' => 'Supplier Firm Status Changed', 'user_id' => $user_id, 'status' => 'Supplier Firm Status Changed', 'delivery_id' => $delivery->id, 'flag' => 1);
                $this->saveImage(NULL,$destinationPath,$data);
                
            }

            if ($request->bilty_date_status) {
                 $data = array('order_id' => $order->id, 'event' => 'Bilty date status changed', 'user_id' => $user_id, 'status' => 'Bilty date status changed', 'delivery_id' => $delivery->id, 'flag' => 1);
                $this->saveImage(NULL,$destinationPath,$data);
                
            }

            if ($request->amount_status) {
                 $data = array('order_id' => $order->id, 'event' => 'Amount Status Changed', 'user_id' => $user_id, 'status' => 'Amount Status Changed', 'delivery_id' => $delivery->id, 'flag' => 1);
                $this->saveImage(NULL,$destinationPath,$data);
                
            }


            if ($request->supplly_station_status) {
                 $data = array('order_id' => $order->id, 'event' => 'Supplly Station Status Changed', 'user_id' => $user_id, 'status' => 'Supplly Station Status Changed', 'delivery_id' => $delivery->id, 'flag' => 1);
                $this->saveImage(NULL,$destinationPath,$data);
                
            }

            if ($request->case_status) {
                 $data = array('order_id' => $order->id, 'event' => 'Case Status Changed', 'user_id' => $user_id, 'status' => 'Case Status Changed', 'delivery_id' => $delivery->id, 'flag' => 1);
                $this->saveImage(NULL,$destinationPath,$data);
                
            }


            if ($request->status) {
                $status = $request->status == 0 ? 'Rejected' : 'Approved';
                $data = array('order_id' => $order->id, 'event' => 'Order '.$status, 'user_id' => $user_id, 'status' =>  'Order '.$status, 'delivery_id' => $delivery->id, 'flag' => 1);
                $this->saveImage(NULL,$destinationPath,$data);
                
            }

            if ($request->no_of_case) {
                 $data = array('order_id' => $order->id, 'event' => 'Number of case updated', 'user_id' => $user_id, 'status' => 'Number of case updated', 'delivery_id' => $delivery->id, 'flag' => 1);
                $this->saveImage(NULL,$destinationPath,$data);
                
            }

            if ($request->price) {
                 $data = array('order_id' => $order->id, 'event' => 'Price updated', 'user_id' => $user_id, 'status' => 'Price updated', 'delivery_id' => $delivery->id, 'flag' => 1);
                $this->saveImage(NULL,$destinationPath,$data);
                
            }

            if ($request->veepee_invoice_number) {
                 $data = array('order_id' => $order->id, 'event' => 'Veepee Invoice number updated', 'user_id' => $user_id, 'status' => 'Veepee Invoice number updated', 'delivery_id' => $delivery->id, 'flag' => 1);
                $this->saveImage(NULL,$destinationPath,$data);
                
            }
          //  $data1 = array('order_id' => $order->id, 'event' => 'Receive document', 'user_id' => $user_id, 'status' => 'Done', 'delivery_id' => $delivery->id, 'flag' => 1);
         //   OrderTrackingModel::create($data1);

            $data = array(
                'no_of_case' => $request->no_of_case,
                'price' => $request->price,
                'veepee_invoice_number' => $request->veepee_invoice_number,
                'order_form' => @$order_form ?? $delivery->order_form,
                'supplier_bill' => @$supplier_bill ?? $delivery->supplier_bill,
                'transport_bill' => @$transport_bill ?? $delivery->transport_bill,
                'eway_bill' => @$eway_bill ?? $delivery->eway_bill,
                'credit_note' => @$credit_note ?? $delivery->credit_note,
                'debit_note' => @$debit_note ?? $delivery->debit_note,
                'courier_doc' => @$courier_doc ?? $delivery->courier_doc,
                'invoice' => @$invoice ?? $delivery->invoice,
                'status' => $request->status,
                'remark' => $request->remark,
                'bo_case_number' => $request->bo_case_number ?? $delivery->bo_case_number,
                'bo_amount' => $request->bo_amount ?? $delivery->bo_amount,
                'supplier_firm_status' => $request->supplier_firm_status ?? 0, 
                'bilty_date_status' => $request->bilty_date_status ?? 0, 
                'amount_status' => $request->amount_status ?? 0, 
                'supplly_station_status' => $request->supplly_station_status ?? 0, 
                'case_status' => $request->case_status ?? 0, 
                'reject_reason' => $request->reject_reason ?? 0
            );

            $delivery->update($data);
            
            if($request->price){
                $spend_order_amount = OrderDeliveryModel::where('order_id',$id)->where('status',1)->sum('price');
                $order->update(['spend_order_amount' =>$spend_order_amount ]);
            }
            
            if(($order->pkt_cases == delivered_cases($order->id)) OR (($order->order_amount - remaing_amount($order->id)) <= 10000)){
                    $order->update(['status' => 'Completed']);
                    $completedata = array('order_id' => $order->order_id, 'event' => 'This order has been completed successfully.', 'user_id' => Auth::id(), 'status' => 'order completed', 'delivery_id' => NULL, 'flag' => 1);
                    $this->saveImage(NULL,NULL,$completedata);
                    $msg = 'We have dispatched your order (Order ID : '.$order->vporder_id.')<br> Order Details are : Number of case : '.$request->no_of_case.'<br> Price INR '.$request->price. '<br> Please collect your order. Now your order has been completed.';
                    
                $buyer = getUser($order->buyer_id);
                $supplier = getUser($order->supplier_id);
                $transport = getTransportDetail($order->transport_one_id);
                $pdfdata = array('order' => $order,'buyer' => $buyer, 'supplier' => $supplier,'dispatch' => @$delivery, 'transport' => $transport);
                $pdf = PDF::loadView('emails.invoice',$pdfdata);
                $pdf->setPaper('a4', 'portrait');
                $pdf->setOptions(['dpi' => 120, 'defaultFont' => 'sans-serif']);
                $buyerdlt='1207163378115864251';
                $supdlt='1207163378119302467';
                
                $links         =   url("order/show/".$order->id);
                 
                 $brand = BrandModel::where('id',$order->brand_id)->first();
                $bb=$brand->name;
               
                $buyermsg = 'DEAR BUYER'.$buyer->name.', '.$buyer->veepeeuser_id.', ORD. NO-'.$order->vporder_id.', BRAND-'.$bb.', HAS BEEN CLOSED. FOR MORE DETAILS CLICK '.$links.', PLEASE CONTACT TO 7718277182 (VEEPEE)';
                
                $suppliermsg = 'DEAR SUPPLIER '.$supplier->name.', '.$supplier->veepeeuser_id.', ORD. NO-'.$order->vporder_id.', HAS BEEN CLOSED, BUYER-'.$buyer->name.', FOR MORE DETAILS CLICK '.$links.', PLEASE CONTACT TO 7718277182 (VEEPEE)';
                $buyer_sms = getBuyers($order->buyer_id);
                $supplier_sms = getSuplliers($order->supplier_id);
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
                
                 
                /*    
                $buyermsg = 'DEAR {{$buyer->name}}, {{$buyer->veepeeuser_id}}, ORD. NO- {{$order->vporder_id}}. , BRAND- {{getBrand($brand_id)}}, HAS BEEN CLOSED.FOR MORE DETAILS CLICK(<a href="{{url("order/show/".$order->id)}}">LINK</a>),PLEASE CONTACT TO 7718277182';
                $suppliermsg = 'DEAR {{$supplier->name}}, {{$supplier->veepeeuser_id}}, ORD. NO- {{$order->vporder_id}}, HAS BEEN CLOSED, {{$buyer->name}},FOR MORE DETAILS CLICK(<a href="{{url("order/show/".$order->id)}}">LINK</a>),PLEASE CONTACT TO 7718277182';
                send_sms('9555699534',$buyermsg);
                send_sms('9555699534',$suppliermsg);
                */
                
                SMSDeliveryNotification::dispatch($order->id);

                $data = array('name' =>$buyer->name,  'msg'=> $msg, 'order_id' => $order->vporder_id);
                /*Mail::send('emails.order_dispatched',$data, function ($message) use($buyer,$supplier,$pdf) {
                    $message->to($buyer->email,$buyer->name)->subject('Veepee Internatonal- Order dispatched');
                    $message->bcc($supplier->email,$supplier->name);
                    $message->attachData($pdf->output(), "invoice.pdf");
                   // $message->bcc($supplier->email,$supplier->name);
                });*/

                /*push to buyer and supplier*/
                //notifyAndroid([$buyer->id,$supplier->id],'Veepee Internatonal',  $msg);
                
                
                
            }
            return redirect(route('admin.check_document'))->withSuccess('Document for this order has been checked successfully.');

        } else {
            $order_id = $id;
            $title = trans('global.add');
            return view('admin.orders.checkdocumenthead', compact('order_id','title','order','delivery'));
        }
        /*} else {
            return redirect(route('admin.orders.delivery', $order->id))->withErrors('All cases for this order has been dispatched successfully.So you are not able to create new delivery.');
        }*/
    }

    public function add_delivery(Request $request, $id){
        if($request->supplier_invoice!=''){
        $delivered_check = OrderDeliveryModel::where('order_id',$id)->where('supplier_invoice',$request->supplier_invoice)->get(); 
        if(count($delivered_check)>0){
            return redirect(route('admin.orders.delivery', $id))->withErrors('You have already created delivery with this Invoice.So you are not able to create new delivery.');
        }
        }
        abort_if(Gate::denies('add_order_delivery'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $order = OrderModel::where('id','=',$id)->first();
        /*check order cases completed or not*/
        //print_r(checkOrderStatus($id)); die;
        if(checkOrderStatus($id) == false){
            
            if($request->all()){
                
            $user_id = Auth::id();

            $delivered_case = OrderDeliveryModel::where('order_id',$id)->where('status',1)->sum('no_of_case');
            
            /*
            if(!empty($request->supplier_invoice) && $request->supplier_invoice !=null){
                if(OrderDeliveryModel::checkSupplierDuplicate($request->supplier_invoice,$id)) {
                    return redirect(route('admin.orders.delivery', $order->id))->withErrors('This inovice number already assinged.');
                }
            }
            
            dd($request->all());
            */
            
            $delivered_case_amount  = OrderDeliveryModel::where('order_id',$id)->where('status',1)->sum('price');
            $remaining_case         = $order->pkt_cases-$delivered_case;
            $remaining_amount       = $order->order_amount-$delivered_case_amount;
            
            $validation_rule = Validator::make($request->all(), [
                'no_of_case'=>'lte:'.$remaining_case,
                'price'=>'lte:'. $remaining_amount,
                'supplier_invoice'=>'required',
                'transport_bill'=>'required',
                'supplier_bill'=>'required',
                'order_form'=>'required',
            ]);
            
            $validation_rule->validate();
            
            $destinationPath = public_path('/images/order_request');
            
            $delivery = OrderDeliveryModel::create(['order_id' => $order->id,'created_by' => $user_id]);

            if ($files = $request->file('order_form')) {
               $data = array('order_id' => $order->id, 'event' => 'Order form uploaded', 'user_id' => $user_id, 'status' => 'File Uploaded', 'delivery_id' => $delivery->id,'flag' => 1);
               $order_form = $this->saveImage($request->file('order_form'),$destinationPath,$data);
            }

            if ($filess = $request->file('supplier_bill')) {
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

            if ($files = $request->file('debit_note')) {
                 $data = array('order_id' => $order->id, 'event' => 'Debit note uploaded', 'user_id' => $user_id,'delivery_id' => $delivery->id, 'status' => 'File Uploaded', 'flag' => 1);
                $debit_note = $this->saveImage($request->file('debit_note'),$destinationPath,$data);
                
            }

            if ($files = $request->file('courier_doc')) {
                 $data = array('order_id' => $order->id, 'event' => 'Courier document uploaded', 'user_id' => $user_id,'delivery_id' => $delivery->id, 'status' => 'File Uploaded', 'flag' => 1);
                $courier_doc = $this->saveImage($request->file('courier_doc'),$destinationPath,$data);
                
            }

            if ($files = $request->file('invoice')) {
                 $data = array('order_id' => $order->id, 'event' => 'Invoice uploaded', 'user_id' => $user_id,'delivery_id' => $delivery->id, 'status' => 'File Uploaded', 'flag' => 1);
                $invoice = $this->saveImage($request->file('invoice'),$destinationPath,$data);
                
            }

            if ($request->supplier_firm_status) {
                 $data = array('order_id' => $order->id, 'event' => 'Supplier Firm Status Changed', 'user_id' => $user_id, 'status' => 'Supplier Firm Status Changed', 'delivery_id' => $delivery->id, 'flag' => 1);
                $this->saveImage(NULL,$destinationPath,$data);
                
            }

            if ($request->bilty_date_status) {
                 $data = array('order_id' => $order->id, 'event' => 'Bilty date status changed', 'user_id' => $user_id, 'status' => 'Bilty date status changed', 'delivery_id' => $delivery->id, 'flag' => 1);
                $this->saveImage(NULL,$destinationPath,$data);
                
            }

            if ($request->amount_status) {
                 $data = array('order_id' => $order->id, 'event' => 'Amount Status Changed', 'user_id' => $user_id, 'status' => 'Amount Status Changed', 'delivery_id' => $delivery->id, 'flag' => 1);
                $this->saveImage(NULL,$destinationPath,$data);
                
            }


            if ($request->supplly_station_status) {
                 $data = array('order_id' => $order->id, 'event' => 'Supplly Station Status Changed', 'user_id' => $user_id, 'status' => 'Supplly Station Status Changed', 'delivery_id' => $delivery->id, 'flag' => 1);
                $this->saveImage(NULL,$destinationPath,$data);
                
            }

            if ($request->case_status) {
                 $data = array('order_id' => $order->id, 'event' => 'Case Status Changed', 'user_id' => $user_id, 'status' => 'Case Status Changed', 'delivery_id' => $delivery->id, 'flag' => 1);
                $this->saveImage(NULL,$destinationPath,$data);
                
            }


            if ($request->status) {
                $status = $request->status == 0 ? 'Rejected' : 'Approved';
                $data = array('order_id' => $order->id, 'event' => 'Order '.$status, 'user_id' => $user_id, 'status' =>  'Order '.$status, 'delivery_id' => $delivery->id, 'flag' => 1);
                $this->saveImage(NULL,$destinationPath,$data);
                
            }

            if ($request->no_of_case) {
                 $data = array('order_id' => $order->id, 'event' => 'Number of case updated', 'user_id' => $user_id, 'status' => 'Number of case updated', 'delivery_id' => $delivery->id, 'flag' => 1);
                $this->saveImage(NULL,$destinationPath,$data);
                
            }

            if ($request->price) {
                 $data = array('order_id' => $order->id, 'event' => 'Price updated', 'user_id' => $user_id, 'status' => 'Price updated', 'delivery_id' => $delivery->id, 'flag' => 1);
                $this->saveImage(NULL,$destinationPath,$data);
                
            }

            if ($request->veepee_invoice_number) {
                 $data = array('order_id' => $order->id, 'event' => 'Veepee Invoice number updated', 'user_id' => $user_id, 'status' => 'Veepee Invoice number updated', 'delivery_id' => $delivery->id, 'flag' => 1);
                $this->saveImage(NULL,$destinationPath,$data);
                
            }
            $data1 = array('order_id' => $order->id, 'event' => 'Receive document', 'user_id' => $user_id, 'status' => 'Done', 'delivery_id' => $delivery->id, 'flag' => 1);
         //   OrderTrackingModel::create($data1);

           $data = array('order_id' =>$order->id,
                    'no_of_case' => $request->no_of_case ?? $delivery->no_of_case,
                    'price' => $request->price ?? $delivery->price,
                    'supplier_invoice' => $request->supplier_invoice ?? $delivery->supplier_invoice,
                    'veepee_invoice_number' => $request->veepee_invoice_number ?? $delivery->veepee_invoice_number,
                    'order_form' => @$order_form ?? $delivery->order_form,
                    'supplier_bill' => @$supplier_bill ?? $delivery->supplier_bill,
                    'transport_bill' => @$transport_bill ?? $delivery->transport_bill,
                    'eway_bill' => @$eway_bill ?? $delivery->eway_bill,
                    'credit_note' => @$credit_note ?? $delivery->credit_note,
                    'debit_note' => @$debit_note ?? $delivery->debit_note,
                    'courier_doc' => @$courier_doc ?? $delivery->courier_doc,
                    'invoice' => @$invoice ?? $delivery->invoice,
                    'remark' => $request->remark,
                    'bo_case_number' => $request->bo_case_number,
                    'bo_amount' => $request->bo_amount,
                    'supplier_firm_status' => $request->supplier_firm_status ?? 0, 
                    'bilty_date_status' => $request->bilty_date_status ?? 0, 
                    'amount_status' => $request->amount_status ?? 0, 
                    'supplly_station_status' => $request->supplly_station_status ?? 0, 
                    'case_status' => $request->case_status ?? 0, 
                    'reject_reason' => $request->reject_reason,
                    'status' => $request->status ?? $delivery->status,
                    
                        );
            //echo '<pre>';print_r($data); die;
            $delivery->update($data);
            $spend_order_amount = OrderDeliveryModel::where('order_id',$id)->where('status',1)->sum('price');
            $order->update(['spend_order_amount' =>$spend_order_amount ]);
            return redirect(route('admin.orders.delivery', $order->id))->withSuccess('This order has been created successfully.');

            } else {
                $order_id = $id;
                $title = trans('global.add');
                return view('admin.orders.add_update_delivery', compact('order_id','title','order'));
            }
        } else {
            return redirect(route('admin.orders.delivery', $order->id))->withErrors('All cases for this order has been dispatched successfully.So you are not able to create new delivery.');
        }
    }

    public function edit_delivery(Request $request,$id){
        abort_if(Gate::denies('edit_order_delivery'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $delivery = OrderDeliveryModel::where('id',$id)->first();

        if($request->all()){
        // echo "<pre>"; print_r($request->all()); die;
        $order = OrderModel::where('id','=',$delivery->order_id)->first();
        $delivered_case = OrderDeliveryModel::where('order_id',$delivery->order_id)->where('status',1)->sum('no_of_case');
        $delivered_case_amount = OrderDeliveryModel::where('order_id',$delivery->order_id)->where('status',1)->sum('price');
        $remaining_case = $order->pkt_cases-$delivered_case+$delivery->no_of_case;
        $remaining_amount = $order->order_amount-$delivered_case_amount;
        $validation_rule = Validator::make($request->all(), [
            'no_of_case'=>'lte:'.$remaining_case,
            'price'=>'lte:'. $remaining_amount,
            'supplier_invoice'=>'required',
            
        ]);
        $validation_rule->validate();

        $destinationPath = public_path('/images/order_request');
       
        // print_r($delivery); die;
        $user_id = Auth::id();

        if ($files = $request->file('order_form')) {
           $data = array('order_id' => $order->id, 'event' => 'Order form uploaded', 'user_id' => $user_id,'delivery_id' => $delivery->id, 'status' => 'File Uploaded', 'flag' => 1);
           $order_form = $this->saveImage($request->file('order_form'),$destinationPath,$data);
        }

        if ($filess = $request->file('supplier_bill')) {
            $data = array('order_id' => $order->id, 'event' => 'Order form uploaded', 'user_id' => $user_id, 'status' => 'File Uploaded', 'delivery_id' => $delivery->id,'flag' => 1);
            $supplier_bill = $this->saveImage($request->file('supplier_bill'),$destinationPath,$data); 
        }
        
        if ($files = $request->file('transport_bill')) {
            $data = array('order_id' => $order->id, 'event' => 'Transport bill uploaded', 'user_id' => $user_id,'delivery_id' => $delivery->id, 'status' => 'File Uploaded', 'flag' => 1);
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

        if ($files = $request->file('debit_note')) {
            $data = array('order_id' => $order->id, 'event' => 'Debit note uploaded', 'user_id' => $user_id,'delivery_id' => $delivery->id, 'status' => 'File Uploaded', 'flag' => 1);
            $debit_note = $this->saveImage($request->file('debit_note'),$destinationPath,$data);
        }

        if ($files = $request->file('courier_doc')) {
            $data = array('order_id' => $order->id, 'event' => 'Courier document uploaded','delivery_id' => $delivery->id, 'user_id' => $user_id, 'status' => 'File Uploaded', 'flag' => 1);
            $courier_doc = $this->saveImage($request->file('courier_doc'),$destinationPath,$data);
        }

        if ($files = $request->file('invoice')) {
             $data = array('order_id' => $order->id, 'event' => 'Invoice uploaded', 'user_id' => $user_id, 'status' => 'File Uploaded', 'flag' => 1);
            $invoice = $this->saveImage($request->file('invoice'),$destinationPath,$data);
        }

        if ($request->supplier_firm_status OR $request->supplier_firm_status!= $delivery->supplier_firm_status) {
             $data = array('order_id' => $order->id, 'event' => 'Supplier Firm Status Changed', 'user_id' => $user_id, 'status' => 'Supplier Firm Status Changed', 'delivery_id' => $delivery->id, 'flag' => 1);
            $this->saveImage(NULL,$destinationPath,$data);
        }

        if ($request->bilty_date_status OR $request->bilty_date_status!= $delivery->bilty_date_status) {
             $data = array('order_id' => $order->id, 'event' => 'Bilty date status changed', 'user_id' => $user_id, 'status' => 'Bilty date status changed', 'delivery_id' => $delivery->id, 'flag' => 1);
            $this->saveImage(NULL,$destinationPath,$data);
        }

        if ($request->amount_status OR $request->amount_status!= $delivery->amount_status) {
            $data = array('order_id' => $order->id, 'event' => 'Amount Status Changed', 'user_id' => $user_id, 'status' => 'Amount Status Changed', 'delivery_id' => $delivery->id, 'flag' => 1);
            $this->saveImage(NULL,$destinationPath,$data);
        }


        if ($request->supplly_station_status OR $request->supplly_station_status!= $delivery->supplly_station_status) {
            $data = array('order_id' => $order->id, 'event' => 'Supplly Station Status Changed', 'user_id' => $user_id, 'status' => 'Supplly Station Status Changed', 'delivery_id' => $delivery->id, 'flag' => 1);
            $this->saveImage(NULL,$destinationPath,$data);
        }

        if ($request->case_status OR $request->case_status!= $delivery->case_status) {
            $data = array('order_id' => $order->id, 'event' => 'Case Status Changed', 'user_id' => $user_id, 'status' => 'Case Status Changed', 'delivery_id' => $delivery->id, 'flag' => 1);
            $this->saveImage(NULL,$destinationPath,$data);
        }


        if ($request->status != ''  OR $request->status!= $delivery->status) {
            $status = $request->status == 0 ? 'Rejected' : 'Approved';
            $data = array('order_id' => $order->id, 'event' => 'Order '.$status, 'user_id' => $user_id, 'status' =>  'Order '.$status, 'delivery_id' => $delivery->id, 'flag' => 1);
            $this->saveImage(NULL,$destinationPath,$data);
        }

        if ($request->no_of_case OR $request->no_of_case!= $delivery->no_of_case) {
             $data = array('order_id' => $order->id, 'event' => 'Number of case updated', 'user_id' => $user_id, 'status' => 'Number of case updated', 'delivery_id' => $delivery->id, 'flag' => 1);
            $this->saveImage(NULL,$destinationPath,$data);
        }

        if ($request->price OR $request->price!= $delivery->price) {
            $data = array('order_id' => $order->id, 'event' => 'Price updated', 'user_id' => $user_id, 'status' => 'Price updated', 'delivery_id' => $delivery->id, 'flag' => 1);
            $this->saveImage(NULL,$destinationPath,$data);
        }

        if ($request->veepee_invoice_number OR $request->veepee_invoice_number!= $delivery->veepee_invoice_number) {
            $data = array('order_id' => $order->id, 'event' => 'Veepee bill create', 'user_id' => $user_id, 'status' => 'Done',  'delivery_id' => $delivery->id, 'flag' => 1);
            OrderTrackingModel::create($data);
            $this->saveImage(NULL,$destinationPath,$data);
        }


        $data = array('order_id' =>$order->id,
                    'supplier_invoice' => $request->supplier_invoice ?? $delivery->supplier_invoice,
                    'no_of_case' => $request->no_of_case ?? $delivery->no_of_case,
                    'price' => $request->price ?? $delivery->price,
                    'veepee_invoice_number' => $request->veepee_invoice_number ?? $delivery->veepee_invoice_number,
                    'order_form' => @$order_form ?? $delivery->order_form,
                    'supplier_bill' => @$supplier_bill ?? $delivery->supplier_bill,
                    'transport_bill' => @$transport_bill ?? $delivery->transport_bill,
                    'eway_bill' => @$eway_bill ?? $delivery->eway_bill,
                    'credit_note' => @$credit_note ?? $delivery->credit_note,
                    'debit_note' => @$debit_note ?? $delivery->debit_note,
                    'courier_doc' => @$courier_doc ?? $delivery->courier_doc,
                    'invoice' => @$invoice ?? $delivery->invoice,
                    'remark' => $request->remark,
                    'bo_case_number' => $request->bo_case_number,
                    'bo_amount' => $request->bo_amount,
                    'supplier_firm_status' => $request->supplier_firm_status ?? $delivery->supplier_firm_status, 
                    'bilty_date_status' => $request->bilty_date_status ?? $delivery->bilty_date_status, 
                    'amount_status' => $request->amount_status ?? $delivery->amount_status, 
                    'supplly_station_status' => $request->supplly_station_status ?? $delivery->supplly_station_status, 
                    'case_status' => $request->case_status ?? $delivery->case_status, 
                    'reject_reason' => $request->reject_reason,
                    'status' => $request->status ?? $delivery->status,
                    
                        );

        $delivery->update($data);
        $spend_order_amount = OrderDeliveryModel::where('order_id',$delivery->order_id)->where('status',1)->sum('price');
        //print_r($spend_order_amount); exit();
        $order->update(['spend_order_amount' =>$spend_order_amount ]);
        return redirect(route('admin.orders.delivery',$delivery->order_id))->withSuccess('This delivery has been updated successfully.');

        } else {
            $order_id = $delivery->id;
            $title = trans('global.edit');
            return view('admin.orders.add_update_delivery', compact('order_id','title','delivery'));
        }
        
    }

    public function add_invoice(Request $request, $id){
          
        abort_if(Gate::denies('edit_order_delivery'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $delivery = OrderDeliveryModel::where('id',$id)->first();
       
        $delivery1 = OrderDeliveryModel::where('veepee_invoice_number',$request->veepee_invoice_number)->first();
          
          
       if(!empty($delivery1) && $request->veepee_invoice_number!=''){
           return redirect(route('admin.pending_bill'))->withErrors('Veepee invoice number already added.');
       }
         
        
        if($request->all()){
        //echo "<pre>"; print_r($request->all()); die;
        $order = OrderModel::where('id','=',$delivery->order_id)->first();
        $delivered_case = OrderDeliveryModel::where('order_id',$delivery->order_id)->where('status',1)->sum('no_of_case');
        $delivered_case_amount = OrderDeliveryModel::where('order_id',$delivery->order_id)->where('status',1)->sum('price');
        $remaining_case = $order->pkt_cases-$delivered_case+$delivery->no_of_case;
        $remaining_amount = $order->order_amount-$delivered_case_amount;
        
        /*
        $validation_rule = Validator::make($request->all(), [
            'no_of_case'=>'lte:'.$remaining_case,
            'price'=>'lte:'. $remaining_amount,
        ]);
        $validation_rule->validate();
        */
        
        $destinationPath = public_path('/images/order_request');
       
        // print_r($delivery); die;
        $user_id = Auth::id();
       
        
        if(!empty($request->veepee_invoice_number) && $request->veepee_invoice_number !=null){
            if(OrderDeliveryModel::checkDuplicate($request->veepee_invoice_number,$id)) {
                return redirect(route('admin.check_document'))->withError('This inovice number already assinged.');
            }
        }

        if ($files = $request->file('order_form')) {
           $data = array('order_id' => $order->id, 'event' => 'Order form uploaded', 'user_id' => $user_id,'delivery_id' => $delivery->id, 'status' => 'File Uploaded', 'flag' => 1);
           $order_form = $this->saveImage($request->file('order_form'),$destinationPath,$data);
        }

        if ($filess = $request->file('supplier_bill')) {
                $data = array('order_id' => $order->id, 'event' => 'Order form uploaded', 'user_id' => $user_id, 'status' => 'File Uploaded', 'delivery_id' => $delivery->id,'flag' => 1);
               $supplier_bill = $this->saveImage($request->file('supplier_bill'),$destinationPath,$data); 
        }
        
        if ($files = $request->file('transport_bill')) {
             $data = array('order_id' => $order->id, 'event' => 'Transport bill uploaded', 'user_id' => $user_id,'delivery_id' => $delivery->id, 'status' => 'File Uploaded', 'flag' => 1);
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

        if ($files = $request->file('debit_note')) {
             $data = array('order_id' => $order->id, 'event' => 'Debit note uploaded', 'user_id' => $user_id,'delivery_id' => $delivery->id, 'status' => 'File Uploaded', 'flag' => 1);
            $debit_note = $this->saveImage($request->file('debit_note'),$destinationPath,$data);
            
        }

        if ($files = $request->file('courier_doc')) {
             $data = array('order_id' => $order->id, 'event' => 'Courier document uploaded','delivery_id' => $delivery->id, 'user_id' => $user_id, 'status' => 'File Uploaded', 'flag' => 1);
            $courier_doc = $this->saveImage($request->file('courier_doc'),$destinationPath,$data);
            
        }

        if ($files = $request->file('invoice')) {
             $data = array('order_id' => $order->id, 'event' => 'Invoice uploaded', 'user_id' => $user_id, 'status' => 'File Uploaded', 'flag' => 1);
            $invoice = $this->saveImage($request->file('invoice'),$destinationPath,$data);
            
        }

        if ($request->supplier_firm_status OR $request->supplier_firm_status!= $delivery->supplier_firm_status) {
             $data = array('order_id' => $order->id, 'event' => 'Supplier Firm Status Changed', 'user_id' => $user_id, 'status' => 'Supplier Firm Status Changed', 'delivery_id' => $delivery->id, 'flag' => 1);
            $this->saveImage(NULL,$destinationPath,$data);
            
        }

        if ($request->bilty_date_status OR $request->bilty_date_status!= $delivery->bilty_date_status) {
             $data = array('order_id' => $order->id, 'event' => 'Bilty date status changed', 'user_id' => $user_id, 'status' => 'Bilty date status changed', 'delivery_id' => $delivery->id, 'flag' => 1);
            $this->saveImage(NULL,$destinationPath,$data);
            
        }

        if ($request->amount_status OR $request->amount_status!= $delivery->amount_status) {
             $data = array('order_id' => $order->id, 'event' => 'Amount Status Changed', 'user_id' => $user_id, 'status' => 'Amount Status Changed', 'delivery_id' => $delivery->id, 'flag' => 1);
            $this->saveImage(NULL,$destinationPath,$data);
            
        }


        if ($request->supplly_station_status OR $request->supplly_station_status!= $delivery->supplly_station_status) {
             $data = array('order_id' => $order->id, 'event' => 'Supplly Station Status Changed', 'user_id' => $user_id, 'status' => 'Supplly Station Status Changed', 'delivery_id' => $delivery->id, 'flag' => 1);
            $this->saveImage(NULL,$destinationPath,$data);
            
        }

        if ($request->case_status OR $request->case_status!= $delivery->case_status) {
             $data = array('order_id' => $order->id, 'event' => 'Case Status Changed', 'user_id' => $user_id, 'status' => 'Case Status Changed', 'delivery_id' => $delivery->id, 'flag' => 1);
            $this->saveImage(NULL,$destinationPath,$data);
            
        }


        if ($request->status != ''  OR $request->status!= $delivery->status) {
            $status = $request->status == 0 ? 'Rejected' : 'Approved';
            $data = array('order_id' => $order->id, 'event' => 'Order '.$status, 'user_id' => $user_id, 'status' =>  'Order '.$status, 'delivery_id' => $delivery->id, 'flag' => 1);
            $this->saveImage(NULL,$destinationPath,$data);
            
        }

        if ($request->no_of_case OR $request->no_of_case!= $delivery->no_of_case) {
             $data = array('order_id' => $order->id, 'event' => 'Number of case updated', 'user_id' => $user_id, 'status' => 'Number of case updated', 'delivery_id' => $delivery->id, 'flag' => 1);
            $this->saveImage(NULL,$destinationPath,$data);
            
        }

        if ($request->price OR $request->price!= $delivery->price) {
             $data = array('order_id' => $order->id, 'event' => 'Price updated', 'user_id' => $user_id, 'status' => 'Price updated', 'delivery_id' => $delivery->id, 'flag' => 1);
            $this->saveImage(NULL,$destinationPath,$data);
            
        }

        if ($request->veepee_invoice_number OR $request->veepee_invoice_number!= $delivery->veepee_invoice_number) {
             $data = array('order_id' => $order->id, 'event' => 'Veepee bill create', 'user_id' => $user_id, 'status' => 'Done',  'delivery_id' => $delivery->id, 'flag' => 1);
            OrderTrackingModel::create($data);
            $this->saveImage(NULL,$destinationPath,$data);
            
        }


        $data = array('order_id' =>$order->id,
                    'no_of_case' => $request->no_of_case ?? $delivery->no_of_case,
                    'price' => $request->price ?? $delivery->price,
                    'veepee_invoice_number' => $request->veepee_invoice_number ?? $delivery->veepee_invoice_number,
                    'order_form' => @$order_form ?? $delivery->order_form,
                    'supplier_bill' => @$supplier_bill ?? $delivery->supplier_bill,
                    'transport_bill' => @$transport_bill ?? $delivery->transport_bill,
                    'eway_bill' => @$eway_bill ?? $delivery->eway_bill,
                    'credit_note' => @$credit_note ?? $delivery->credit_note,
                    'debit_note' => @$debit_note ?? $delivery->debit_note,
                    'courier_doc' => @$courier_doc ?? $delivery->courier_doc,
                    'invoice' => @$invoice ?? $delivery->invoice,
                    'remark' => $request->remark,
                    'bo_case_number' => $request->bo_case_number ?? $delivery->bo_case_number,
                    'bo_amount' => $request->bo_amount ?? $delivery->bo_amount,
                    'supplier_firm_status' => $request->supplier_firm_status ?? $delivery->supplier_firm_status, 
                    'bilty_date_status' => $request->bilty_date_status ?? $delivery->bilty_date_status, 
                    'amount_status' => $request->amount_status ?? $delivery->amount_status, 
                    'supplly_station_status' => $request->supplly_station_status ?? $delivery->supplly_station_status, 
                    'case_status' => $request->case_status ?? $delivery->case_status, 
                    'reject_reason' => $request->reject_reason,
                    'status' => $request->status ?? $delivery->status,
                    
                        );

        $delivery->update($data);
        $spend_order_amount = OrderDeliveryModel::where('order_id',$delivery->order_id)->where('status',1)->sum('price');
        //print_r($spend_order_amount); exit();
        $order->update(['spend_order_amount' =>$spend_order_amount ]);
        return redirect(route('admin.pending_bill'))->withSuccess('Invoice has been updated successfully.');

        } else {
            $order_id = $delivery->id;
            $title = trans('global.edit');
            return view('admin.orders.add_update_invoice', compact('order_id','title','delivery'));
        }
    }

    public function dispatch_delivery(Request $request, $id){
        $dispatched = OrderDeliveryModel::where('id',$id)->where('status',1)->first();
        $user_id  = Auth::user()->id;
        if($request->all()){
            $courier_doc = '';
            if(@$dispatched){
                
                $dispatched->update(['dispatch' => 1]);
                $destinationPath = public_path('/images/order_request');
                if ($files = $request->file('courier_doc')) {
                   // @unlink($destinationPath.'/'.$dispatched->courier_doc);
                     $data = array('order_id' => $dispatched->order_id, 'event' => 'Courier document uploaded', 'user_id' => $user_id, 'status' => 'File Uploaded', 'flag' => 1);
                    $courier_doc = $this->saveImage($request->file('courier_doc'),$destinationPath,$data);
                    
                }

                CourierModel::create([
                    'order_id' => $dispatched->order_id, 
                    'delivery_id' => $dispatched->id, 
                    'courier_name' => $request->courier_name, 
                    'courier_id' => $request->courier_id, 
                    'courier_date' => $request->courier_date,
                    'courier_doc' => $courier_doc

                ]);
                $trackorder =  OrderDeliveryModel::where('order_id',$dispatched->order_id)->where('status',1)->get();
                $order = OrderModel::where('id',$dispatched->order_id)->first();

                
                $dispatchdata = array('order_id' => $dispatched->order_id, 'event' => 'Courier to buyer.
                	courier name :' .$request->courier_name . 
                    ', courier id : ' .$request->courier_id .
                    ', courier date : ' . $request->courier_date.
                    ',courier document : ' . url('/images/order_request/'.$courier_doc), 'user_id' => Auth::id(), 'status' => 'Done', 'delivery_id' => $id, 'flag' => 1);
               // print_r($dispatchdata); die;
                OrderTrackingModel::create($dispatchdata);
                $this->saveImage(NULL,NULL,$dispatchdata);

                $remaining_cases = $order->pkt_cases - $trackorder->sum('no_of_case');
                $msg = 'We have dispatched your order (Order ID : '.$order->vporder_id.') <br> Order Details are : Number of case : '.$dispatched['no_of_case'].'<br> Price INR '.$dispatched->price. '<br> Remaining cases : '.$remaining_cases.'. <br> Please collect your order.';

                /*if(($trackorder->sum('no_of_case') == $order->pkt_cases) OR (($order->order_amount - remaing_amount($order->id)) < 10000)){
                    $order->update(['status' => 'Completed']);

                    $completedata = array('order_id' => $dispatched->order_id, 'event' => 'This order has been completed successfully.', 'user_id' => Auth::id(), 'status' => 'order completed', 'delivery_id' => NULL, 'flag' => 1);

                    $this->saveImage(NULL,NULL,$completedata);
                     $msg = 'We have dispatched your order (Order ID : '.$order->vporder_id.')<br> Order Details are : Number of case : '.$dispatched['no_of_case'].'<br> Price INR '.$dispatched->price. '<br> Please collect your order. Now your order has been completed.';

                }*/


                $buyer = getUser($order->buyer_id);
                $supplier = getUser($order->supplier_id);
                $transport = getTransportDetail($order->transport_one_id);
                $pdfdata = array('order' => $order,'buyer' => $buyer, 'supplier' => $supplier,'dispatch' => $dispatched, 'transport' => $transport);
                $pdf = PDF::loadView('emails.invoice',$pdfdata);
                $pdf->setPaper('a4', 'portrait');
                $pdf->setOptions(['dpi' => 120, 'defaultFont' => 'sans-serif']);



                $data = array('name' =>$buyer->name,  'msg'=> $msg, 'order_id' => $order->vporder_id);
                Mail::send('emails.order_dispatched',$data, function ($message) use($buyer,$supplier,$pdf) {
                    $message->to($buyer->email,$buyer->name)->subject('Veepee Internatonal- Order dispatched');
                    $message->bcc($supplier->email,$supplier->name);
                    $message->attachData($pdf->output(), "invoice.pdf");
                   // $message->bcc($supplier->email,$supplier->name);
                });

                /*push to buyer and supplier*/
                //notifyAndroid([$buyer->id,$supplier->id],'Veepee Internatonal',  $msg);

                return redirect(route('admin.courier_detail'))->withSuccess('This order has been dispatched successfully.');
            } else {
                return redirect(route('admin.courier_detail', $order->id))->withErrors('This order has been already dispatched.');
            }
        } else {
            return view('admin.orders.courier');
        }
        
    }
    
    
     public function edit_dispatch_delivery(Request $request, $id){
        $dispatched = CourierModel::where('delivery_id',$id)->first();
        $user_id  = Auth::user()->id;
        if($request->all()){
            $courier_doc = $dispatched->courier_doc;
            if(@$dispatched){
                
                $destinationPath = public_path('/images/order_request');
                if ($files = $request->file('courier_doc')) {
                   // @unlink($destinationPath.'/'.$dispatched->courier_doc);
                     $data = array('order_id' => $dispatched->order_id, 'event' => 'Courier document uploaded', 'user_id' => $user_id, 'status' => 'File Uploaded', 'flag' => 1);
                    $courier_doc = $this->saveImage($request->file('courier_doc'),$destinationPath,$data);
                    
                }

                $dispatched->update([
                    
                    'courier_name' => $request->courier_name, 
                    'courier_id' => $request->courier_id, 
                    'courier_date' => $request->courier_date,
                    'courier_doc' => $courier_doc

                ]);
                
                return redirect(route('admin.courier_detail'))->withSuccess('This order has been dispatched successfully.');
            } else {
                return redirect(route('admin.courier_detail', $order->id))->withErrors('This order has been already dispatched.');
            }
        } else {
            $delivery =  CourierModel::where('delivery_id',$id)->first();
            return view('admin.orders.courier',compact('delivery'));
        }
        
    }

    public function delete_delivery(Request $request, $id){
        abort_if(Gate::denies('delete_order_delivery'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            $delivery = OrderDeliveryModel::where('order_id',$id)->get();
            return view('admin.orders.delivery', compact('orders','delivery'));
        
    }

    public function edit($id){
        
        abort_if(Gate::denies('order_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$order      = OrderModel::where('id','=',$id)->first();
        $suppliers  = User::where('branch_id',$order->branch_id)->where('user_type','supplier')->get();
        
        $transports = TransportsModels::where('branch_id',$order->branch_id)->where('status',1)->get();
        return view('admin.orders.edit', compact('order','suppliers','transports'));
    }

    public function update(Request $request, $id){
        $user_id = Auth::id();
        $order = OrderModel::where('id','=',$id)->first();
        $destinationPath = public_path('/images/order_request');

        if ($files = $request->file('order_form')) {
           @unlink($destinationPath.'/'.NULL);
           $data = array('order_id' => $order->id, 'event' => 'Order form uploaded', 'user_id' => $user_id, 'status' => 'File Uploaded', 'flag' => 1);
           $order_form = $this->saveImage($request->file('order_form'),$destinationPath,$data);

            
        }

        if ($files = $request->file('supplier_bill')) {
            @unlink($destinationPath.'/'.$order->supplier_bill);
             $data = array('order_id' => $order->id, 'event' => 'Supplier bill uploaded', 'user_id' => $user_id, 'status' => 'File Uploaded', 'flag' => 1);
            $supplier_bill = $this->saveImage($request->file('supplier_bill'),$destinationPath,$data);
            
        }

        if ($files = $request->file('transport_bill')) {
            @unlink($destinationPath.'/'.$order->transport_bill);
             $data = array('order_id' => $order->id, 'event' => 'Transport bill uploaded', 'user_id' => $user_id, 'status' => 'File Uploaded', 'flag' => 1);
            $transport_bill = $this->saveImage($request->file('transport_bill'),$destinationPath,$data);
            
        }

        if ($files = $request->file('eway_bill')) {
            @unlink($destinationPath.'/'.$order->eway_bill);
             $data = array('order_id' => $order->id, 'event' => 'E-Way bill uploaded', 'user_id' => $user_id, 'status' => 'File Uploaded', 'flag' => 1);
            $eway_bill = $this->saveImage($request->file('eway_bill'),$destinationPath,$data);
            
        }

        if ($files = $request->file('credit_note')) {
            @unlink($destinationPath.'/'.$order->credit_note);
             $data = array('order_id' => $order->id, 'event' => 'Credit note uploaded', 'user_id' => $user_id, 'status' => 'File Uploaded', 'flag' => 1);
            $credit_note = $this->saveImage($request->file('credit_note'),$destinationPath,$data);
            
        }

        if ($files = $request->file('debit_note')) {
            @unlink($destinationPath.'/'.$order->debit_note);
             $data = array('order_id' => $order->id, 'event' => 'Debit note uploaded', 'user_id' => $user_id, 'status' => 'File Uploaded', 'flag' => 1);
            $debit_note = $this->saveImage($request->file('debit_note'),$destinationPath,$data);
            
        }

        if ($files = $request->file('courier_doc')) {
            @unlink($destinationPath.'/'.$order->courier_doc);
             $data = array('order_id' => $order->id, 'event' => 'Courier document uploaded', 'user_id' => $user_id, 'status' => 'File Uploaded', 'flag' => 1);
            $courier_doc = $this->saveImage($request->file('courier_doc'),$destinationPath,$data);
            
        }

        if ($files = $request->file('invoice')) {
            @unlink($destinationPath.'/'.$order->invoice);
             $data = array('order_id' => $order->id, 'event' => 'Invoice uploaded', 'user_id' => $user_id, 'status' => 'File Uploaded', 'flag' => 1);
            $invoice = $this->saveImage($request->file('invoice'),$destinationPath,$data);
            
        }

        $data = array('order_id' =>$order->id,
                    'no_of_case' => $request->no_of_case,
                    'price' => $request->price,
                    'transport_one_id' => $request->transport_one_id,
                    'transport_two_id' => $request->transport_two_id,
                    'veepee_invoice_number' => $request->veepee_invoice_number,
                    'order_form' => @$order_form ?? $order->order_form,
                    'supplier_bill' => @$supplier_bill ?? $order->supplier_bill,
                    'transport_bill' => @$transport_bill ?? $order->transport_bill,
                    'eway_bill' => @$eway_bill ?? $order->eway_bill,
                    'credit_note' => @$credit_note ?? $order->credit_note,
                    'debit_note' => @$debit_note ?? $order->debit_note,
                    'courier_doc' => @$courier_doc ?? $order->courier_doc,
                    'remark' => $request->remark,
                    'bo_case_number' => $request->bo_case_number,
                    'bo_amount' => $request->bo_amount,
                    'invoice' => @$invoice ?? $order->invoice,
                    'status' => $request->status,
                        );
        OrderDeliveryModel::create($data);
        return redirect()->back();
    }

    public function show($id){
       
        abort_if(Gate::denies('order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $orders = OrderModel::where('id',$id)->first();
         
        return view('admin.orders.show', compact('orders'));
    }
    
  public function reorder($id){
       
        abort_if(Gate::denies('order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $orders = OrderModel::where('id',$id)->first();
         
        return view('admin.orders.reorder', compact('orders'));
    }
    
    public function acceptorder($id){
       
        abort_if(Gate::denies('order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $orders = OrderModel::where('id',$id)->first();
        $ordersItem = OrderItemModel::select('order_items.name','brands.name as brands_name')->where('order_id',$id)->join('brands', 'brands.id', '=', 'order_items.brand_id')->get();
         $branches = BranchModel::where('status',1)->get(['name','id']);
         $sisuser = User::where('id',$orders->supplier_id)->first();
         $sisfrm = SuppliersModels::where('user_id',$orders->supplier_id)->first(); 
         
         $expsisfrm=explode(",",$sisfrm->sister_firm);
         
            
     if(count($expsisfrm)>0){
    foreach($expsisfrm as $frm){
    $sisterfirms[] = User::where('id',$frm)->first();
     }
     }else{
         $sisterfirms =[];
     }
             
            
       // dd($ordersItem); die;
        return view('admin.orders.acceptorder', compact('orders','branches','sisterfirms','sisuser','ordersItem'));
    }
    
    public function rejectorder($id){
       
        abort_if(Gate::denies('order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
         $orders = OrderModel::where('id',$id)->first();
        if($orders->status=='New'){
         
       $reasons = RejectReasonModel::where('usertype','Supplier')->where('status',1)->get();
        }else{
            $reasons = RejectReasonModel::where('usertype','Buyer')->where('status',1)->get();
        }
       
        
         $branches = BranchModel::where('status',1)->get(['name','id']);
         $sisterfirms = User::where('branch_id',$orders->branch_id)->get(); 
        return view('admin.orders.rejectorder', compact('orders','branches','sisterfirms','reasons'));
    }
    
    public function rejectorders(Request $request){
        if($request->order_id!=''){
               
             $order = OrderModel::where('id',$request->order_id)->first();
              
        }
        else{
                return redirect()->back()->withErrors('There is no order selected.'); 
            }
        if($request->all()){
         $user= Auth::user();
                         //print_r($status); die;
                        /*$notification = 'DEAR '.$supplier->name.', '.$supplier->veepeeuser_id.',  UNFORTUNATELY YOUR ORD. NO- '.$order->vporder_id.',HAS BEEN  CANCELLED DUE TO '.$reason.',BUYER-'.$buyer->name.',FOR MORE DETAILS CLICK('.url("order/show/".$order->id).' ), PLEASE CONTACT TO-7718277182';*/
                        OrderCancelModel::insert(['order_id'=>$request->order_id,'supplier_id'=>$order->supplier_id,'reason'=>$request->reason,'status'=>'Rejected','cancelled_by'=>$user->id]);
                        //send_sms('9555699534',$notification);
                        OrderModel::where(['id'=> $request->order_id])->update(['supplier_accept' =>0,'status' => 'Rejected','reason'=>$request->reason,'remark'=>@$request->remark,'users_id'=>$user->id,'face'=>'web']);
              
             
            
            return redirect('admin/orders')->withSuccess('Order has been rejected successfully.');
        }  
    }
    
    public function supplier_accept_order(Request $request){
        if($request->order_id!=''){
               
             $order = OrderModel::where('id',$request->order_id)->first();
        }
        else{
                return redirect()->back()->withErrors('There is no order selected.'); 
            }
        if($request->all()){
         
            
 
       if($request->sister_firm!=''){
           
       $data = array('supplier_id' => $request->sister_firm,'pkt_cases' => $request->pkt_cases,              'order_amount' => $request->order_amount,'supplier_accept' =>1,'status' => 'Confirm');

        
       }else{    
            $data = array('pkt_cases' => $request->pkt_cases,              'order_amount' => $request->order_amount,'supplier_accept' =>1,'status' => 'Confirm');
       }
                    
            $order->update($data);
            
             
            
            return redirect('admin/orders?status=New')->withSuccess('Order has been updated successfully.');
        }  
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
            // $buyermsg = 'DEAR BUYER ('.$buyer->name.', '.$buyer->veepeeuser_id.'), WE HAVE RECEIVED YOUR CONFIRMED ORD. NO-'.$vpordid.', BRAND-'.getBrand($order->brand_id).',FOR MORE DETAILS CLICK '.$links.', PLEASE CONTACT TO-7718277182(VEEPEE)';
                // $suppliermsg = 'CONGRATULATION SUPPLIER ('.$supplier->name.', '.$supplier->veepeeuser_id.'),YOU HAVE RECEIVED OUR CONFIRMED ORD. NO.-'.$vpordid.',BUYER-'.$buyer->name.',PLEASE DISPATCH ORDER BEFORE DUE DATE,FOR MORE DETAILS CLICK '.$links.',PLEASE CONTACT TO 7718277182(VEEPEE)';
                /*$suppliermsg = 'DEAR {{$supplier->name}}, {{$supplier->veepeeuser_id}}, ORD. NO- {{$order->vporder_id}}, HAS BEEN CLOSED, {{$buyer->name}},FOR MORE DETAILS CLICK(<a href="{{url("order/show/".$order->id)}}">LINK</a>),PLEASE CONTACT TO 7718277182';*/
               //$buyer_sms = getBuyers($order->buyer_id);
               // $supplier_sms = getSuplliers($brand->user_id);
                //echo $buyer_sms->notify_sms;
                //echo "-".$supplier_sms->notify_sms;
                //exit;
                //send_sms($buyer_sms->notify_sms,$buyermsg,$buyerdlt);
               // send_sms($supplier_sms->notify_sms,$suppliermsg,$supdlt);
                //send whatsapp notification
                // $whatsappTemplate = 'veepe_ord';
                // $whatsappParameters = array(
                //   array(
                //       "name" => "veepee_msg",
                //       "value" => $buyermsg,
                //     ),
                // );
                // whatsappCurl($whatsappTemplate, $buyer_sms->notify_whatsapp, $whatsappParameters);
                // $whatsappParameters = array(
                //     array(
                //         "name" => "veepee_msg",
                //         "value" => $suppliermsg,
                //       ),
                //   );
                //   whatsappCurl($whatsappTemplate, $supplier_sms->notify_whatsapp, $whatsappParameters);
            OrderTrackingModel::create(['order_id' => $order->id, 'event' =>'Confirm','status' => 'Accepted', 'user_id' => $order->buyer_id]); 
            OrderNotification::dispatch($order->id);
            return true;
            return redirect('admin/orders?status=Waiting for approval')->withSuccess('Order has been updated successfully.');
        }  
    }

    public function destroy($id,OrderModel $OrderModel){
        abort_if(Gate::denies('order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $OrderModel = OrderModel::find($id);
        $OrderModel->delete();
        return back();
    }

    public function delivery_show($id){
       // abort_if(Gate::denies('order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $delivery = OrderDeliveryModel::where('id',$id)->first();
        return view('admin.orders.deliveryshow', compact('delivery'));
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
    
    public function create_order(Request $request){
        //print_r($request->all()); die;
       
        if($request->all()){ 
            $dispatch   =   [];
            if(empty($request->items) && $request->items == null){
                return redirect()->back()->withErrors('There is no item selected for this order.'); 
            }
                $userr          =  User::where('id',$request->buyer_id)->first();
             
                $amount         =  speneded_amount($request->buyer_id);
                //$remaining    =  busy($userr->veepeeuser_id) - $amount;
            
                $data           =  busy($userr->veepeeuser_id);
                $Samount        =  speneded_amount($request->buyer_id);
                $Oamount        =  ordered_amount($request->buyer_id);
                $remaining      =  ($data->amount-($Oamount-$Samount));
          
            //$Bamount     =  busy($userr->veepeeuser_id);
            //$remaining   =  $Bamount->amount-$amount;
            
                if($request->order_amount > $remaining){
                    return redirect()->back()->withErrors('Order amount should be less then '.$remaining);
                }
                 
                if(50000 > $remaining){
                    return redirect()->back()->withErrors('Order amount should be atleast 50000');
                }
                
                if($remaining<50000){
                    return redirect()->back()->withErrors('Remaining amount should be atleast 50000');
                }
                
                $brand      =   BrandModel::where('id',$request->brand_idd)->first();
                //print_r($brand); die;
                // @$lastorder =   OrderModel::latest()->first('id');
                // $oid        =   ($lastorder->id ?? 0)+1;
                
                //           if($lastorder->id==$oid){
                //         $oid = ($lastorder->id ?? 0)+1;  
                 // }
                $branch     =   strtoupper(substr(getBranch($request->branch_id),0,3));
                $LastOrder  =    OrderConfigurationModel::select('order_increment_id')->where(['status'=>'1'])->first();//,'financial_year'=>'24-25'
                $oid= ($LastOrder->order_increment_id + 1);
                $vpOrderId = 'VP'.$branch.'-'.$oid;
                
                $data       =   array('vporder_id' => $vpOrderId,//'VP'.$branch.'-'.$oid,
                                    'buyer_id' => $request->buyer_id,
                                    'supplier_id' => $request->supplier_idd,
                                    'branch_id' => $request->branch_id,
                                    'brand_id' => $request->brand_idd,
                                    'marka' => $request->marka,
                                    'transport_one_id' => $request->transport_one_id,
                                    'transport_two_id' => $request->transport_two_id,
                                    'supply_start_date' => date('Y-m-d h:i:s',strtotime($request->supply_start_date)),
                                    'orderlast_date' => date('Y-m-d h:i:s',strtotime($request->orderlast_date)),
                                    'station' => $request->station,
                                    'pkt_cases' => $request->case_no, //case_no
                                    'case_no' => $request->case_no, //case_no
                                    'remaining_pkt' => NULL,
                                    'supplier_accept' => 1,
                                    'buyer_accept' => 1,
                                    'order_amount' => $request->order_amount,
                                    'amount'=>$request->order_amount, //amount
                                    'color_size_range_etc'=>$request->color_size_range_etc,
                                    'optional'=>$request->optional,
                                    //'status' => 'Confirm',
                                    'status' => 'Waiting for approval',
                                    'supplier_status_flag' =>1,
                                    'merchandiser_name'=> $request->merchandiser_name,
                                    'order_date' => date('Y-m-d'),
                                    'order_by' => $request->order_by,
                                    'users_id'=> Auth::user()->id,
                                    'face'=>'web',
                                );
                if($request->order_id!=''){
                   
                        $order = OrderModel::where('id',$request->order_id)->first();
                        $data       =   array( 
                                    'marka' => $request->marka,
                                    'transport_one_id' => $request->transport_one_id,
                                    'transport_two_id' => $request->transport_two_id,
                                    'supply_start_date' => date('Y-m-d h:i:s',strtotime($request->supply_start_date)),
                                    'orderlast_date' => date('Y-m-d h:i:s',strtotime($request->orderlast_date)),
                                    'station' => $request->station,
                                    'pkt_cases' => $request->case_no, //case_no
                                    'case_no' => $request->case_no, //case_no
                                    'remaining_pkt' => NULL,
                                    'supplier_accept' => 1,
                                    'buyer_accept' => 1,
                                    'order_amount' => $request->order_amount, //amount
                                    'amount'=>$request->order_amount, //amount
                                    'color_size_range_etc'=>$request->color_size_range_etc,
                                    'optional'=>$request->optional,
                                    'status' => 'Confirm',
                                    //'merchandiser_name'=> $request->merchandiser_name,
                                    'order_date' => date('Y-m-d'),
                                    'reason' => null,
                                    'order_by' => $request->order_by,
                                    'users_id'=> Auth::user()->id,
                                    'face'=>'web',
                                    'created_at' => date('Y-m-d h:i:s'),
                                );
                 $order = $order->update($data);
                 $order_cancelModel = DB::table('order_cancel')->where('order_id',$request->order_id)->delete();
                 if($request->items != ''){
                    $OrderModel = DB::table('order_items')->where('order_id',$request->order_id)->delete();
                         
                       
                        
                    foreach ($request->items as $row) {
                        $item = ItemModel::where('id',$row)->first();
                        
                        OrderItemModel::create(['order_id' =>  $request->order_id, 'item_id' => $item->id, 'name' => $item->name, 'quantity' => $request->qty, 'color' => $request->color, 'size' => $request->size, 'article_no' => $request->art,'range'=>$request->range]);    
                    }
                }
                
                if($request->sampleimage != ''){
                     
                   $OrderGalleryModel = DB::table('orders_gallery')->where('order_id',$request->order_id)->delete();
                    foreach ($request->sampleimage as $key=>$img) {
                        $filepath   =   public_path('/images/order_request');
                        if(!\File::exists($filepath)) {
                            \File::makeDirectory($filepath, 0777, true, true);
                        }else{
                            $filename       =  md5($key).time(). '.' . $img->getClientOriginalExtension();
                            $img->move($filepath,$filename);
                            
                            OrderGalleryModel::create(['order_id' =>  $request->order_id, 'image_name'=> $filename]);
                        }
                    }
                }
            }else{
                $order = OrderModel::create($data);
                // OrderModel::where('id', $order->id)->update(['vporder_id' => 'VP'.$branch.'-'.$order->id]);
                OrderConfigurationModel::where('status',1)->update(['order_increment_id'=>$oid]);
                //$datau = array('vporder_id' => 'VP'.$branch.'-'.$order->id);
                $datau = array('vporder_id' => $vpOrderId);    
                OrderModel::where('id',$order->id)->update($datau);
                
                if($request->items != ''){
                    foreach ($request->items as $row) {
                        $item = ItemModel::where('id',$row)->first();
                        OrderItemModel::create(['order_id' =>  $order->id, 'item_id' => $item->id, 'name' => $item->name, 'quantity' => $request->qty, 'color' => $request->color, 'size' => $request->size, 'article_no' => $request->art,'range'=>$request->range]);    
                    }
                }
            
                if($request->sampleimage != ''){
                    foreach ($request->sampleimage as $key=>$img) {
                        $filepath   =   public_path('/images/order_request');
                        if(!\File::exists($filepath)) {
                            \File::makeDirectory($filepath, 0777, true, true);
                        }else{
                            $filename       =  md5($key).time(). '.' . $img->getClientOriginalExtension();
                            $img->move($filepath,$filename);
                            OrderGalleryModel::create(['order_id' =>  $order->id, 'image_name'=> $filename]);
                        }
                    }
                }
                }
                if($request->order_id!=''){
                 $order = OrderModel::where('id',$request->order_id)->first();
                }
            
                $buyer = getUser($request->buyer_id);
                $supplier = getUser($brand->user_id);
                $buyerdlt='1207163378106237130';
                $supdlt='1207163378110074486';
                $vpordid='VP'.$branch.'-'.$order->id;
    
                if(Auth::user()->id!=11332){
                        //  $links         =   url("order/show/".$order->id);
                        $buyermsg = 'DEAR BUYER ('.$buyer->name.', '.$buyer->veepeeuser_id.'), YOU HAVE GOT ONE NEW ORDER, PLEASE.';
                        $suppliermsg = 'DEAR SUPPLIER ('.$supplier->name.', '.$supplier->veepeeuser_id.'),YOU ORDER REQUEST HAS BEEN SENT TO THE SUPPLIER SUCCESSFULLY';
                        /*$suppliermsg = 'DEAR {{$supplier->name}}, {{$supplier->veepeeuser_id}}, ORD. NO- {{$order->vporder_id}}, HAS BEEN CLOSED, {{$buyer->name}},FOR MORE DETAILS CLICK(<a href="{{url("order/show/".$order->id)}}">LINK</a>),PLEASE CONTACT TO 7718277182';*/
                        
                        $buyer_sms = getBuyers($request->buyer_id);
                        $supplier_sms = getSuplliers($brand->user_id);
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
                        OrderTrackingModel::create(['order_id' => $order->id, 'event' =>'Confirm','status' => 'Accepted', 'user_id' => $request->buyer_id]);
                    
                    //OrderNotification::dispatch($order->id);
            }
            if($request->order_id!=''){
                return redirect('admin/orders')->withSuccess('Order has been created successfully & Order#:'.$vpordid);
            }else{
            
            return redirect()->back()->withSuccess('Order has been created successfully & Order#:'.$vpordid);
            }
            
        } else {
            $buyer = BuyerModel::with(['user', 'city'])->get();
            $user_id = Auth::id();
            $loginUser = SuppliersModels::select('branch_id')->where('user_id',$user_id)->first();
            $data =  array(
                'title'         => 'Create Order',
                'buyers'        => $buyer,
                //'buyers'        => User::where('user_type','buyer')->get(), //User::where('user_type','buyer')->where('status',1)->get(),
                'branches'      => BranchModel::where('status',1)->where("id", $loginUser->branch_id)->get(),
                //'transports'    => TransportsModels::where('status',1)->get()
            );
            return view('supplier.orders.create_order',$data); 
        }
    }
    
    public function edit_order(Request $request, $id){
        $order = OrderModel::where('id',$id)->first();
        
        if($request->all()){
            $brand = BrandModel::where('user_id',$request->supplier_id)->first();
            //dd($order,$request->all(),$brand);/
            $data = array('supplier_id' => $request->supplier_id, 'transport_one_id' => $request->transport_one_id,'status' =>$request->status ?? $order->status, 'reason' => $request->reason, 'brand_id'=> $brand->id);
                    
            $order->update($data);
            
            if($request->status == 'Cancelled'){
                OrderCancelModel::insert(['order_id'=>$id,'supplier_id'=>$request->supplier_id,'reason'=>$request->reason,'status'=>$request->status,'cancelled_by'=>Auth::id() ]);
                OrderNotification::dispatch($id);
                return redirect('admin/orders')->withSuccess('Order has been cancelled successfully.');
            }
            
            return redirect('admin/orders')->withSuccess('Order has been updated successfully.');
        } else {
            
            $data =  array(
                    'title' => 'Edit Order',
                    'order' => $order,
                    'supplier' => User::where('user_type','supplier')->where('status',1)->where('branch_id',$order->branch_id)->orderBy('name')->get(),
                   // 'branches' => BranchModel::where('status',1)->get(),
                    'transports' =>TransportsModels::where('status',1)->where('branch_id',$order->branch_id)->orderBy('transport_name')->get() 
                    //TransportsModels::where('status',1)->orderBy('transport_name')->get() 
                    
                );
            return view('admin.orders.edit_order',$data);
        }
    }
    
    public function last_six_month_order(Request $request){
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=report.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        abort_if(Gate::denies('report_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $branches = BranchModel::where('status',1)->get();
        
        if($request->all()){
            // $orders1    =   User::select('users.*', 'orders.*')->join("orders",function($join){
            //                     $join->on("orders.supplier_id","=","users.id");
            //                 });
            \DB::enableQueryLog();
            if($request->account_number){
                $first_character = substr($request->account_number, 0, 1);
                if($first_character == "A" || $first_character == "a") {
                    $buyers = 1;
                }else{
                    $buyers = 2;
                }
            }else{
                $buyers = 0;
            }
            if($buyers == 1){
                $orders1 = User::select('users.*', 'orders.*' , 'brands.name as brand_name', 'order_items.name as item_name')
                ->join('orders', 'orders.buyer_id', '=', 'users.id')
                ->join('brands', 'brands.id', '=', 'orders.brand_id')
                ->join('order_items', 'order_items.order_id', '=', 'orders.id');
            }else{
                $orders1 = User::select('users.*', 'orders.*' , 'brands.name as brand_name', 'order_items.name as item_name')
                ->join('orders', 'orders.supplier_id', '=', 'users.id')
                ->join('brands', 'brands.id', '=', 'orders.brand_id')
                ->join('order_items', 'order_items.order_id', '=', 'orders.id');
            }
            
            //$orders1 = join('orders', 'orders.supplier_id', '=', 'users.id');
            // $orders1 = join('brands', 'brands.id', '=', 'orders.brand_id');
            // $orders1 = join('order_items', 'order_items.order_id', '=', 'orders.id');
            // ->join('buyers', 'buyers.user_id', '=', 'orders.buyer_id')
            // ->join('states', 'states.id', '=', 'buyers.state_id');
            //->where('followers.follower_id', '=', 3)
            //->get();
            //print_r($orders1);              
            if($request->name != ''){
                $orders1->where('users.name','like','%'.$request->name.'%');
            }
            if($request->account_number != ''){
                $orders1->where('users.veepeeuser_id',$request->account_number);
            }
            if($request->branch != ''){
                $orders1->where('orders.branch_id',$request->branch);
            }
            if($request->vporder_id != ''){
                $orders1->where('orders.vporder_id',$request->vporder_id);
            }
            if($request->supplier_accept != ''){
                $orders1->where('orders.supplier_accept',$request->supplier_accept)->where('orders.buyer_accept',0);
            }
            if($request->start_date != '' && $request->end_date != ''){
               $orders1->where('orders.created_at','>=',date('Y-m-d 00:00:00',strtotime($request->start_date)))->where('orders.created_at','<=',date('Y-m-d 23:59:59',strtotime($request->end_date)));
            }
            if($request->start_due_date != '' && $request->end_due_date != ''){
               $orders1->where('orders.orderlast_date','>=',date('Y-m-d 00:00:00',strtotime($request->start_due_date)))->where('orders.orderlast_date','<=',date('Y-m-d 23:59:59',strtotime($request->end_due_date)));
            }
            if($request->status != ''){
                $orders1->where('orders.status',$request->status);
            }
            $order = $orders1->orderby('orders.created_at','desc')->wherein('orders.status',['Confirm','Cancelled','Completed'])->get();
            if($request->export == 'download'){
                /*
                $columns = array('vporder_id','buyer_id','supplier_id','branch_id','brand_id','marka','transport_one_id','transport_two_id','supply_start_date',
                'orderlast_date','station','pkt_cases','remaining_pkt','order_amount','remaining_amount','supplier_accept','buyer_accept','veepee_invoice_number',
                'invoice','status','order_date','merchandiser_name');
                */
                
                $columns = array('Sr. No.','VPOrder Id','Buyer Acc No','Buyer/Merchandiser','Buyer Name','Supplier Acc No','Supplier Name','Branch Name','Brand Name','Marka','Order Date','Transport One Id','Transport Two Id','Supply Start Date',
                'Orderlast Date','Station','Pkt Cases','Remaining Pkt','Order Amount','Remaining Amount','Item','Supplier Accept','Buyer Accept','Veepee Invoice Number',
                'Invoice','Status','Reason','Remark','Remarked By','Remark Date','Merchandiser Name');
                //$order = $orders1->orderby('orders.created_at','desc')->get();
				
                //dd($order->toArray());
                //exit;
                $callback = function() use ($order, $columns) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);
                    $n=1;
                    $TRPCase   = null ;
                    foreach($order->unique('vporder_id') as $row) {
                        $items = DB::table('order_items')->select('name')->where('order_id',$row->id)->get();
                         if(isset($items)){
                             foreach($items as $itmrow){
                             $items=$itmrow->name." ";
                             }
                    }
                        $RPCase   = $row->pkt_cases - (delivered_cases($row->id) ?? 0);
                                    $TRPCase += $RPCase;
                        fputcsv($file, array(
                            $n,
                            $row->vporder_id,
                            @getUser($row->buyer_id)->veepeeuser_id,
                           @$row->merchandiser_name?? getUser($row->buyer_id)->name,
                             
                            @getUser($row->buyer_id)->name,
                            @getUser($row->supplier_id)->veepeeuser_id,
                            @getUser($row->supplier_id)->name,
                            @getBranch($row->branch_id),
                            @getBrand($row->brand_id),
                            $row->marka,
                            $row->order_date,
                            @getTransport($row->transport_one_id),
                            @getTransport($row->transport_two_id),
                            $row->supply_start_date,
                            $row->orderlast_date,
                            @$row->station,
                            $row->pkt_cases,
                            @$RPCase,
                            $row->order_amount,
                            ($row->order_amount - remaing_amount($row->id)),
                            @$items,
                                
                            ($row->supplier_accept == 0 || $row->supplier_accept == null ) ? 'Pending' : 'Accepted',
                            ($row->buyer_accept == 0 || $row->buyer_accept == null) ? 'Pending' : 'Accepted',
                            $row->veepee_invoice_number,
                            $row->invoice,
                            $row->status,
                            $row->reason,
                            $row->remark,
                            @getUser(ordercancelremark($row->id)->cancelled_by)->name ?? '',
                            $row->updated_at,
                            
                            @getUser($row->users_id)->name ?? ''
                        ));
                        $n++;
                    }
                    fclose($file);
              };
                return \Response::stream($callback, 200, $headers);
            }
            $orders = $orders1->where('orders.created_at','>=',date('Y-m-d 00:00:00',strtotime('-6 month')))->
                                where('orders.created_at','<=',date('Y-m-d 23:59:59'))->whereIn('orders.status',['Cancelled','Completed'])->
                                orderby('orders.created_at','DESC')->paginate(20);
                               // dd(\DB::getQueryLog());
            //$orders = $orders1->orderby('orders.created_at','desc')->wherein('orders.status',['Cancelled','Completed'])->paginate(20);
            
            return view('admin.orders.last_six_month_order', compact('orders','branches'));
        }else{
            //$orders = OrderModel::wherein('status',['Confirm','Cancelled','Completed'])->paginate(20);
            
            $orders =   OrderModel::select('orders.*' , 'brands.name as brand_name', 'order_items.name as item_name')
            //->join('orders', 'orders.supplier_id', '=', 'users.id')
            ->join('brands', 'brands.id', '=', 'orders.brand_id')
            ->join('order_items', 'order_items.order_id', '=', 'orders.id')
            // ->join('buyers', 'buyers.user_id', '=', 'orders.buyer_id')
            // ->join('states', 'states.id', '=', 'buyers.state_id')
            ->where('orders.created_at','>=',date('Y-m-d 00:00:00',strtotime('-6 month')))->
                                    where('orders.created_at','<=',date('Y-m-d 23:59:59'))->whereNull('orders.deleted_at')->whereIn('orders.status',['Cancelled','Completed'])->
                                    orderby('orders.created_at','desc')->paginate(20);
                //print_r( $orders);   die;                      
            return view('admin.orders.last_six_month_order', compact('orders','branches'));
        }
        
        
        /*
        $orders = OrderModel::where('orders.created_at','>=',date('Y-m-d 00:00:00',strtotime('-6 month')))->
                              where('orders.created_at','<=',date('Y-m-d 23:59:59'))->whereIn('status',['Cancelled','Completed'])->
                              orderby('created_at','desc')->paginate(20);
                              
        return view('admin.orders.last_six_month_order',compact('orders'));
        */
    }
    
    public function getbalance($vpid){
        $userr      =  User::where('id',$vpid)->first();
        $Samount    =  speneded_amount($vpid);
        $Oamount    =  ordered_amount($vpid);
        $data       =  busy($userr->veepeeuser_id);
        return json_encode(['amount'=>($data->amount-($Oamount-$Samount)),'days'=>$data->days,'ustatus'=>$userr->status,'bypass'=>getBuyers($userr->id)->bypass]);
    }

    public function getbuyercity($vpid){
        $result = DB::table('buyers')->join('cities', 'cities.id', '=', 'buyers.city_id')->where('buyers.user_id', '=', $vpid)->select(['cities.city_name'])->first();
        return json_encode(['data'=>$result]);
    }
    
    public function getlimit(Request $request){
       
        $userr      =  User::where('id',$request->id)->first();
         
        $Samount    =  speneded_amount($request->id);
        $Oamount    =  ordered_amount($request->id);
        $data       =  busy($userr->veepeeuser_id);
        
         
        $remaining=$data->amount-($Oamount-$Samount);
        if($remaining<50000 && getBuyers($userr->id)->bypass==1){
           return json_encode(['amount'=>$remaining,'days'=>$data->days,'ustatus'=>$userr->status,'Ordered'=>$Oamount,'Spent'=>$Samount,'BusyAmount'=>$data->amount,'bypass'=>0]); 
        }else{
        //return json_encode(['amount'=>($data->amount-($Oamount-$Samount)),'days'=>$data->days,'ustatus'=>$userr->status,'Ordered'=>$Oamount,'Spent'=>$Samount,'BusyAmount'=>$data->amount,'bypass'=>getBuyers($userr->id)->bypass]);
        //return json_encode(['amount'=>($data->amount-($Oamount-$Samount)),'days'=>$data->days,'ustatus'=>$userr->status,'Ordered'=>$Oamount,'Spent'=>$Samount,'BusyAmount'=>$data->amount,'bypass'=>getBuyers($userr->id)->bypass]);
        return json_encode(['amount'=>1000000,'days'=>5,'ustatus'=>$userr->status,'Ordered'=>$Oamount,'Spent'=>$Samount,'BusyAmount'=>100000,'bypass'=>getBuyers($userr->id)->bypass]);
        }
    }
    
    public function emailOrder($id){
        OrderNotification::dispatch($id);
        return json_encode(['status'=>true]);
    }
    
    public function smsOrder($id){
         
        OrderSmsNotification::dispatch($id);
        return json_encode(['status'=>true]);
    }
    
    public function download($type,$id,$file){
        $download = OrderImageDownload::where('order_delivery_id',$id)->first();
        if($download!=null){
            $download->$type = $download->$type+1;
        }else{
            $download                       = new OrderImageDownload();
            $download->order_delivery_id    = $id;
            $download->$type                = 1;
        }
        $download->save();
        return response()->download(public_path('images/order_request/'.$file));
    }
    
    public function gettest(){
         fcmnotifyAndroidforbiltydue();
    }

    public function testEmail(){

        
        
        try{
            //Mail::to("sotam1992@gmail.com")->cc("sotam1992@gmail.com")->bcc('development@veepeeonline.com')->send("test mail");
            $data = ["name"=>"sotam", "data"=>"Hello Sotam"];
            $user["to"] = "sotam1992@gmail.com";
            Mail::send('mail',$data,function($message) use($user){
                $message->to("sotam1992@gmail.com");
                $message->subject("Hello Sotam");
            });
            print_r("done");
        }
        catch(\Exception $e){
            echo $e->getMessage(); die;
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
                    //send_sms(8871025543, $msg);
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
                
                ///////

                $order  = OrderModel::where(['id'=> $request->order_id,'buyer_id'=> $request->buyer_id,'supplier_id'=> $request->supplier_id,'order_otp'=> $request->otp])->first();
                if($order){
                    $this->customer_accept_order($request->order_id);
                    $order->order_otp = null;
                    $order->supplier_status_flag = 0;
                    $order->status = "Confirm";
                    $order->save();
                    return response()->json(['status'=>true,'message'=> 'Otp verified'],200);
                }else{
                    return response()->json(['status'=>false,'message'=> 'Order otp mismatch'],200);
                }
            }
        }
        
        return response()->json(['status'=>true,'message'=> 'Success','result'=>$result],200);
         
     }

     public function add_delivery_new(Request $request){
        $validation_rule = Validator::make($request->all(), [
            'transport_bill' => 'required',
            'supplier_bill' => 'required',
            'supplier_invoice' => 'required',
        ]);

        //$validation_rule->validate();

        if ($validation_rule->fails()) {
            $firstErrorMessage = $validation_rule->errors()->first();
            $result['status'] = false;
            $result['message'] = $firstErrorMessage;
            return response()->json(['data' => $result]);
        }

        //return $request->all();
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
            //return $delivered_case;
            $validation_rule = Validator::make($request->all(), [
                'no_of_case'=>'lte:'.$remaining_case,
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
                    'supplier_invoice'  => $request->supplier_invoice,     
             );
            $delivery->update($data);
            $order->update(['status' =>$request->status ]);
            $result['status'] = true;
            $result['message'] = 'This order has been created successfully';
            return response()->json(['data' => $result]);

            } else {
                $result['status'] = false;
                $result['message'] = 'Somthing want wrong';
                return response()->json(['data' => $result]);
            }
        } else {
            $result['status'] = true;
            $result['message'] = 'All cases for this order has been dispatched successfully.So you are not able to create new delivery.';
            return response()->json(['data' => $result]);
        }
    }
    // public function saveImage($file,$destinationPath,$data){
    //     $filename = '';
    //     if($file != '' || NULL){
    //         $catalog = $file;
    //         $filename = $catalog->getClientOriginalName();
    //         //$filename = time().''.uniqid().'.'.$catalog->getClientOriginalExtension();
    //         $catalog->move($destinationPath, $filename);
    //     }
    //     return $filename;
    // }
    
    

}