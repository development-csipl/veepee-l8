<?php
    use App\Models\{CountryModel,CityModels,StatesModels,SizeModel,ColorModel,BranchModel,BrandModel,TransportsModels,StationModel};
    use App\Models\{SuppliersModels,BuyerModel,OrderModel,OrderTrackingModel,OrderDeliveryModel,OrderCancelModel,FcmModel,SiteInfoModel};
    use App\{User,UserRole,Role};
    use Carbon\Carbon;
    //use DB;
    use Illuminate\Support\Facades\DB;
    //use Mail;
    use Illuminate\Support\Facades\Mail;
    
    //use Auth;
    function processing_amount($user_id){
        $pamount = OrderModel::where('buyer_id',$user_id)->where('status','Processing')->get()->sum('order_amount');
        $orders = OrderModel::where('buyer_id',$user_id)->where('status','Processing')->get()->pluck('id')->toArray();
        $ramount = OrderDeliveryModel::where('status',1)->wherein('order_id',$orders)->get()->sum('price');
        return ($pamount-$ramount);
        //return OrderModel::where('buyer_id',$user_id)->where('status','Processing')->get()->sum('order_amount');
    }
    function speneded_amount($user_id){
        $orders = OrderModel::where('buyer_id',$user_id)->where('status','Confirm')->get()->pluck('id')->toArray();
        return OrderDeliveryModel::where('status',1)->wherein('order_id',$orders)->get()->sum('price');
        //OrderDeliveryModel::where('status',1)->wherein('order_id',$orders)->where('veepee_invoice_number','!=',NULL)->get()->sum('price');
    }
    
    // function ordered_amount($user_id){
    //     return OrderModel::where('buyer_id',$user_id)->where('status','Confirm')->get()->sum('order_amount');
    // }

    function ordered_amount($user_id){
        return OrderModel::where('buyer_id',$user_id)->whereIn('status',['Confirm','New'])->get()->sum('order_amount');
    }

    function ordered_amount_supplier_accept($user_id){
        return OrderModel::where('buyer_id',$user_id)->where('status','Confirm')->get()->sum('order_amount');
    }
    
    function lastsixmonth($user_id){
         $orders = OrderModel::where('supplier_id',$user_id)->where('orders.created_at','>=',date('Y-m-d 00:00:00',strtotime('-6 month')))->where('orders.created_at','<=',date('Y-m-d 23:59:59'))->whereIn('status',['Cancelled','Completed'])->count();
         return $orders;
            
    }
    
    function ordercancelremark($id){
        return OrderCancelModel::where('order_id',$id)->first();
    }
    
    function countcheckdocument(){
       //return OrderDeliveryModel::where('veepee_invoice_number',NULL)->where('status',NULL)->count();
       $user       =   Auth::user();
       $role       =  getRole($user->id);
        if($user->branch_id == 1){
            return $delivery   =   OrderDeliveryModel::with(['downloads'])->where('veepee_invoice_number',NULL)->where('status',NULL)->orderby('created_at','desc')->count();
        }elseif($user->user_type == "supplier" || $role=="Branch Operator"){
            return $delivery   =   OrderDeliveryModel::select('order_delivery.*')
                ->Join('orders', 'order_delivery.order_id', '=', 'orders.id')
                ->where('orders.branch_id',$user->branch_id)
                ->where('orders.supplier_id',$user->id)
                ->where('order_delivery.veepee_invoice_number',NULL)
                ->where('orders.status','Processing')
                ->where(function ($query) {
                                        $query->where('order_delivery.status', '!=' , 0)
                                        ->orWhere('order_delivery.status', NULL);
                                    })
                ->orderby('order_delivery.created_at','desc')
                ->count();
        }else{
            return $delivery   =   OrderDeliveryModel::select('order_delivery.*')->Join('orders', 'order_delivery.order_id', '=', 'orders.id')->where('orders.branch_id',$user->branch_id)->where('order_delivery.veepee_invoice_number',NULL)->where('order_delivery.status',NULL)->orderby('order_delivery.created_at','desc')->count();
        }
    }

    function countProcessingorder($created_by){
        return OrderModel::where('veepee_invoice_number',NULL)->where('status','Processing')->orderby('created_at','desc')->count();
    }

    function countAllStatusOrderBranchOperator($branch_id){
        // echo "<pre>";
        // print_r($branch_id); die;
        if($branch_id == 26){
            return OrderModel::whereNotIn('status', ['Rejected'])->count();
        }else{
            return OrderModel::where('branch_id',$branch_id)->whereIn('status',['Confirm','Processing'])->count();
        }
        
    }

    function countAllStatusOrderSupplierOperator($supplier_id){
        // echo "<pre>";
        // print_r($supplier_id); die;
        return OrderModel::where('supplier_id',$supplier_id)->whereIn('status',['Confirm','Processing'])->count();
    }
    
    function vp_order_id($order_id){
        return (OrderModel::where('id',$order_id)->first())->vporder_id;
    }
    
    function cancel_order_rating($user_id){
        return [
           'total'=>OrderModel::where('supplier_id',$user_id)->whereMonth('order_date', '=',Carbon::now()->subMonth()->month)->count(),
           'cancel'=>OrderModel::where('supplier_id',$user_id)->where('supplier_accept',0)->whereMonth('order_date', '=',Carbon::now()->subMonth()->month)->count()
       ];
    }
    
    function cancel_rating($user_id){
       $cancel = OrderModel::where('supplier_id',$user_id)->where('supplier_accept',0)->whereBetween('order_date', [date("Y-m-1",strtotime("-1 month")), date("Y-m-t",strtotime("-1 month"))])->withTrashed()->count();
       $total  = OrderModel::where('supplier_id',$user_id)->whereBetween('order_date', [date("Y-m-1",strtotime("-1 month")), date("Y-m-t",strtotime("-1 month"))])->withTrashed()->count();
       return ($cancel === 0) ? 0 : ($cancel*100)/$total;
    }
    
    function branches(){
       return BranchModel::where('status',1)->get();
    }
    
    function delivered_cases($id){
       return OrderDeliveryModel::where('order_id',$id)->where('no_of_case','!=',NULL)->where('status',1)->sum('no_of_case');
    }

    function delivered_cases_sum($id){
        //return OrderDeliveryModel::where('order_id',$id)->where('no_of_case','!=',NULL)->where('status',1)->sum('no_of_case');
        return OrderDeliveryModel::where('order_id',$id)
        ->where('no_of_case','!=',NULL)
        ->where('supplier_invoice','!=',NULL)
        ->where('price', '!=',0)
        ->where(function ($q){
         $q->whereNull('status')
         ->orWhere('status', 1);        
      })
        ->sum('no_of_case');
     }
     function delivered_cases_checkbox($id){
        //return OrderDeliveryModel::where('order_id',$id)->where('no_of_case','!=',NULL)->where('status',1)->sum('no_of_case');
        /*return OrderDeliveryModel::where('order_id',$id)
        ->where('no_of_case','!=',NULL)
        //->where('supplier_invoice',NULL)
        //->where('status',1)
        ->where(function ($q){
         $q->whereNull('status')
         ->orWhere('status', 1);        
      })
        ->sum('no_of_case');*/


        $noOfCase = OrderDeliveryModel::where('order_id',$id)
            ->where('no_of_case','!=',NULL)
            //->where('supplier_invoice',NULL)
            //->where('status',1)
            ->where(function ($q){
                $q->whereNull('status')
                ->orWhere('status', 1);        
            })
            ->sum('no_of_case');
        
        $noCase = OrderDeliveryModel::where('order_id',$id)
            ->where('no_of_case','=',NULL)
            ->where(function ($q){
                $q->whereNull('status')
                ->orWhere('status', 1);        
            })->count();

        if(!is_null($noCase)){
            //return $noOfCase + 1;
            return $noOfCase + $noCase;
            //return $noOfCase=1;
        }
        else{
            return $noOfCase;
        }
        
        
     }
    
    function remaing_amount($id){
       return OrderDeliveryModel::where('order_id',$id)->where('price','!=',NULL)->where('status',1)->get()->sum('price');
    }
    
    function countcourier($user_id){
        return  OrderDeliveryModel::where('veepee_invoice_number','!=',NULL)->where('status',1)->where('dispatch',0)->where('created_by',$user_id)->orderby('created_at','desc')->count();
    }
    
    function countcompletebill(){
        return OrderDeliveryModel::where('status',1)->where('veepee_invoice_number','!=',NULL)->orwhere('status',0)->count();
    }
    
    function getinvoiceid($order_id){
       return OrderDeliveryModel::where('order_id',$order_id)->orderBy('id', 'DESC')->first();
    }
    
    function countpendinginvoice($created_by){
        return OrderDeliveryModel::where('veepee_invoice_number',NULL)->where('created_by',$created_by)->where('status','!=',0)->orderby('created_at','desc')->count();
    }

    function rejectedorder($user_id){
        $role       =  getRole($user_id);
        if($role == "Head Office Operator"){
            return OrderDeliveryModel::where('veepee_invoice_number',NULL)->where('status',0)->where('dispatch',0)->orderby('created_at','desc')->count();
        }else{
            return OrderDeliveryModel::where('veepee_invoice_number',NULL)->where('created_by',$user_id)->where('status',0)->where('dispatch',0)->orderby('created_at','desc')->count();
        }
        
    }
    
    function countveepeepending(){
        return OrderDeliveryModel::where('veepee_invoice_number',NULL)->where('status',1)->where('dispatch',0)->count();
    }
    
    function getCountry($id){
        return CountryModel::where('id',$id)->first();
    }

    function getState($id){
        return StatesModels::where('id',$id)->first();
    }
    
    function getCity($id){
        return CityModels::where('id',$id)->first();
    }
    
    function getCities($stateid){
        return CityModels::where('state_id',$stateid)->get();
    }
    
    function getColor($id){
        return  (ColorModel::where('id',$id)->first())->name;
    }
    
    function getSize($id){
        return (SizeModel::where('id',$id)->first())->name;
    }
    
    function getBranch($id){
        if(!$id){
            return 'Undefined';
        }
        return (BranchModel::where('id',$id)->first())->name;
    }
    
    function getBranchs($id){
        return (CityModels::where('id',$id)->first())->city_name;
    }
    
    function getBrand($id){
        if(!$id){
            return 'Undefined';
        }
        return (BrandModel::where('id',$id)->first())->name;
    }
    
    function getTransport($id){
        return (TransportsModels::where('id',$id)->first('transport_name'))->transport_name;
    }
    
    function getTransportDetail($id){
        return TransportsModels::where('id',$id)->first();
    }
    
    function getAllTransport($id){
        return TransportsModels::where('id',$id)->first(['id','transport_name','gst','contact_person','contact_mobile','address']);
    }
    
    function getAddress($id){
        return TransportsModels::where('id',$id)->first(['id','transport_name','gst','contact_person','contact_mobile','address']);
    }
    
    function getUser($id){
        return User::where('id',$id)->first();
    }
    
    function getSuplliers($id){
        return SuppliersModels::where('user_id',$id)->first();
    }
    
    function getBuyers($id){
        return BuyerModel::where('user_id',$id)->first();
    }
    
    function getRole($id){
        return @(Role::where('id',@(UserRole::where('user_id',$id)->first())->role_id)->first())->title;
    }
    
    function checkOrderStatus($order_id){
        $order      = OrderModel::where('id',$order_id)->first();
        $delivery   = OrderDeliveryModel::where('order_id',$order_id)->where('status',1)->get();
        
        if(@$delivery->sum('no_of_case') === $order->pkt_cases){
            return true;
        }else{
            return false;
        }
    }
    
    function checkOrderAccept($order_id){
        $order = OrderModel::where('id',$order_id)->where('supplier_accept',1)->orWhere('buyer_accept',1)->first();
        if(@$order){
            return true;
        } else {
            return false;
        }
    }
    
    function getdays(){
        return array("Sunday","Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
    }
    
    function getSiteData(){
        return $sitedata = SiteInfoModel::first();
    }
    
    /*function send_sms($mobile,$msg){
        //$mobile     = 9999144750;
        $curl       = curl_init();
        $authKey    = "241007Ap1vkI6T5q5bb5ca5b";
        $senderId   = "BSYJKB";
        $message    = urlencode($msg);
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.msg91.com/api/sendhttp.php?authkey=241007Ap1vkol6T5q5bb5ca5b&mobiles='.$mobile.'&country=91&message='.$message.'&sender=VEEPIN&route=4',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Cookie: PHPSESSID=53n322gr4gkdg5mrp234q2jvd3'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
    }*/
    
    function send_sms($mobile,$msg,$dlt=""){
        //$mobile     = 9999144750;
        $curl       = curl_init();
        $authKey    = "241007Ap1vkI6T5q5bb5ca5b";
        $senderId   = "BSYJKB";
        $message    = urlencode($msg);
        if($dlt!=''){
            $urls='https://api.msg91.com/api/sendhttp.php?authkey=241007Ap1vkol6T5q5bb5ca5b&mobiles='.$mobile.'&country=91&message='.$message.'&sender=VEEPIN&route=4&DLT_TE_ID='.$dlt.'';
            
        }else{
            $urls='https://api.msg91.com/api/sendhttp.php?authkey=241007Ap1vkol6T5q5bb5ca5b&mobiles='.$mobile.'&country=91&message='.$message.'&sender=VEEPIN&route=4';
        }
        curl_setopt_array($curl, array(
         
          CURLOPT_URL => $urls,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Cookie: PHPSESSID=53n322gr4gkdg5mrp234q2jvd3'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        
    }
    
    function busy_bac($vpid){

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        
        /********************************1st API*******************************/

        $query      =   "Select Code from Master1 where Alias='{$vpid}'";
        $response1  =   run_curl($query);
        $obj1       =   mungXML($response1);
        $count=0;
       if(!isset($obj1->rs_data)){ 
           if($count==0){
            
            $bgst=  User::where('status',1)->orderby('id','desc')->first('bg_flag');
            $bgstatus = $bgst['bg_flag'];
            if($bgstatus!=1){
        $bmsg = 'APP IS DISCONNECTED FROM BUSY OR SERVER IS NOT WORKING. CHECK IT ASAP AND INFORM IT DEPT OR MD SIR ON URGENT BASIS. VEEPEE APP SMS.';
        $bdlt='1207165147079382121';
        $smsno='7392929292';
        //$smsno='8448983473';
        send_sms($smsno,$bmsg,$bdlt);
        $data = array('');
            $uemail='development@veepeeonline.com';
             Mail::send('emails.bg_email',$data, function ($message) use($uemail) {
                $message->to($uemail)->subject('Busi Server Down');
            });
           }
           $res    =  User::where(['status'=> 1])->update(['bg_flag' => 1]);
            $count++;
           }
       }else{
          $res    =  User::where(['status'=> 1])->update(['bg_flag' => 0]); 
       }
        $code       =   $obj1->rs_data->z_row->attributes()->Code;              
        
        /********************************2nd API*******************************/
        
        $query      =   "Select  D1 from Folio1 where MasterCode={$code}";
        $response2  =   run_curl($query);
        $obj2       =   mungXML($response2);                                     
        if(count($obj2->rs_data->z_row)>0){ 
        
        $Folio1     =   $obj2->rs_data->z_row->attributes()->D1->__toString();
        }else{
           @$Folio1     =0; 
        }  
        
        /********************************3rd API*******************************/
 $query      =   "Select Value1 from Tran2 where MasterCode1={$code} And RecType=1";
        //$query      =   "Select Value1 from Tran2 where MasterCode1={$code}";
        $response3  =   run_curl($query);
        $obj3       =   mungXML($response3);
        
        $Tran2      =   0;
        foreach($obj3->rs_data->z_row as $rows){
            $Tran2 = $Tran2+ $rows->attributes()->Value1->__toString();
        }
        
        /********************************4th API*******************************/
        
        $query      =   "SELECT D1 FROM Master1 where Alias='{$vpid}'";
        $response4  =   run_curl($query);
        $obj4       =   mungXML($response4);
        $Master1    =   $obj4->rs_data->z_row->attributes()->D1->__toString();
        
        //dd(['D1'=>$value1,'Value1'=>$value2, 'D1-2'=>$value3]);
        
        $amount     =   $Master1+$Tran2+$Folio1;
        
        /********************************5th API*******************************/
        $DArr       =   [];
        $query      =   "SELECT Date,(Select Sum(Value1) from tran3 as t3 where t3.refcode = tran3.refcode and t3.date<= '".date('Y-m-d')."' AND T3.ApprovalStatus <> 2) as bal FROM TRAN3 WHERE Tran3.RECTYPE = 1  AND Tran3.METHOD = 1 AND Tran3.MASTERCODE1 = '".$code."' AND Tran3.MASTERCODE2 = 0 AND Tran3.DATE between '2012-01-13' AND '".date('Y-m-d')."' AND Tran3.Value1<0 AND TRAN3.ApprovalStatus <> 2 ORDER BY Tran3.Date";
        //$query    =   "Select Date from Tran3 where MasterCode1='{$code}' AND Date <= '".date('Y-m-d', strtotime('-90 days'))."'";
        $response5  =   run_curl($query);
        $obj5       =   mungXML($response5);
        
        foreach($obj5->rs_data->z_row as $key=>$rows){
            $balance    =   $rows->attributes()->bal->__toString();
            if($balance < 0 && !preg_match("/[a-z]/i",$balance)){
                $DArr[] =  date('Y-m-d', strtotime($rows->attributes()->Date->__toString()));
            }
        }
        return (object)['days'=>days($DArr),'amount'=>$amount];
    }

    function busy($vpid){

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        
        /********************************1st API*******************************/

        $query      =   "Select Code from Master1 where Alias='{$vpid}'";
        $response1  =   run_curl($query);
        $obj1       =   mungXML($response1);
        $count=0;
       if(!isset($obj1->rs_data)){ 
           if($count==0){
            
            $bgst=  User::where('status',1)->orderby('id','desc')->first('bg_flag');
            $bgstatus = $bgst['bg_flag'];
            if($bgstatus!=1){
        $bmsg = 'APP IS DISCONNECTED FROM BUSY OR SERVER IS NOT WORKING. CHECK IT ASAP AND INFORM IT DEPT OR MD SIR ON URGENT BASIS. VEEPEE APP SMS.';
        $bdlt='1207165147079382121';
        $smsno='7392929292';
        //$smsno='8448983473';
        send_sms($smsno,$bmsg,$bdlt);
        $data = array('');
            $uemail='development@veepeeonline.com';
             Mail::send('emails.bg_email',$data, function ($message) use($uemail) {
                $message->to($uemail)->subject('Busi Server Down');
            });
           }
           $res    =  User::where(['status'=> 1])->update(['bg_flag' => 1]);
            $count++;
           }
       }else{
          $res    =  User::where(['status'=> 1])->update(['bg_flag' => 0]); 
       }
        $code       =   $obj1->rs_data->z_row->attributes()->Code;              
        
        /********************************2nd API*******************************/
        
        $query      =   "Select  D1 from Folio1 where MasterCode={$code}";
        $response2  =   run_curl($query);
        $obj2       =   mungXML($response2);                                     
        if(count($obj2->rs_data->z_row)>0){ 
        
        $Folio1     =   $obj2->rs_data->z_row->attributes()->D1->__toString();
        }else{
           @$Folio1     =0; 
        }  
        
        /********************************3rd API*******************************/
 $query      =   "Select Value1 from Tran2 where MasterCode1={$code} And RecType=1";
        //$query      =   "Select Value1 from Tran2 where MasterCode1={$code}";
        $response3  =   run_curl($query);
        $obj3       =   mungXML($response3);
        
        $Tran2      =   0;
        foreach($obj3->rs_data->z_row as $rows){
            $Tran2 = $Tran2+ $rows->attributes()->Value1->__toString();
        }
        
        /********************************4th API*******************************/
        
        $query      =   "SELECT D1 FROM Master1 where Alias='{$vpid}'";
        $response4  =   run_curl($query);
        $obj4       =   mungXML($response4);
        $Master1    =   $obj4->rs_data->z_row->attributes()->D1->__toString();
        
        //dd(['D1'=>$value1,'Value1'=>$value2, 'D1-2'=>$value3]);
        
        $amount     =   $Master1+$Tran2+$Folio1;
        
        /********************************5th API*******************************/
        $DArr       =   [];
        $query      =   "SELECT Date,(Select Sum(Value1) from tran3 as t3 where t3.refcode = tran3.refcode and t3.date<= '".date('Y-m-d')."' AND T3.ApprovalStatus <> 2) as bal FROM TRAN3 WHERE Tran3.RECTYPE = 1  AND Tran3.METHOD = 1 AND Tran3.MASTERCODE1 = '".$code."' AND Tran3.MASTERCODE2 = 0 AND Tran3.DATE between '2012-01-13' AND '".date('Y-m-d')."' AND Tran3.Value1<0 AND TRAN3.ApprovalStatus <> 2 ORDER BY Tran3.Date";
        //$query    =   "Select Date from Tran3 where MasterCode1='{$code}' AND Date <= '".date('Y-m-d', strtotime('-90 days'))."'";
        $response5  =   run_curl($query);
        $obj5       =   mungXML($response5);
        
        foreach($obj5->rs_data->z_row as $key=>$rows){
            $balance    =   $rows->attributes()->bal->__toString();
            if($balance < 0 && !preg_match("/[a-z]/i",$balance)){
                $DArr[] =  date('Y-m-d', strtotime($rows->attributes()->Date->__toString()));
            }
        }
        return (object)['days'=>days($DArr),'amount'=>$amount];
    }
    function busy2($vpid,$startdate,$enddate){

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        
        /********************************1st API*******************************/

        $query      =   "Select Code from Master1 where Alias='{$vpid}'";
        $response1  =   run_curl($query);
          $obj1       =   mungXML($response1);                                    
        $code       =   $obj1->rs_data->z_row->attributes()->Code;              
        
        /********************************2nd API*******************************/
        
        $query      =   "Select  D1 from Folio1 where MasterCode={$code}";
        $response2  =   run_curl($query);
        $obj2       =   mungXML($response2);                                     
        $Folio1     =   $obj2->rs_data->z_row->attributes()->D1->__toString();  
        
        /********************************3rd API*******************************/

        $query      =   "Select Value1 from Tran2 where MasterCode1={$code}";
        $response3  =   run_curl($query);
        $obj3       =   mungXML($response3);
        
        $Tran2      =   0;
        foreach($obj3->rs_data->z_row as $rows){
            $Tran2 = $Tran2+ $rows->attributes()->Value1->__toString();
        }
        
        /********************************4th API*******************************/
        
        $query      =   "SELECT D1 FROM Master1 where Alias='{$vpid}'";
        $response4  =   run_curl($query);
        $obj4       =   mungXML($response4);
        $Master1    =   $obj4->rs_data->z_row->attributes()->D1->__toString();
        
        //dd(['D1'=>$value1,'Value1'=>$value2, 'D1-2'=>$value3]);
        
        $amount     =   $Master1+$Tran2+$Folio1;
        
        /********************************5th API*******************************/
        $users =  User::where('veepeeuser_id',$vpid)->first();
        $userType=$users->user_type;
       
        $DArr       =   [];
        /*$query      =   "SELECT Date,(Select Sum(Value1) from tran3 as t3 where t3.refcode = tran3.refcode and t3.date<= '".date('Y-m-d')."' AND T3.ApprovalStatus <> 2) as bal FROM TRAN3 WHERE Tran3.RECTYPE = 1  AND Tran3.METHOD = 1 AND Tran3.MASTERCODE1 = '".$code."' AND Tran3.MASTERCODE2 = 0 AND Tran3.DATE between '2012-01-13' AND '".date('Y-m-d')."' AND Tran3.Value1<0 AND TRAN3.ApprovalStatus <> 2 ORDER BY Tran3.Date";*/
        //AND Tran3.Value1<0 for receivable and
        $queryforsum      =   "SELECT Date,No,(Select abs(Sum(Value1)) from tran3 as t3 where t3.refcode = tran3.refcode and t3.date<= '".date('Y-m-d')."' AND T3.ApprovalStatus <> 2) as bal FROM TRAN3 WHERE Tran3.RECTYPE = 1    AND  Tran3.METHOD = 1  AND Tran3.MASTERCODE2 = 0 AND Tran3.Value1>0 AND  Tran3.MASTERCODE1 = '".$code."'    ORDER BY Tran3.Date"; 
        $response4  =   run_curl($queryforsum);
        $obj4      =   mungXML($response4);
        
        $sum = 0;
foreach($obj4->rs_data->z_row as $key=>$value){
    $balances=$value->attributes()->bal->__toString();
   if(number_format($balances, 2)!=0){
     $sum += $value->attributes()->bal->__toString();
   }
}
 
         
        if($userType=='buyer'){
         $query      =   "SELECT Date,No,(Select abs(Sum(Value1)) from tran3 as t3 where t3.refcode = tran3.refcode and t3.date<= '".date('Y-m-d')."' AND T3.ApprovalStatus <> 2) as bal FROM TRAN3 WHERE Tran3.RECTYPE = 1   AND  Tran3.DATE between '".$startdate."' AND '".$enddate."' AND  Tran3.METHOD = 1  AND Tran3.MASTERCODE2 = 0 AND Tran3.Value1<0 AND  Tran3.MASTERCODE1 = '".$code."'    ORDER BY Tran3.Date"; 
        }else{
         //AND Tran3.Value1<0 for Payble and 
         $query      =   "SELECT Date,No,(Select abs(Sum(Value1)) from tran3 as t3 where t3.refcode = tran3.refcode and t3.date<= '".date('Y-m-d')."' AND T3.ApprovalStatus <> 2) as bal FROM TRAN3 WHERE Tran3.RECTYPE = 1   AND  Tran3.DATE between '".$startdate."' AND '".$enddate."' AND  Tran3.METHOD = 1  AND Tran3.MASTERCODE2 = 0 AND Tran3.Value1>0 AND  Tran3.MASTERCODE1 = '".$code."'    ORDER BY Tran3.Date";
        }
        
          
        
         
        //$query    =   "Select Date from Tran3 where MasterCode1='{$code}' AND Date <= '".date('Y-m-d', strtotime('-90 days'))."'";
        $response5  =   run_curl($query);
        $obj5       =   mungXML($response5);
        
           $result=[];
        foreach($obj5->rs_data->z_row as $key=>$rows){
             
            $datas[]=$rows->attributes();
            $balance    =   $rows->attributes()->bal->__toString();
            $ref_No    =   $rows->attributes()->No->__toString();
            $dates=date('d-m-Y', strtotime($rows->attributes()->Date->__toString()));
            $now = time(); // or your date as well
$your_date = strtotime($dates);
$datediff = ceil(($now - $your_date)/86400);
if(intval($datediff)>1){
  $datediff = intval($datediff)-1;
}
           
            if($balance < 0 && !preg_match("/[a-z]/i",$balance)){
                //$DArr[] =  date('d-m-Y', strtotime($rows->attributes()->Date->__toString()));
                 
            }
             if(number_format($balance, 2)!=0){
            if($userType=='buyer'){
                
            $result[]=array('bill_receivable_date' => $dates,'days' => $datediff,'pending_amount' => number_format($balance, 2), 'ref_no'=> trim($ref_No),'total_pending_amount' =>0);
            }else{
                if(number_format($sum, 2)>0){
                    $num=number_format($sum, 2).' CR';
                }else{
                   $num= number_format($sum, 2).' DR';
                }
             $result[]=array('bill_payble_date' => $dates,'days' => '','pending_amount' => number_format($balance, 2), 'ref_no'=> trim($ref_No),'total_pending_amount' => $num);   
            }
             } 
              
            }   
        
        //return (object)['days'=>$datas,'amount'=>$amount];
        //return $data;
         
         return $result;
    }
    
    
    function busy3($vpid){

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        
        /********************************1st API*******************************/

        $query      =   "Select Code from Master1 where Alias='{$vpid}'";
        $response1  =   run_curl($query);
          $obj1       =   mungXML($response1);                                    
        $code       =   $obj1->rs_data->z_row->attributes()->Code;              
        
        /********************************2nd API*******************************/
        
        $query      =   "Select  D1 from Folio1 where MasterCode={$code}";
        $response2  =   run_curl($query);
        $obj2       =   mungXML($response2);                                     
        $Folio1     =   $obj2->rs_data->z_row->attributes()->D1->__toString();  
        
        /********************************3rd API*******************************/
 //$query      =   "Select Value1 from Tran2 where MasterCode1={$code} And RecType=1";
        $query      =   "Select Value1 from Tran2 where MasterCode1={$code}";
        $response3  =   run_curl($query);
        $obj3       =   mungXML($response3);
        
        $Tran2      =   0;
        foreach($obj3->rs_data->z_row as $rows){
            $Tran2 = $Tran2+ $rows->attributes()->Value1->__toString();
        }
        
        /********************************4th API*******************************/
        
        $query      =   "SELECT D1 FROM Master1 where Alias='{$vpid}'";
        $response4  =   run_curl($query);
        $obj4       =   mungXML($response4);
        $Master1    =   $obj4->rs_data->z_row->attributes()->D1->__toString();
        
        //dd(['D1'=>$value1,'Value1'=>$value2, 'D1-2'=>$value3]);
        
        $amount     =   $Master1+$Tran2+$Folio1;
        
        /********************************5th API*******************************/
        $users =  User::where('veepeeuser_id',$vpid)->first();
        $userType=$users->user_type;
       
        $DArr       =   [];
        /*$query      =   "SELECT Date,(Select Sum(Value1) from tran3 as t3 where t3.refcode = tran3.refcode and t3.date<= '".date('Y-m-d')."' AND T3.ApprovalStatus <> 2) as bal FROM TRAN3 WHERE Tran3.RECTYPE = 1  AND Tran3.METHOD = 1 AND Tran3.MASTERCODE1 = '".$code."' AND Tran3.MASTERCODE2 = 0 AND Tran3.DATE between '2012-01-13' AND '".date('Y-m-d')."' AND Tran3.Value1<0 AND TRAN3.ApprovalStatus <> 2 ORDER BY Tran3.Date";*/
        //AND Tran3.Value1<0 for receivable and
        $queryforsum      =   "SELECT Date,No,(Select abs(Sum(Value1)) from tran3 as t3 where t3.refcode = tran3.refcode and t3.date<= '".date('Y-m-d')."' AND T3.ApprovalStatus <> 2) as bal FROM TRAN3 WHERE Tran3.RECTYPE = 1    AND  Tran3.METHOD = 1  AND Tran3.MASTERCODE2 = 0 AND Tran3.Value1>0 AND  Tran3.MASTERCODE1 = '".$code."'    ORDER BY Tran3.Date"; 
        $response4  =   run_curl($queryforsum);
        $obj4      =   mungXML($response4);
        
        $sum = 0;
foreach($obj4->rs_data->z_row as $key=>$value){
    $balances=$value->attributes()->bal->__toString();
   if(number_format($balances, 2)!=0){
     $sum += $value->attributes()->bal->__toString();
   }
}
 
         
        if($userType=='buyer'){
         $query      =   "SELECT Date,No,(Select abs(Sum(Value1)) from tran3 as t3 where t3.refcode = tran3.refcode and t3.date<= '".date('Y-m-d')."' AND T3.ApprovalStatus <> 2) as bal FROM TRAN3 WHERE Tran3.RECTYPE = 1    AND  Tran3.METHOD = 1  AND Tran3.MASTERCODE2 = 0 AND Tran3.Value1<0 AND  Tran3.MASTERCODE1 = '".$code."'    ORDER BY Tran3.Date"; 
        }else{
         //AND Tran3.Value1<0 for Payble and 
         $query      =   "SELECT Date,No,(Select abs(Sum(Value1)) from tran3 as t3 where t3.refcode = tran3.refcode and t3.date<= '".date('Y-m-d')."' AND T3.ApprovalStatus <> 2) as bal FROM TRAN3 WHERE Tran3.RECTYPE = 1   AND  Tran3.METHOD = 1  AND Tran3.MASTERCODE2 = 0 AND Tran3.Value1>0 AND  Tran3.MASTERCODE1 = '".$code."'    ORDER BY Tran3.Date";
        }
        
           
        
         
        //$query    =   "Select Date from Tran3 where MasterCode1='{$code}' AND Date <= '".date('Y-m-d', strtotime('-90 days'))."'";
        $response5  =   run_curl($query);
        $obj5       =   mungXML($response5);
           $result=[];
          
        foreach($obj5->rs_data->z_row as $key=>$rows){
             
            $datas[]=$rows->attributes();
            $balance    =   $rows->attributes()->bal->__toString();
            $ref_No    =   $rows->attributes()->No->__toString();
            $dates=date('d-m-Y', strtotime($rows->attributes()->Date->__toString()));
            $now = time(); // or your date as well
$your_date = strtotime($dates);
$datediff = ceil(($now - $your_date)/86400);
if(intval($datediff)>1){
  $datediff = intval($datediff)-1;
}
           
            if($balance < 0 && !preg_match("/[a-z]/i",$balance)){
                //$DArr[] =  date('d-m-Y', strtotime($rows->attributes()->Date->__toString()));
                 
            }
            
             if(number_format($balance, 2)!=0){
             
               
                if(number_format($sum, 2)>0){
                    $num=number_format($sum, 2).' CR';
                }else{
                   $num= number_format($sum, 2).' DR';
                }
             $result[]=array('total_pending_amount' => $num);   
             
            
                 
            
             }  
              
            }   
        
        //return (object)['days'=>$datas,'amount'=>$amount];
        //return $data;
         
         return $result;
    }
function busy4($vpid){

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        
        /********************************1st API*******************************/

        $query      =   "Select Code from Master1 where Alias='{$vpid}'";
        $response1  =   run_curl($query);
          $obj1       =   mungXML($response1);                                    
        $code       =   $obj1->rs_data->z_row->attributes()->Code;              
        
        /********************************2nd API*******************************/
        
        $query      =   "Select  D1 from Folio1 where MasterCode={$code}";
        $response2  =   run_curl($query);
        $obj2       =   mungXML($response2);                                     
        $Folio1     =   $obj2->rs_data->z_row->attributes()->D1->__toString();  
        
        /********************************3rd API*******************************/

        $query      =   "Select Value1 from Tran2 where MasterCode1={$code}";
        $response3  =   run_curl($query);
        $obj3       =   mungXML($response3);
        
        $Tran2      =   0;
        foreach($obj3->rs_data->z_row as $rows){
            $Tran2 = $Tran2+ $rows->attributes()->Value1->__toString();
        }
        
        /********************************4th API*******************************/
        
        $query      =   "SELECT D1 FROM Master1 where Alias='{$vpid}'";
        $response4  =   run_curl($query);
        $obj4       =   mungXML($response4);
        $Master1    =   $obj4->rs_data->z_row->attributes()->D1->__toString();
        
        //dd(['D1'=>$value1,'Value1'=>$value2, 'D1-2'=>$value3]);
        
        $amount     =   $Master1+$Tran2+$Folio1;
        
        /********************************5th API*******************************/
        $users =  User::where('veepeeuser_id',$vpid)->first();
        $userType=$users->user_type;
       
        $DArr       =   [];
        /*$query      =   "SELECT Date,(Select Sum(Value1) from tran3 as t3 where t3.refcode = tran3.refcode and t3.date<= '".date('Y-m-d')."' AND T3.ApprovalStatus <> 2) as bal FROM TRAN3 WHERE Tran3.RECTYPE = 1  AND Tran3.METHOD = 1 AND Tran3.MASTERCODE1 = '".$code."' AND Tran3.MASTERCODE2 = 0 AND Tran3.DATE between '2012-01-13' AND '".date('Y-m-d')."' AND Tran3.Value1<0 AND TRAN3.ApprovalStatus <> 2 ORDER BY Tran3.Date";*/
        //AND Tran3.Value1<0 for receivable and
        $queryforsum      =   "SELECT Date,No,(Select abs(Sum(Value1)) from tran3 as t3 where t3.refcode = tran3.refcode and t3.date<= '".date('Y-m-d')."' AND T3.ApprovalStatus <> 2) as bal FROM TRAN3 WHERE Tran3.RECTYPE = 1    AND  Tran3.METHOD = 1  AND Tran3.MASTERCODE2 = 0 AND Tran3.Value1>0 AND  Tran3.MASTERCODE1 = '".$code."'    ORDER BY Tran3.Date"; 
        $response4  =   run_curl($queryforsum);
        $obj4      =   mungXML($response4);
        
        $sum = 0;
        foreach($obj4->rs_data->z_row as $key=>$value){
            $balances=$value->attributes()->bal->__toString();
        if(number_format($balances, 2)!=0){
            $sum += $value->attributes()->bal->__toString();
        }
        }
 
         
        if($userType=='buyer'){
         $query      =   "SELECT Date,No,(Select abs(Sum(Value1)) from tran3 as t3 where t3.refcode = tran3.refcode and t3.date<= '".date('Y-m-d')."' AND T3.ApprovalStatus <> 2) as bal FROM TRAN3 WHERE Tran3.RECTYPE = 1    AND  Tran3.METHOD = 1  AND Tran3.MASTERCODE2 = 0 AND Tran3.Value1<0 AND  Tran3.MASTERCODE1 = '".$code."'    ORDER BY Tran3.Date"; 
        }else{
         //AND Tran3.Value1<0 for Payble and 
         $query      =   "SELECT Date,No,(Select abs(Sum(Value1)) from tran3 as t3 where t3.refcode = tran3.refcode and t3.date<= '".date('Y-m-d')."' AND T3.ApprovalStatus <> 2) as bal FROM TRAN3 WHERE Tran3.RECTYPE = 1   AND  Tran3.METHOD = 1  AND Tran3.MASTERCODE2 = 0 AND Tran3.Value1>0 AND  Tran3.MASTERCODE1 = '".$code."'    ORDER BY Tran3.Date";
        }
        
          
        
         
        //$query    =   "Select Date from Tran3 where MasterCode1='{$code}' AND Date <= '".date('Y-m-d', strtotime('-90 days'))."'";
        $response5  =   run_curl($query);
        $obj5       =   mungXML($response5);
        
           $result=[];
           $i=0;
        foreach($obj5->rs_data->z_row as $key=>$rows){
             
            $datas[]=$rows->attributes();
            $balance    =   $rows->attributes()->bal->__toString();
            $ref_No    =   $rows->attributes()->No->__toString();
            $dates=date('d-m-Y', strtotime($rows->attributes()->Date->__toString()));
            $now = time(); // or your date as well
            $your_date = strtotime($dates);
            $datediff = ceil(($now - $your_date)/86400);
            if(intval($datediff)>1){
            $datediff = intval($datediff)-1;
            }
           
            if($balance < 0 && !preg_match("/[a-z]/i",$balance)){
                //$DArr[] =  date('d-m-Y', strtotime($rows->attributes()->Date->__toString()));
                 
            }
            
             if(number_format($balance, 2)!=0){
            if($userType=='buyer'){
                
            $result[]=array('bill_receivable_date' => $dates,'days' => $datediff,'pending_amount' => number_format($balance, 2), 'ref_no'=> trim($ref_No),'total_pending_amount' =>0);
            }else{
                if(number_format($sum, 2)>0){
                    $num=number_format($sum, 2).' CR';
                }else{
                   $num= number_format($sum, 2).' DR';
                }
             $result[]=array('bill_payble_date' => $dates,'days' => '','pending_amount' => number_format($balance, 2), 'ref_no'=> trim($ref_No),'total_pending_amount' => $num);   
            }
             } 
             $i++; 
            }   
        
        //return (object)['days'=>$datas,'amount'=>$amount];
        //return $data;
          
         return $result;
    }

    function busy4444($vpid){

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        
        /********************************1st API*******************************/

        $query      =   "Select Code from Master1 where Alias='{$vpid}'";
        $response1  =   run_curl($query);
          $obj1       =   mungXML($response1);                                    
        $code       =   $obj1->rs_data->z_row->attributes()->Code;  

        /********************************1st API*******************************/
        $users =  User::where('veepeeuser_id',$vpid)->first();
        //return $users;
        $userType=$users->user_type;
       
        $query      =   "SELECT D1,D2,D3 FROM Folio1 where MasterCode='{$code}'";
        $response4  =   run_curl($query);
        $obj4       =   mungXML($response4);
        $OpeningBalance    =   $obj4->rs_data->z_row->attributes()->D1->__toString();

         /********************************2st API*******************************/
        
         $query6 = "SELECT SUM(Dr1) AS D1, SUM(Dr2) AS D2, SUM(Cr1) AS C1, SUM(Cr2) AS C2 FROM DailySum WHERE MasterCode1 = '{$code}' AND Date <= '2023-11-30'";

         $response6  =   run_curl($query6); 
         $obj6       =   mungXML($response6);
         //$balance_c1    =   $obj6->rs_data->z_row->attributes()->C1->__toString();
         //$balance_d1    =   $obj6->rs_data->z_row->attributes()->D1->__toString();
        $balance_c1 =0; $balance_d1=0;
           if (isset($obj6->rs_data->z_row)) { 
               if (method_exists($obj6->rs_data->z_row, 'attributes')) {
                  $attributes =$obj6->rs_data->z_row->attributes();
                  if (!empty($attributes)) {
                    $balance_c1    =   $obj6->rs_data->z_row->attributes()->C1->__toString();
                    $balance_d1    =   $obj6->rs_data->z_row->attributes()->D1->__toString();
                  }
               }
           }    
         $opbal = $OpeningBalance + $balance_c1 - $balance_d1;
        //return $opbal;

        // Assuming you have variables $startDate and $endDate with your dynamic date values
        $startDate = "2023-12-01";
        $endDate = "2024-01-08";     

        $query = "SELECT TRAN2.SRNO,TRAN2.C1,TRAN2.DATE,TRAN2.MASTERCODE1,Vch.Narration1,Vch.Narration2,vch.purchasebillno,vch.purchasebilldate ,(Select Top 1 NameAlias from Help1 as H1 where H1.NameOrAlias = 1 and H1.Code = TRAN2.MASTERCODE1) As MastName,TRAN2.VCHTYPE,TRAN2.VCHNO,TRAN2.VCHCODE,TRAN2.VALUE1,TRAN2.VCHSERIESCODE,TRAN2.SHORTNAR AS AccNar FROM TRAN2  WITH (NOLOCK)  Left Join VchOtherInfo as Vch on Vch.Vchcode = Tran2.VchCode WHERE TRAN2.VCHCODE IN( SELECT VCHCODE FROM TRAN2  WITH (NOLOCK)  WHERE RECTYPE = 1 And MASTERCODE1='".$code."' And MASTERCODE2=0) and RECTYPE = 1 And MASTERCODE2=0  ORDER BY TRAN2.DATE, TRAN2.VCHTYPE, TRAN2.VCHNO, TRAN2.VCHCODE, TRAN2.SRNO";


        //return $query;
        
        $response5  =   run_curl($query);
        $obj5       =   mungXML($response5);

        $seetData    =   $obj5->rs_data;
        $result=[];
        $i=0;
        foreach($obj5->rs_data->z_row as $key=>$rows){
            $mastercode1 = $rows->attributes()->MASTERCODE1->__toString();
            if($mastercode1 == $code){
                $datas[]=$rows->attributes();
                $amount    =   $rows->attributes()->VALUE1->__toString();
                $Narration1    =   $rows->attributes()->Narration1->__toString();
                $MastName    =   $rows->attributes()->MastName->__toString();
                $dates=date('d-m-Y', strtotime($rows->attributes()->DATE->__toString()));
                
                
                if($userType=='buyer'){
                    $result[]=array('date' => $dates,'amount' => number_format($amount, 2), 'mastname'=> $MastName, 'particulars'=> $Narration1,'openingBalance' =>$opbal);
                }else{
                    // if(number_format($sum, 2)>0){
                    //     $num=number_format($sum, 2).' CR';
                    // }else{
                    // $num= number_format($sum, 2).' DR';
                    // }
                    $result[]=array('date' => $dates,'amount' => number_format($amount, 2), 'mastname'=> $MastName, 'particulars'=> $Narration1,'openingBalance' =>$opbal);   
                }
            }
             
             $i++; 
            } 
        return $result;
    }
    
    function busyDatewise($vpid,$startDate,$endDate){

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        
        /********************************1st API*******************************/

        $query      =   "Select Code from Master1 where Alias='{$vpid}'";
        $response1  =   run_curl($query);
          $obj1       =   mungXML($response1);                                    
        $code       =   $obj1->rs_data->z_row->attributes()->Code;  

        /********************************1st API*******************************/
        $users =  User::where('veepeeuser_id',$vpid)->first();
        //return $users;
        $userType=$users->user_type;
       
        $query      =   "SELECT D1,D2,D3 FROM Folio1 where MasterCode='{$code}'";
        $response4  =   run_curl($query);
        $obj4       =   mungXML($response4);
        $OpeningBalance    =   $obj4->rs_data->z_row->attributes()->D1->__toString();

         /********************************2st API*******************************/
        $date = Carbon::parse($startDate);
        $previousDate = $date->subDay()->toDateString();
         
         $query6 = "SELECT SUM(Dr1) AS D1, SUM(Dr2) AS D2, SUM(Cr1) AS C1, SUM(Cr2) AS C2 FROM DailySum WHERE MasterCode1 = '{$code}' AND Date <= '{$previousDate}'";

         $response6  =   run_curl($query6);
         $obj6       =   mungXML($response6);
          $balance_c1 =0; $balance_d1=0;
           if (isset($obj6->rs_data->z_row)) { 
               if (method_exists($obj6->rs_data->z_row, 'attributes')) {
                  $attributes =$obj6->rs_data->z_row->attributes();
                  if (!empty($attributes)) {
                    $balance_c1    =   $obj6->rs_data->z_row->attributes()->C1->__toString();
                    $balance_d1    =   $obj6->rs_data->z_row->attributes()->D1->__toString();
                  }
               }
           }    
         $opbal = $OpeningBalance + $balance_c1 - $balance_d1;
         
        //return $opbal;

        // Assuming you have variables $startDate and $endDate with your dynamic date values
        //$startDate = "2024-03-01";
        //$endDate = "2024-03-28";     

        //$query = "SELECT TRAN2.SRNO,TRAN2.C1,TRAN2.DATE,TRAN2.MASTERCODE1,Vch.Narration1,Vch.Narration2,vch.purchasebillno,vch.purchasebilldate ,(Select Top 1 NameAlias from Help1 as H1 where H1.NameOrAlias = 1 and H1.Code = TRAN2.MASTERCODE1) As MastName,TRAN2.VCHTYPE,TRAN2.VCHNO,TRAN2.VCHCODE,TRAN2.VALUE1,TRAN2.VCHSERIESCODE,TRAN2.SHORTNAR AS AccNar FROM TRAN2  WITH (NOLOCK)  Left Join VchOtherInfo as Vch on Vch.Vchcode = Tran2.VchCode WHERE TRAN2.VCHCODE IN( SELECT VCHCODE FROM TRAN2  WITH (NOLOCK)  WHERE RECTYPE = 1 And MASTERCODE1='".$code."' And MASTERCODE2=0) and RECTYPE = 1 And MASTERCODE2=0  ORDER BY TRAN2.DATE, TRAN2.VCHTYPE, TRAN2.VCHNO, TRAN2.VCHCODE, TRAN2.SRNO";
        $query="SELECT Tran2.I2,Tran2.C2,TRAN2.TranType,TRAN2.RECONSTATUS,TRAN2.C1,TRAN2.DATE,TRAN2.B1, (Select top 1 (Select Top 1 NameAlias from Help1 as H1 where H1.NameOrAlias = 1 and H1.Code = t21.mastercode1) from Tran2 as t21 where t21.vchcode = tran2.vchcode and t21.rectype = 1 and  t21.value1 < 0 and t21.mastercode1 <> '".$code."' and t21.b1=tran2.b1 order by t21.srno) As DebitMast, (Select top 1 (Select Top 1 NameAlias from Help1 as H1 where H1.NameOrAlias = 1 and H1.Code = t22.mastercode1) from Tran2 as t22 where t22.vchcode = tran2.vchcode and t22.rectype = 1 and t22.value1 > 0 and t22.mastercode1 <> '".$code."' and t22.b1=tran2.b1 order by t22.srno) AS CreditMast, (Select top 1 shortnar from Tran2 as t2 where t2.vchcode = tran2.vchcode and t2.rectype = 1 and t2.value1 > 0 and t2.mastercode1 <> '".$code."' order by t2.srno) AS CrAccNar , (Select top 1 shortnar from Tran2 as t2 where t2.vchcode = tran2.vchcode and t2.rectype = 1 and t2.value1 < 0 and t2.mastercode1 <> '".$code."' order by t2.srno) AS DrAccNar,(Select top 1 I2 from Tran2 as t2 where t2.vchcode = tran2.vchcode and t2.rectype = 1 and t2.value1 > 0 and t2.mastercode1 <> '".$code."' order by t2.srno) AS CrInstType ,(Select top 1 I2 from Tran2 as t2 where t2.vchcode = tran2.vchcode and t2.rectype = 1 and t2.value1 < 0 and t2.mastercode1 <> '".$code."' order by t2.srno) AS DrInstType,(Select top 1 C2 from Tran2 as t2 where t2.vchcode = tran2.vchcode and t2.rectype = 1 and t2.value1 > 0 and t2.mastercode1 <> '".$code."' order by t2.srno) AS CrInstNo ,(Select top 1 C2 from Tran2 as t2 where t2.vchcode = tran2.vchcode and t2.rectype = 1 and t2.value1 < 0 and t2.mastercode1 <> '".$code."' order by t2.srno) AS DrInstNo  ,TRAN2.VCHTYPE,TRAN2.VCHNO,TRAN2.VCHCODE,TRAN2.VALUE1,TRAN2.VCHSERIESCODE,TRAN2.SHORTNAR,TRAN2.MASTERCODE1,TRAN2.SRNO,(Select MasterCode1 from tran1 as t1 where t1.vchcode = tran2.vchcode ) AS PartyCode ,(Select SelfImageLink from Tran1 where Tran1.VchCode=Tran2.VchCode) As SelfImageLink,(Select BusyDocLink from tran1 where tran1.VchCode=tran2.VchCode) As BusyDocLink,Vch.Narration1,Vch.Narration2,vch.purchasebillno,vch.purchasebilldate FROM TRAN2 Left Join VchOtherInfo as Vch on Vch.Vchcode = Tran2.VchCode WHERE RECTYPE = 1 AND MASTERCODE1 = '".$code."' AND MASTERCODE2 = 0  AND DATE >= '".$startDate."' AND DATE <= '".$endDate."' ORDER BY TRAN2.DATE,TRAN2.VCHTYPE,TRAN2.VCHNO,TRAN2.VCHCODE,TRAN2.SRNO";

       // return $query;
        
        $response5  =   run_curl($query);
        $obj5       =   mungXML($response5);
        //return($obj5);
         
        $seetData    =   $obj5->rs_data;
        $result=[];
        $i=0;
         
        foreach($obj5->rs_data->z_row as $key=>$rows){
            $mastercode1 = $rows->attributes()->MASTERCODE1->__toString();
            //$creditAmount =0;$debitAmount = 0;
            if($mastercode1 == $code){
                $datas[]=$rows->attributes();
                $amount    =   $rows->attributes()->VALUE1->__toString();
                $Narration1    =   $rows->attributes()->Narration1->__toString();
                //$MastName    =   $rows->attributes()->MastName->__toString();
                $vchtype    =   voucherCase($rows->attributes()->VCHTYPE->__toString()). $rows->attributes()->VCHTYPE->__toString();
                $dates=date('d-m-Y', strtotime($rows->attributes()->DATE->__toString()));
                $vchno = $rows->attributes()->VCHNO->__toString();
                
                 
                if($userType=='buyer'){
                    $result[]=array('date' => $dates,'amount' => number_format($amount, 2), 'vchtype'=> $vchtype, 'particulars'=> $Narration1,'openingBalance' =>$opbal,'vchno'=>$vchno);
                }else{
                    // if(number_format($sum, 2)>0){
                    //     $num=number_format($sum, 2).' CR';
                    // }else{
                    // $num= number_format($sum, 2).' DR';
                    // }
                    $result[]=array('date' => $dates,'amount' => number_format($amount, 2), 'vchtype'=> $vchtype, 'particulars'=> $Narration1,'openingBalance' =>$opbal,'vchno'=>$vchno);   
                }
            }
             
             $i++; 
            } 
             
        return $result;
    }
    
    function moneyFormatIndia($num){
        $nums = explode(".",$num);
        if(count($nums)>2){
            return "0";
        }else{
        if(count($nums)==1){
            $nums[1]="00";
        }
        $num = $nums[0];
        $explrestunits = "" ;
        if(strlen($num)>3){
            $lastthree = substr($num, strlen($num)-3, strlen($num));
            $restunits = substr($num, 0, strlen($num)-3); 
            $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; 
            $expunit = str_split($restunits, 2);
            for($i=0; $i<sizeof($expunit); $i++){

                if($i==0)
                {
                    $explrestunits .= (int)$expunit[$i].","; 
                }else{
                    $explrestunits .= $expunit[$i].",";
                }
            }
            $thecash = $explrestunits.$lastthree;
        } else {
            $thecash = $num;
        }
        return $thecash.".".$nums[1]; 
        }
    }
    function notifyAndroid($user_ids,$title,$message){
        if(!empty($user_ids)){
             
                $fcm =  User::where('id',$user_ids)->orderby('id','desc')->first('device_fcm');
                $device_token = @$fcm['device_fcm'];
                $msgarray = array("title" => $title, "body" => $message);
                $apiKey   = 'AAAA_uCA_U8:APA91bEQRM5fItZkwYERZvfIhh68jipbtGP5VFLbK-uzjA0M_EeOjkWoCws_fZQbE0fOPSdl0zKcj9N19-qVgunZovTo96y2ecJJASaXSgEWG77hRjaKjKFoXtErIkRUQsjwqkUix4Jx';
                 $registrationIDs = $device_token;
                 
                $url = 'https://fcm.googleapis.com/fcm/send';
                $androidMessage = array(
                    'registration_ids' => array($registrationIDs),
                    'notification' => $msgarray,
                );
                $headers = array(
                    'Content-Type:application/json',
                    'Authorization: key='.$apiKey
                );
                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($androidMessage));
                $result =curl_exec($ch);
                curl_close($ch);
                 /*$dd= array($result,$androidMessage); 
 print_r($dd);
 exit; */
             
        }
    }
    
    
    function fcmnotifyAndroid(){
       
         $users =  User::where('user_type','buyer')->where('status',1)->orderby('id','desc')->get();
         
           
        //$access_key='AAAASW_SDE8:APA91bHsMdEYfR9711nwokGpwUSertJVDc3TkINu5e2lkN_D2zuhWklVV56LA3zWQFh8Nmq67Wm6hIcRbjFZppaK1iIHKrraDHoJtAvDX2RA1XN3Wpx0_MMIEFHok_-PhkohtdsQrL80';
        $access_key='AAAA_uCA_U8:APA91bEQRM5fItZkwYERZvfIhh68jipbtGP5VFLbK-uzjA0M_EeOjkWoCws_fZQbE0fOPSdl0zKcj9N19-qVgunZovTo96y2ecJJASaXSgEWG77hRjaKjKFoXtErIkRUQsjwqkUix4Jx';
        $url="https://fcm.googleapis.com/fcm/send";
        
            foreach($users as $usr){
             $userid= $usr->id;
             //$userid='10868';
             $fcm =  User::where('id',$userid)->orderby('id','desc')->first('device_fcm');
                $device_token = $fcm['device_fcm'];
              $fcm =  FcmModel::where('user_id',$userid)->where('is_login',1)->orderby('id','desc')->first('device_fcm');
                $device_token = @$fcm['device_fcm'];
        //$device_token = 'evBODWXDpaM:APA91bElWRX5DHifIy7hGaP_29wGZFeRxMFyJObAvpoeo8_STXKXOV78wqoz026ZM68Uf0q1hvzTQvhE8FnsUmMvcvHb3KS1b5RZ7O8kvZK92I9tw0SiV109aVdkkPRQEmhG4lSF8rxd';
        $registrationIds = array($device_token);
        
        $userr      =  User::where('id',$userid)->first();
        
        $Samount    =  speneded_amount($userid);
        $Oamount    =  ordered_amount($userid);
        $data       =  busy($userr->veepeeuser_id);
        $remaining=$data->amount-($Oamount-$Samount);
        //$remaining=1000;
        //if($remaining<50000 && getBuyers($userr->id)->bypass==1){
        if($remaining<50000){ 
           /*return json_encode(['amount'=>$remaining,'days'=>$data->days,'ustatus'=>$userr->status,'Ordered'=>$Oamount,'Spent'=>$Samount,'BusyAmount'=>$data->amount,'bypass'=>0]); */
        $title='Amount Limit';
        $message='Remaining amount is not sufficient.';
        $msgarray = array("title" => $title, "body" => $message);
        $androidMessage = array(
                    'registration_ids' => $registrationIds,
                    'notification' => $msgarray,
                );
        $headers = array
        (
            'Authorization: key=' . $access_key,
            'Content-Type: application/json'
        );      
        DB::table('fcm_notification')->insert([
    'user_type' => 'buyer','user_id' => $userr->veepeeuser_id,'title' => $title,'notification' => $message,'read_unread' => 0
]);
        
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, $url);
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $androidMessage ) );
        $result = curl_exec($ch );
        curl_close( $ch );
        /*$dd= array($result,$androidMessage); 
         print_r($dd);
        exit;*/
        $buyer = getUser($userid);
        $whatsappTemplate ='buyer_limit_low';
        $whatsappParameters = array(
            array(
                "name" => "buyerfirmname",
                "value" => $buyer->name,
              ),array(
                "name" => "brlink",
                "value" => url("veepeeapp.html?page=Bill"),
              ),
            );
        //dd($whatsappParameters);
        $buyer_sms = getBuyers($userid);
        
        if($whatsappTemplate){
            whatsappCurl($whatsappTemplate, $buyer_sms->notify_sms, $whatsappParameters); //'8448983473'
        }
        
 
        }elseif($data->days>90){
    
        $title='90 days expired';
        $message='Due period 90 days expired.';
        $msgarray = array("title" => $title, "body" => $message);
        $androidMessage = array(
                    'registration_ids' => $registrationIds,
                    'notification' => $msgarray,
                );
        $headers = array
        (
            'Authorization: key=' . $access_key,
            'Content-Type: application/json'
        );      
        DB::table('fcm_notification')->insert([
        'user_type' => 'buyer','user_id' => $userr->veepeeuser_id,'title' => $title,'notification' => $message,'read_unread' => 0
        ]);     
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, $url);
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $androidMessage ) );
        $result = curl_exec($ch );
        curl_close( $ch );
        /*$dd= array($result,$androidMessage); 
         print_r($dd);
         exit;*/
          
        $buyer = getUser($userid);
        $whatsappTemplate ='buyers_days_low';
        $whatsappParameters = array(
            array(
                "name" => "buyerfirmname",
                "value" => $buyer->name,
              ),array(
                "name" => "accountno",
                "value" => $buyer->veepeeuser_id,
              ),array(
                "name" => "brlink",
                "value" => url("veepeeapp.html?page=Bill"),
              ),
            );
        //dd($whatsappParameters);
        $buyer_sms = getBuyers($userid);
        if($whatsappTemplate){
            whatsappCurl($whatsappTemplate, $buyer_sms->notify_sms, $whatsappParameters); //'8448983473'
        }     
        }
         
         }
           
        
        
      
    }
    
    function fcmnotifyAndroid2(){
        //$access_key='AAAASW_SDE8:APA91bHsMdEYfR9711nwokGpwUSertJVDc3TkINu5e2lkN_D2zuhWklVV56LA3zWQFh8Nmq67Wm6hIcRbjFZppaK1iIHKrraDHoJtAvDX2RA1XN3Wpx0_MMIEFHok_-PhkohtdsQrL80';
        $access_key='AAAA_uCA_U8:APA91bEQRM5fItZkwYERZvfIhh68jipbtGP5VFLbK-uzjA0M_EeOjkWoCws_fZQbE0fOPSdl0zKcj9N19-qVgunZovTo96y2ecJJASaXSgEWG77hRjaKjKFoXtErIkRUQsjwqkUix4Jx';
        $url="https://fcm.googleapis.com/fcm/send";
       
         $orders     =    OrderModel::whereIn('status',['Waiting for approval','New'])->get();
         
        $userid= '';
        
        if($orders != null && !$orders->isEmpty()){
            foreach ($orders as $row) {
                
                if($row->status=='New'){
                    $userid=$row->supplier_id;
                    $usertype='supplier';
                    
                }
                if($row->status=='Waiting for approval'){
                    
                 $userid=$row->buyer_id;
                    $usertype='buyer';
                }
                
             //$userid='10868';
             
             $fcm =  User::where('id',$userid)->orderby('id','desc')->first('device_fcm');
                $device_token = $fcm['device_fcm'];
              $fcm =  FcmModel::where('user_id',$userid)->where('is_login',1)->orderby('id','desc')->first('device_fcm');
                $device_token = @$fcm['device_fcm'];
        //$device_token = 'evBODWXDpaM:APA91bElWRX5DHifIy7hGaP_29wGZFeRxMFyJObAvpoeo8_STXKXOV78wqoz026ZM68Uf0q1hvzTQvhE8FnsUmMvcvHb3KS1b5RZ7O8kvZK92I9tw0SiV109aVdkkPRQEmhG4lSF8rxd';
        $registrationIds = array($device_token);
        
        $userr      =  User::where('id',$userid)->first();
        
         
         
         
        
         
        $title='Pending approval';
        $message='You have pending approval items, please contact order helpline';
        $msgarray = array("title" => $title, "body" => $message);
        $androidMessage = array(
                    'registration_ids' => $registrationIds,
                    'notification' => $msgarray,
                );
        $headers = array
        (
            'Authorization: key=' . $access_key,
            'Content-Type: application/json'
        );      
        DB::table('fcm_notification')->insert([
    'user_type' => $usertype,'user_id' => $userr->veepeeuser_id,'title' => $title,'notification' => $message,'read_unread' => 0
]);
        
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, $url);
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $androidMessage ) );
        $result = curl_exec($ch );
        curl_close( $ch );
        /*$dd= array($result,$androidMessage); 
         print_r($dd);
         exit;*/
          
        $supplier = getUser($row->supplier_id);
        $whatsappTemplate ='approvalpending_supplier';
        $whatsappParameters = array(
            array(
                "name" => "supplierfirmname",
                "value" => $supplier->name,
              ),array(
                "name" => "approvalpendinglink",
                "value" => url("/").'/veepeeapp.html?page=Approval',//url("order/show/".$row->id),
              ),
            );
        //dd($whatsappParameters);
        $supplier_sms = getSuplliers($row->supplier_id);
        if($whatsappTemplate){
            whatsappCurl($whatsappTemplate, $supplier_sms->notify_sms, $whatsappParameters); //'8448983473'$supplier_sms->notify_sms
        }
       
        $buyer = getUser($row->buyer_id);
        $whatsappTemplate ='approvalpending_buyer';
        $whatsappParameters = array(
            array(
                "name" => "buyerfirmname",
                "value" => $buyer->name,
              ),array(
                "name" => "approvalpendinglink",
                "value" => url("/").'/veepeeapp.html?page=Approval',
              ),
            );
        //dd($whatsappParameters);
        $buyer_sms = getBuyers($row->buyer_id);
        if($whatsappTemplate){
            whatsappCurl($whatsappTemplate, $buyer_sms->notify_sms, $whatsappParameters); //'8448983473'
        }        
                 
            }
        }
        
    }
    
