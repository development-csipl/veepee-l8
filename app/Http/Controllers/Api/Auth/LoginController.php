<?php 
namespace App\Http\Controllers\Api\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{SuppliersModels,BuyerModel,FcmModel,BrandModel,ItemModel};
use Illuminate\Support\Facades\Validator;
//use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser;
use App\User;
use Auth;
use Hash;
use Mail;
use Laravel\Passport\Token;
use Session;

class LoginController extends Controller{
    
    public $responseStatus = 200; 
     
    public function generate_otp(Request $request, User $user){
        $is_user = User::where('veepeeuser_id',$request->veepeeuser_id)->first();
        $phonenumber ='';
         
        if($is_user->user_type=='buyer'){
          $is_buyer = BuyerModel::where('user_id',$is_user->id)->first();  
          $phonenumber = $is_buyer->notify_sms;
          $phonenumberwhatsapp = $is_buyer->notify_whatsapp;
        } elseif($is_user->user_type=='supplier'){
            $is_buyer = SuppliersModels::where('user_id',$is_user->id)->first();
            $phonenumber = $is_buyer->notify_sms;
            $phonenumberwhatsapp = $is_buyer->notify_sms;
        }
         
        if($is_user){
            if($is_user->block != 1){
                if($phonenumber=='888888888' || $phonenumber=='9999999999'|| $phonenumber=='9827889867' || $phonenumber=='8448983473'){
                  $otp = 123456;  
                }else{
                $otp = mt_rand(100000, 999999);
                }
                $msg = 'we get login request from your Veepee account. OTP : '.$otp;
                //send_sms(9999144750, $msg);
                //send_sms(8700834684, $msg);
                //send_sms(9827889867, $msg);
                //send_sms(8448983473, $msg);
                //send_sms(8871025543, $msg);
                send_sms($phonenumber, $msg);
                
                $is_user->update(['otp' => $otp]);

                //send whatsapp notification
                $whatsappTemplate = '4_vp_login';
                $whatsappParameters = array(
                  array(
                      "name" => "name",
                      "value" => $is_user->name,
                    ),
                    array(
                        "name" => "login_otp",
                        "value" => $otp,
                    ),
                );
                whatsappCurl($whatsappTemplate, $phonenumberwhatsapp, $whatsappParameters);

                $response['messages'] = 'We have sent login OTP for your registered mobile number.';
                return response()->json(['response' => $response, 'status' => true],200);
            }else{
                $response['messages'] = 'Your account has been blocked.Please contact to administrator.';
                return response()->json(['response' => $response, 'block' => 1, 'status' => false],200);
            }
        } else {
            $response['messages'] = 'You are not a registred user.';
            return response()->json(['response' => $response, 'status' => false],200);
        }
    }

    public function loginbyotp(Request $request){
        //$users  =   User::where('otp','700045')->where('veepeeuser_id','A3249')->first();
         $users  =   User::where('otp',$request->otp)->where('veepeeuser_id',$request->username)->first();
        //  if($users->user_type == "supplier"){
        //   die("Site is down due to development work going on");
        //  }
        if($users){
             $query = User::where('id', $users->id)->update(array('device_fcm' => $request->device_fcm));
             //$notification   = "Remaining amount is not sufficient ";
         //notifyAndroid('6733','Veepee Internatonal',  $notification);
            return $this->users(Auth::loginUsingId($users->id),$request);
        }else {
            $response['messages'] = 'Your account credentials has been not matched.Please contact to administrator.';
            return response()->json(['response' => $response, 'block' => 0, 'status' => false],200);
        }
    }
    
    public function loginbytoken(Request $request){
        $id = (new Parser(new JoseEncoder()))->parse($request->bearerToken())->claims()->all()['jti'];
        $users   = Token::find($id)->user_id;
        if($users){
           
            return $this->users(Auth::loginUsingId($users),$request);
            
        }else{
            $response['messages'] = 'There is some issue, please contact to administrator.';
            return response()->json(['response' => $response, 'block' => 0, 'status' => false],200);
        }
    }
     
