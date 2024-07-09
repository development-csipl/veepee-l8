<?php
    use App\Models\{CountryModel,CityModels,StatesModels,SizeModel,ColorModel,BranchModel,BrandModel,TransportsModels,StationModel};
    use App\Models\{SuppliersModels,BuyerModel,OrderModel,OrderTrackingModel,OrderDeliveryModel,OrderCancelModel,FcmModel,SiteInfoModel};
    use App\{User,UserRole,Role};
    use Carbon\Carbon;
    use DB;
    
    //use Auth;
    function speneded_amount($user_id){
        $orders = OrderModel::where('buyer_id',$user_id)->where('status','Confirm')->get()->pluck('id')->toArray();
        return OrderDeliveryModel::where('status',1)->wherein('order_id',$orders)->get()->sum('price');
        //OrderDeliveryModel::where('status',1)->wherein('order_id',$orders)->where('veepee_invoice_number','!=',NULL)->get()->sum('price');
    }
    
    function ordered_amount($user_id){
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
       return OrderDeliveryModel::where('veepee_invoice_number',NULL)->where('status',NULL)->count();
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
       $cancel = OrderModel::where('supplier_id',$user_id)->where('supplier_accept',0)->whereBetween('order_date', [date("Y-m-1",strtotime("-1 month")), date("Y-m-t",strtotime("-1 month"))])->count();
       $total  = OrderModel::where('supplier_id',$user_id)->whereBetween('order_date', [date("Y-m-1",strtotime("-1 month")), date("Y-m-t",strtotime("-1 month"))])->count();
       return ($cancel === 0) ? 0 : ($cancel*100)/$total;
    }
    
    function branches(){
       return BranchModel::where('status',1)->get();
    }
    
    function delivered_cases($id){
       return OrderDeliveryModel::where('order_id',$id)->where('no_of_case','!=',NULL)->where('status',1)->sum('no_of_case');
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
        return OrderDeliveryModel::where('veepee_invoice_number',NULL)->where('created_by',$user_id)->where('status',0)->where('dispatch',0)->orderby('created_at','desc')->count();
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
        return (BranchModel::where('id',$id)->first())->name;
    }
    
    function getBranchs($id){
        return (CityModels::where('id',$id)->first())->city_name;
    }
    
    function getBrand($id){
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
        $order = OrderModel::where('id',$order_id)->where('supplier_accept',1)->where('buyer_accept',1)->first();
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
    
    function busy($vpid){

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
        if(count($obj2->rs_data->z_row)>0){ 
        
        $Folio1     =   $obj2->rs_data->z_row->attributes()->D1->__toString();
        }else{
           @$Folio1     =0; 
        }  
        
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

    function notifyAndroid($user_ids,$title,$message){
        if(!empty($user_ids)){
            foreach ($user_ids as $user_id) {
                $fcm =  FcmModel::where('user_id',$user_id)->where('is_login',1)->orderby('id','desc')->first('device_fcm');
                $device_token = $fcm['device_fcm'];
                $msgarray = array("title" => $title, "body" => $message);
                $apiKey   = 'AIzaSyAXDvwrxwYqc2AgyErwbh6e46LbQAsHsLU';
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
                curl_exec($ch);
                curl_close($ch);   
            }
        }
    }
    
    
    function fcmnotifyAndroid(){
       
         $users =  User::where('user_type','buyer')->where('status',1)->orderby('id','desc')->get();
         
           
        $access_key='AAAASW_SDE8:APA91bHsMdEYfR9711nwokGpwUSertJVDc3TkINu5e2lkN_D2zuhWklVV56LA3zWQFh8Nmq67Wm6hIcRbjFZppaK1iIHKrraDHoJtAvDX2RA1XN3Wpx0_MMIEFHok_-PhkohtdsQrL80';
        $url="https://fcm.googleapis.com/fcm/send";
        
            foreach($users as $usr){
             $userid= $usr->id;
             //$userid='10868';
             $fcm =  User::where('id',$userid)->orderby('id','desc')->first('device_fcm');
                $device_token = $fcm['device_fcm'];
              $fcm =  FcmModel::where('user_id',$userid)->where('is_login',1)->orderby('id','desc')->first('device_fcm');
                $device_token = $fcm['device_fcm'];
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
             
        }
         
         }
           
        
        
      
    }
    
    function fcmnotifyAndroid2(){
        $access_key='AAAASW_SDE8:APA91bHsMdEYfR9711nwokGpwUSertJVDc3TkINu5e2lkN_D2zuhWklVV56LA3zWQFh8Nmq67Wm6hIcRbjFZppaK1iIHKrraDHoJtAvDX2RA1XN3Wpx0_MMIEFHok_-PhkohtdsQrL80';
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
                $device_token = $fcm['device_fcm'];
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
    
    function run_curl($data){
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

        return "<a class='fas fa-".$class."' title='".$status." Order' ></a>";
    }
    
    
    
    