function fcmnotifyAndroidforbiltydue(){
    
        //$access_key='AAAASW_SDE8:APA91bHsMdEYfR9711nwokGpwUSertJVDc3TkINu5e2lkN_D2zuhWklVV56LA3zWQFh8Nmq67Wm6hIcRbjFZppaK1iIHKrraDHoJtAvDX2RA1XN3Wpx0_MMIEFHok_-PhkohtdsQrL80';
        $access_key='AAAA_uCA_U8:APA91bEQRM5fItZkwYERZvfIhh68jipbtGP5VFLbK-uzjA0M_EeOjkWoCws_fZQbE0fOPSdl0zKcj9N19-qVgunZovTo96y2ecJJASaXSgEWG77hRjaKjKFoXtErIkRUQsjwqkUix4Jx';
        $url="https://fcm.googleapis.com/fcm/send";
       
         $orders     =    OrderModel::where('status','Confirm')->get();
        $userid= '';
        
        if($orders != null && !$orders->isEmpty()){
            foreach ($orders as $row) {
                
                
                 
                    
                 $buyerid=$row->buyer_id;
                 $supplierid=$row->supplier_id;
                    $usertype='buyer';
                 $biltydate=$row->orderlast_date;
                 $dates=date('d-m-Y', strtotime($biltydate. ' - 2 days'));
            $now = date('d-m-Y'); // or your date as well
            $your_date = $dates;
//$datediff = ceil(($now - $your_date)/86400);

//if(intval($datediff)==3){
if($now==$your_date){    
            $userid=$supplierid;   
             //$userid='10867';
             
             //if($supplierid==$userid){
                 
             //$fcm =  User::where('id',$userid)->orderby('id','desc')->first('device_fcm');
                //$device_token = $fcm['device_fcm'];
              //$fcm =  FcmModel::where('user_id',$userid)->where('is_login',1)->orderby('id','desc')->first('device_fcm');
                //$device_token = $fcm['device_fcm'];
        //$device_token = 'evBODWXDpaM:APA91bElWRX5DHifIy7hGaP_29wGZFeRxMFyJObAvpoeo8_STXKXOV78wqoz026ZM68Uf0q1hvzTQvhE8FnsUmMvcvHb3KS1b5RZ7O8kvZK92I9tw0SiV109aVdkkPRQEmhG4lSF8rxd';
        //$registrationIds = array($device_token);
        
        $userr      =  User::where('id',$userid)->first();
         
        $title='Bilty Due Date';
        //$message='Your order bilty due date will expire in three days-'.$row->orderlast_date;
        $message='Pending order expiring soon - '.$row->orderlast_date;
        $msgarray = array("title" => $title, "body" => $message);
        /*$androidMessage = array(
                    'registration_ids' => $registrationIds,
                    'notification' => $msgarray,
                );*/
        $headers = array
        (
            'Authorization: key=' . $access_key,
            'Content-Type: application/json'
        ); 
        
        DB::table('fcm_notification')->insert([
            'user_type' => $usertype,'user_id' => $userr->veepeeuser_id,'title' => $title,'notification' => $message,'read_unread' => 0
        ]);

    $supplier = getUser($row->supplier_id);
    $whatsappTemplate ='due_data_expire';
    $whatsappParameters = array(
        array(
            "name" => "supplierfirmname",
            "value" => $supplier->name,
          ),
          array(
            "name" => "orderformno",
            "value" => $row->vporder_id,
          ),array(
            "name" => "confirmorderlink",
            "value" => url("/").'/veepeeapp.html?page=Approval',
          ),
        );
    //dd($whatsappParameters);
    $supplier_sms = getSuplliers($row->supplier_id);
    if($whatsappTemplate){
        whatsappCurl($whatsappTemplate,$supplier_sms->notify_sms , $whatsappParameters); //'8448983473'
    }    
        /*$ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, $url);
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $androidMessage ) );
        $result = curl_exec($ch );
        curl_close( $ch );*/
        /*$dd= array($result,$androidMessage); 
         print_r($dd);
         exit;*/
                     //}      
                
        }            
            }
        }
        
    }
    
    function mungXML($xml){
        // CHANGE OUT COLONS ns: INTO ns_
        $nsm = array('s', 'rs', 'dt', 'z');
        foreach ($nsm as $key){
            // A REGULAR EXPRESSION TO MUNG THE XML
            $rgx
            = '#'               // REGEX DELIMITER
            . '('               // GROUP PATTERN 1
            . '\<'              // LOCATE A LEFT WICKET
            . '/?'              // MAYBE FOLLOWED BY A SLASH
            . preg_quote($key)  // THE NAMESPACE
            . ')'               // END GROUP PATTERN
            . '('               // GROUP PATTERN 2
            . ':{1}'            // A COLON (EXACTLY ONE)
            . ')'               // END GROUP PATTERN
            . '#'               // REGEX DELIMITER
            ;
            // INSERT THE UNDERSCORE INTO THE TAG NAME
            $rep
            = '$1'          // BACKREFERENCE TO GROUP 1
            . '_'           // LITERAL UNDERSCORE IN PLACE OF GROUP 2
            ;
            // PERFORM THE REPLACEMENT
            $xml =  preg_replace($rgx, $rep, $xml);
        }
        return SimpleXML_Load_String($xml);
    }
    
    function run_curl($data,$prdate = null){
        $service_code=1;        
        if($prdate){
            $service_code=11;
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_PORT => "981",
            CURLOPT_URL => "http://103.107.66.68:981/",
            //CURLOPT_URL => "http://5.veepeeonline.in:981/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            //CURLOPT_HEADER => true,
            CURLOPT_FOLLOWLOCATION=> true, 
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array("Pwd: Demo","SC: 1","UserName: Demo Cr","Qry: {$data}"),
        ));
        
        $response = curl_exec($curl);

        $err      = curl_error($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
          return $response;
        }
    }
    
    function days($data){
        sort($data);
        return (!empty($data)) ? ((integer)round((strtotime(date('Y-m-d')) - strtotime(current($data))) / (60 * 60 * 24))) : 0;
    }
    
    function user_status($val){
        return ($val == 1) ? 'Active' : "Incactive";
    }
    
     
    
    function status($status){
        $class = 'question-circle text-light';
        
        if($status == 'New'){
            $class = 'question-circle text-light';
        }
        if($status == 'Accepted'){
            $class = 'arrow-circle-down text-light';
        }
        if($status == 'Rejected'){
            $class = 'arrow-circle-right text-danger';
        }
        if($status == 'Processing'){
            $class = 'question-circle text-light';
        }
        if($status == 'Cancelled'){
            $class = 'times-circle text-danger';
        }
        if($status == 'Completed'){
            $class = 'check-circle text-success';
        }
        if($status == 'Accepted by supplier'){
            $class = 'user-circle text-success';
        }
        if($status == 'Waiting for approval'){
            $class = 'user-circle text-warning';
        }
        if($status == 'Confirm'){
            $class = 'circle text-success';
        }
        if($status == 'Processing'){
            $class = 'check-circle text-primary';
        }

        return "<a class='fas fa-".$class."' title='".$status." Order' ></a>";
    }
    function styleOrderColor($datee) {
        $todayDate = new DateTime('now');
        $orderDate = new DateTime($datee);
    
        $interval = $todayDate->diff($orderDate);
    
        // Assuming you want to apply different styles based on the interval
        if ($interval->days <= 6) {
            return "white";
           // return "table-success text-white"; // Within the last 7 days
        } elseif ($interval->days <= 9) {
            //return "table-info text-white"; // Within the last 30 days
            return "#ecedf1";
        } elseif ($interval->days <= 14){
           // return "table-primary text-dark";
           return "#d5d6db";
        }
         else {
            //return "table-secondary text-dark"; // Older than 30 days
            return "#bbbcc1";
        }
    }

    function whatsappCurl($template, $phonenumber, $parameters)
    {
        //die($phonenumber);
        $contactNumber = $phonenumber;
        if (!preg_match('/^91/', $contactNumber)) {
            // If not, add "91" to the beginning of the contact number
            $contactNumber = '91' . $contactNumber;
        }
        $url = 'https://live-server-116125.wati.io/api/v1/sendTemplateMessage?whatsappNumber='.$contactNumber;
        $headers = array(
            'accept: */*',
            'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiIxZGVkMjUzNC04NzhkLTQ5MzQtYmE0YS03Y2E0NzA3ZWVhZDQiLCJ1bmlxdWVfbmFtZSI6ImRldmVsb3BtZW50QHZlZXBlZW9ubGluZS5jb20iLCJuYW1laWQiOiJkZXZlbG9wbWVudEB2ZWVwZWVvbmxpbmUuY29tIiwiZW1haWwiOiJkZXZlbG9wbWVudEB2ZWVwZWVvbmxpbmUuY29tIiwiYXV0aF90aW1lIjoiMTIvMjAvMjAyMyAwODowODo1MCIsImRiX25hbWUiOiIxMTYxMjUiLCJodHRwOi8vc2NoZW1hcy5taWNyb3NvZnQuY29tL3dzLzIwMDgvMDYvaWRlbnRpdHkvY2xhaW1zL3JvbGUiOiJBRE1JTklTVFJBVE9SIiwiZXhwIjoyNTM0MDIzMDA4MDAsImlzcyI6IkNsYXJlX0FJIiwiYXVkIjoiQ2xhcmVfQUkifQ.FXwQSRPp8yWxsU_KzUV79yw_Zfj2NPisvG9v8dSIf8A',
            'Content-Type: application/json-patch+json',
        );

        $data = array(
            "template_name" => $template,
            "broadcast_name" => $template,
            "parameters" =>$parameters
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        }

        curl_close($ch);

        return $result;
    }
    
    function voucherCase($vchcode){
         //= "PURCHASE"; // Example value for demonstration
        $constantsArray = [
            2=>'PURCHASE' ,
            3=>'SALE_RETURN' ,
            4=>'MATERIAL_RECEIPT',
            5=>'STOCK_TRANSFER' ,
            6=>'PRODUCTION' ,
            7=>'UNASSEMBLE',
            8=>'STOCK_JOURNAL' ,
            9=>'SALE',
            10=>'PURCHASE_RETURN' ,
            11=>'MATERIAL_ISSUE',
            12=>'SALE_ORDER',
            13=>'PURCHASE_ORDER',
            14=>'RECEIPT',
            15=>'CONTRA' ,
            16=>'JOURNAL' ,
            17=>'DEBIT_NOTE',
            18=>'CREDIT_NOTE' ,
            19=>'PAYMENT',
            21=>'FORMS_RECEIVED' ,
            22=>'FORMS_ISSUED',
            26=>'SALE_QUOTATION' ,
            27=>'PURCHASE_QUOTATION',
            28=>'SALARY_CALCULATION',
            29=>'CALL_RECEIPT' ,
            30=>'CALL_ALLOCATION' ,
            31=>'PURCHASE_INDENT',
            32=>'CALL_REPORT',
            61=>'PHYSICAL_STOCK',
        ];
        $p_VchType= $constantsArray[$vchcode];
        switch ($p_VchType) {
            case "OP_BAL":
                $strRetVal = "OpBl";
                break;
            case "SALE": 
                $strRetVal = "SupO"; 
                break;
            case "PURCHASE":
                $strRetVal = "SupI";
                break;
            case "SALE_RETURN":
                
                $strRetVal = "SORt";
                
                break;
            case "PURCHASE_RETURN":
                $strRetVal = "SIRt";
                break;
            case "PAYMENT":
                $strRetVal = "Pymt";
                break;
            case "RECEIPT":
                $strRetVal = "Rcpt";
                break;
            case "JOURNAL":
                $strRetVal = "Jrnl";
                break;
            case "CONTRA":
                $strRetVal = "Cntr";
                break;
            case "DEBIT_NOTE":
                $strRetVal = "DrNt";
                break;
            case "CREDIT_NOTE":
                $strRetVal = "CrNt";
                break;
            case "STOCK_TRANSFER":
                $strRetVal = "StTf";
                break;
            case "PRODUCTION":
                $strRetVal = "Prod";
                break;
            case "UNASSEMBLE":
                $strRetVal = "UnAs";
                break;
            case "STOCK_JOURNAL":
                $strRetVal = "SJrl";
                break;
            case "MATERIAL_ISSUE":
                $strRetVal = "MtIs";
                break;
            case "MATERIAL_RECEIPT":
                $strRetVal = "MtRc";
                break;
            case "SALE_ORDER":
                $strRetVal = "SlOd";
                break;
            case "PURCHASE_ORDER":
                $strRetVal = "PuOd";
                break;
            case "FORMS_ISSUED":
                $strRetVal = "FmIs";
                break;
            case "FORMS_RECEIVED":
                $strRetVal = "FmRc";
                break;
            case "SALE_QUOTATION":
                $strRetVal = "SlQt";
                break;
            case "PURCHASE_QUOTATION":
                $strRetVal = "PrQt";
                break;
            case "SALARY_CALCULATION":
                $strRetVal = "Slry";
                break;
            case "VAT_JOURNAL":
                $strRetVal = "VATJRL";
                break;
            case "ADJUST_EXCISE_AMOUNTS":
                $strRetVal = "AEAMT";
                break;
            case "PHYSICAL_STOCK":
                $strRetVal = "PhyStk";
                break;
            case "CALL_RECEIPT":
                $strRetVal = "ClRc";
                break;
            case "CALL_ALLOCATION":
                $strRetVal = "ClAl";
                break;
            case "PURCHASE_INDENT":
                $strRetVal = "PrIn";
                break;
            case "CALL_REPORT":
                $strRetVal = "ClRp";
                break;
            default:
                $strRetVal = "    ";
        }
        
        return $strRetVal;

    }