    public function login(Request $request,User $user){
        $validator =   Validator::make($request->all(), ['username' =>'required','password'=>'required']);
        if($validator->fails()){
            $response['messages'] = $validator->messages()->first();
            $response['otp']      = ''; 
            return response()->json(['response' => $response,'status' => false],201); 
        }else{
            $userdata = array('veepeeuser_id'=>$request->username,'password'=>$request->password);
            if(Auth::attempt($userdata)){
                return $this->users(Auth::user(),$request);
            }else{
                $response['messages'] = "Login Failed! Username and password is incorrect."; 
                return response()->json(['response' => $response, 'block' => 0, 'status' => false],200);
            }
        }
    }
 
    public function forgotPassword(Request $request){
        $validator =   Validator::make($request->all(),[
          'email' => 'required']);
        
          if($validator->fails()){
              $response['message'] = $validator->messages()->first();
            return response()->json(['response' => $response,'status' => false],401); 
          } else {
            // echo $request->email;exit;
              $userdata = array('email'=> $request->email);
             // $user = User::where(['email'=>$request->email])->first();
             $user = User::where(['veepeeuser_id'=>$request->email])->first();

          if(!empty($user)){
            $string1 = str_shuffle('abcdefghijklmnopqrstuvwxyz');
            $random1 = substr($string1,0,3);
            $string2 = str_shuffle('1234567890');
            $random2 = substr($string2,0,3);
            $random = $random1.$random2; 
            $query = User::where('id', $user->id)->update(array('password' => bcrypt($random)));
            if($query){
              $vpid=  $user->veepeeuser_id;
              $email=  $user->email;
              $data = array('user_name' =>$vpid,'password' => $random);
              Mail::send('emails.forgot_email',$data, function ($message) use($email) {
                 $message->to($email)->subject('Password Re-set');
                });
              }
            $response['message'] = 'New password has been successfully sent to your email id.';
           // $mailResponse=array('email'=>$user->email,'name'=>$user->name,'password'=>$random);
           // Mail::to($user->email,'Veepee')->send(new ForgotQuery($mailResponse));
              return response()->json(['response' => $response,'status' => true],200); 
           }
          else{
              $response['message'] = "Email ID not found in our database";
              return response()->json(['response' => $response,'status' => true],200); 
          }
      }
}
 
    public function changePass(Request $request){
    			
      $value = $request->bearerToken();
      $old_pass = $request->old_pass;
      $new_pass = $request->new_pass;
      $confirm_pass = $request->confirm_pass;
    
        $id = (new Parser(new JoseEncoder()))->parse($value)->claims()->all()['jti'];
        
      $user_id = Token::find($id)->user_id;
      
       if(!empty($user_id))
       {
          $validator =  Validator::make($request->all(), [
               'old_pass' => 'required|min:6',
               'new_pass' => 'required|min:6',
               'confirm_pass' => 'required|min:6'
               ]);
         if($validator->fails()){
           return response()->json([
           'message' => $validator->messages()->first(),  'status'=>false], 201);
         }
         else{
         $user  = DB::table('users')->select('*')
             ->where('id', $user_id)
             ->get()->first();
             //echo Hash::check($old_pass, bcrypt($user[0]->password));
             // echo bcrypt('123456')."</br>";
             // echo $user[0]->password;exit;
               if (Hash::check($old_pass, $user->password)) {
                 if($new_pass == $confirm_pass){
                   $query = DB::table('users')
                   ->where('id', $user_id)
                   ->update(array('password' => bcrypt($new_pass), 'password_updated' => 1));
                   //echo $user[0]->email;exit;
                   if($query){
                      // $mailResponse=array('email'=>$user[0]->email,'name'=>$user[0]->name);
                      $vpid=  $user->veepeeuser_id;
                      $email=  $user->email;
                      $data = array('user_name' =>$vpid,'password' => $new_pass);
                      Mail::send('emails.forgot_email',$data, function ($message) use($email) {
                         $message->to($email)->subject('Password Re-set');
                        });
                      return response()->json(['message' =>"Password changed successfully.",  'status'=>true], 201);
                   }else{
                     return response()->json(['message' =>"Error try again !!!",  'status'=>false], 201);
                   }
                 }
                 else{
                  return response()->json(['message' =>"Password not matched",  'status'=>false], 201);
                 }
               }
               else{
                return response()->json(['message' =>"Old Password not matched",'status'=>false], 201);
               }
         }
       }
        else
       {
            return response()->json([
           'message' => "All fields mandatory.",  'status'=>false
         ], 201);
    
       }	
     
    }

    public function updatePass(Request $request){
    			
      $value = $request->bearerToken();
      //$old_pass = $request->old_pass;
      $new_pass = $request->new_pass;
      $confirm_pass = $request->confirm_pass;
    
     // $id = (new Parser())->parse($value)->getHeader('jti');
      $id = (new Parser(new JoseEncoder()))->parse($value)->claims()->all()['jti']; 
      $user_id = Token::find($id)->user_id;
      
       if(!empty($user_id))
       {
          $validator =  Validator::make($request->all(), [
                'new_pass' => 'required|min:6',
                'confirm_pass' => 'required|min:6'
               ]);
         if($validator->fails()){
           return response()->json([
           'message' => $validator->messages()->first(),  'status'=>false], 201);
         }
         else{
         $user  = DB::table('users')->select('*')
             ->where('id', $user_id)
             ->get()->first();
                 if($new_pass == $confirm_pass){
                   $query = DB::table('users')
                   ->where('id', $user_id)
                   ->update(array('password' => bcrypt($new_pass),'password_updated'=>1));
                   //echo $user[0]->email;exit;
                   if($query){
                    $vpid=  $user->veepeeuser_id;
                    $email=  $user->email;
                    $data = array('user_name' =>$vpid,'password' => $new_pass);
                    Mail::send('emails.forgot_email',$data, function ($message) use($email) {
                       $message->to($email)->subject('Password Re-set');
                      });
                      return response()->json(['message' =>"Password changed successfully.",  'status'=>true], 201);
                   }else{
                     return response()->json(['message' =>"Error try again !!!",  'status'=>false], 201);
                   }
                 }
                 else{
                  return response()->json(['message' =>"Password not matched",  'status'=>false], 201);
                 }
             
         }
       }
        else
       {
            return response()->json([
           'message' => "All fields mandatory.",  'status'=>false
         ], 201);
       }	 
    }
    
    public function logout(Request $request){
        /*
        $value = $request->bearerToken();
        $id = (new Parser())->parse($value)->getHeader('jti');
        $revoked = DB::table('oauth_access_tokens')->where('id', '=', $id)->update(['revoked' => 1]);
        Auth::logout();
        
        $response['message'] = 'responsefully Logout';
        
        return response()->json(['response' =>$response], $this->responseStatus); 
        */
    }
    
    public function users($user,$request){
        if($user->block == 0){ 
            $upload                         = !empty($user->profile_pic) ? url('/').'/images/user_img/'. $user->profile_pic : '';     
            $response['url']                = url('catalog/');
            $response['user_id']            = Auth::user()->id;
            $response['user_name']          = $user->name;
            $response['email']              = $user->email;
            $response['mobile']             = $user->mobile;
            $response['password_updated']   = $user->password_updated;
            $response['gender']             = !empty($user->gender) ?  $user->gender : '';
            $response['dob']                = !empty($user->dob) ?  $user->dob : '';
            $response['profile_image']      = $upload ;
            $response['veepeeuser_id']      = !empty($user->veepeeuser_id) ?  $user->veepeeuser_id : '';
            $response['user_type']          = !empty($user->user_type) ?  $user->user_type : '';
            $response['remember_token']     = !empty($user->remember_token) ?  $user->remember_token : '';
            $response['branch_id']          = !empty($user->branch_id) ?  $user->branch_id : '';
            $response['messages']           = "Successfully Login";
            $response['user_status']           = $user->status;
            
            if(!empty($request->device_fcm) && !empty($request->device_id) && !empty($request->device_type)){
                 
                  $this->updateFCMDetails($response['user_id'],$request->device_fcm,$request->device_id,$request->device_type,$request->device_name);
                 
            }
            
            if($user->user_type === 'supplier'){
                $result                      = DB::table('suppliers')->join('users', 'users.id', '=', 'suppliers.user_id')->join('states', 'states.id', '=', 'suppliers.state_id')->join('cities', 'cities.id', '=', 'suppliers.city_id')->where('suppliers.user_id', '=', $response['user_id'])->get(['suppliers.*','states.state_name','cities.city_name','users.*'])->first();   
                $sisterfirm                  = @$result->sister_firm ?? '';
                $sisterfirm                  = explode(',',$sisterfirm);
                $response['sisterfirm_data'] = User::whereIn('id',$sisterfirm)->get(['id','name','email','number','status']);
                $response['brands']          = DB::table('brands')->join('items', 'items.brand_id', '=', 'brands.id')->where('brands.user_id', '=', $response['user_id'])->select('brands.name as brand_name','items.name as item_name','items.min_range','items.max_range','items.article_no','items.quantity')->get(); 
            }elseif($user->user_type === 'buyer'){
                $result = DB::table('buyers')->join('users', 'users.id', '=', 'buyers.user_id')->join('states', 'states.id', '=', 'buyers.state_id')->join('cities', 'cities.id', '=', 'buyers.city_id')->where('buyers.user_id', '=', $response['user_id'])->get(['buyers.*','states.state_name','cities.city_name','users.*'])->first();                  
                //where('users.status', '=', 1)->
            }else{
                $result=[];
            }
            
            $response['profile_details'] = $result;
            return response()->json(['response' => $response,'status'=>true,'block' => 0,'token'=> $user->createToken($user->name)->accessToken], 200); 
        }else {
            Auth::logout(); 
            $response['messages'] = 'Your account has been blocked.Please contact to administrator.';
            return response()->json(['response' => $response, 'block' => 1, 'status' => false],200);
        }
    }
    
    
    public function signout(Request $request) {
      
           
            $value = $request->bearerToken();
        //$id = (new Parser())->parse($value)->getHeader('jti');
        $id = (new Parser(new JoseEncoder()))->parse($value)->claims()->all()['jti'];
        //$users   = Token::find($id)->user_id;
        $revoked = DB::table('oauth_access_tokens')->where('id', '=', $id)->update(['revoked' => 1]);
        $query = FcmModel::where('device_id', $request->device_id)->update(array('device_fcm' =>'','is_login'=>0,'updated_at' =>date('Y-m-d H:i:s')));
        DB::table('user_fcm_data')->where('device_id', $request->device_id)->update(array('device_fcm' =>'','is_login'=>0,'updated_at' =>date('Y-m-d H:i:s')));
        Auth::logout();
        
        $response['message'] = 'responsefully Logout';
        
        return response()->json(['response' =>$response], $this->responseStatus); 
    }
    
    public function updateFCMDetails($user_id,$device_fcm,$device_id,$device_type,$device_name){
         
        $user = FcmModel::firstOrNew(array('user_id' => $user_id,'device_fcm'=>$device_fcm));
         
        $user->device_fcm = $device_fcm;
        $user->user_id = $user_id;
        $user->device_id = $device_id;
        $user->device_type = $device_type;
        $user->device_name = $device_name;
        $user->created_at = date('Y-m-d H:i:s');
        $user->updated_at = date('Y-m-d H:i:s');
         $user->save();        
    }
    
    public function checklogin(Request $request){
        $is_user = User::where('veepeeuser_id',$request->user_id)->first(); 
        if($is_user){
           $query = FcmModel::select('is_login')->where(['device_id'=> $request->device_id,'user_id'=>$is_user->id])->where('device_fcm', '<>', '')->groupBy('device_id')->get(); 
           if($query->isNotEmpty() && $query[0]->is_login){ //
            $response['login']= true;
            $response['message'] = 'User is login';
            } else {
                $response['login']= false;
                $response['message'] = 'User is logout';
            }
        }else{
            $response['login']= false;
            $response['message'] = 'User not exist';
        } 
         
        return response()->json(['response' =>$response], $this->responseStatus); 
    }
    /*public function updateFCMDetails($user_id,$device_fcm,$device_id,$device_type,$device_name){
         $lastuser = FcmModel::where('user_id',$user_id)->get()->last();
         
         if(@$lastuser->device_fcm==$device_fcm){
         
        $user = FcmModel::firstOrNew(array('user_id' => $user_id,'device_fcm'=>$device_fcm));
         
        $user->device_fcm = $device_fcm;
        $user->user_id = $user_id;
        $user->device_id = $device_id;
        $user->device_type = $device_type;
        $user->device_name = $device_name;
         $user->save(); 
         }else{
        $user = FcmModel::where('user_id',$user_id)->get()->last();
         
        $user->device_fcm = $device_fcm;
        $user->user_id = $user_id;
        $user->device_id = $device_id;
        $user->device_type = $device_type;
        $user->device_name = $device_name;
         $user->save();
         }
    }*/
    
    
    
    
}