<?php namespace App\Http\Controllers\Api\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SuppliersModels;
use App\Models\BuyerModel;
use App\Models\FcmModel;
use Illuminate\Support\Facades\Validator;
use Lcobucci\JWT\Parser;
use App\User;
use Auth;
use Hash;
use Mail;
use Laravel\Passport\Token;

class LoginController extends Controller
{
     public $responseStatus = 200; 
     
    public function login(Request $request, User $user)
    {
       
            $validator =   Validator::make($request->all(), [
                  'username' => 'required',
                  'password' => 'required',
                ]);
             
              if($validator->fails()){
                   $response['messages'] = $validator->messages()->first();
                    $response['otp'] = ''; 
                 return response()->json(['response' => $response,'status' => false],201); 

              } else{

                     $userdata = array(
                          'veepeeuser_id' => $request->username,
                          'password'  =>  $request->password
                      );
                      // attempt to do the login
                     // dd($userdata);
                     $device_fcm  = $request->device_fcm;
                     $device_id   = $request->device_id;
                     $device_type = $request->device_type;
                      if (Auth::attempt($userdata)) {
                            $user = Auth::user();  
                                                   
                            $upload=!empty($user->profile_pic) ? url('/').'/images/user_img/'. $user->profile_pic : '';                           
                            $response['user_id'] = Auth::user()->id;
                            $response['user_name'] = $user->name;
                            $response['email'] = $user->email;
                            $response['mobile'] = $user->mobile;
                            $response['password_updated'] = $user->password_updated;
                            $response['gender'] = !empty($user->gender) ?  $user->gender : '';
                            $response['dob'] = !empty($user->dob) ?  $user->dob : '';
                            $response['profile_image'] = $upload ;
                            $response['veepeeuser_id'] = !empty($user->veepeeuser_id) ?  $user->veepeeuser_id : '';
                            $response['user_type'] = !empty($user->user_type) ?  $user->user_type : '';
                            $response['remember_token'] = !empty($user->remember_token) ?  $user->remember_token : '';
                            $response['branch_id'] = !empty($user->branch_id) ?  $user->branch_id : '';
                            $response['messages'] = "Successfully Login";
                             // update fcm token
                             $updateFCM  = $this->updateFCMDetails($response['user_id'],$device_fcm,$device_id,$device_type); 
                            if($user->user_type == 'supplier'){
                              $result  = DB::table('suppliers')
                              ->join('users', 'users.id', '=', 'suppliers.user_id')
                              ->leftjoin('brands', 'brands.user_id', '=', 'suppliers.user_id')
                              ->leftjoin('items', 'items.brand_id', '=', 'brands.id')
                              ->join('countries', 'countries.id', '=', 'suppliers.country_id')
                              ->join('states', 'states.id', '=', 'suppliers.state_id')
                              ->join('cities', 'cities.id', '=', 'suppliers.city_id')
                              ->join('cities as cty', 'cty.id', '=', 'suppliers.station_id')
                              // ->where('items.status', '=', 1)
                              // ->where('brands.status', '=', 1)
                             // ->where('suppliers.status', '=', 1)
                              ->where('suppliers.user_id', '=', $response['user_id'])
                              ->get(['suppliers.*','countries.country','states.state_name','cities.city_name','users.*','cty.city_name as station_name'])->first();   
                              // $result = SuppliersModels::where(['user_id'=> $response['user_id']])->get()->first();
                              $sisterfirm = $result->sister_firm;
                              $sisterfirm = explode(',',$sisterfirm);
                             // print_r($sisterfirm);
                              $response['sisterfirm_data'] = User::whereIn('id',$sisterfirm)->get(['id','name','email','number']);
                            }elseif($user->user_type=='buyer'){
                             
                              $result = DB::table('buyers')
                              ->join('users', 'users.id', '=', 'buyers.user_id')
                              ->join('countries', 'countries.id', '=', 'buyers.country_id')
                              ->join('states', 'states.id', '=', 'buyers.state_id')
                              ->join('cities', 'cities.id', '=', 'buyers.city_id')
                              ->join('cities as cty', 'cty.id', '=', 'buyers.station_id')
                              ->where('users.status', '=', 1)
                              ->where('buyers.user_id', '=', $response['user_id'])
                              ->get(['buyers.*','countries.country','states.state_name','cities.city_name','users.*','cty.city_name as station_name'])->first();                  
                            }else{
                              $result=[];
                            }
                            $response['profile_details'] = $result;
                          return response()->json(['response' => $response,'status'=>true,'token'=> $user->createToken($user->name)->accessToken], 200); 
                       //   return response()->json(['response' => true,'error'=>''],200);
                      } else {
                          $response['messages'] = "Login Failed! Username and password is incorrect."; 
                          return response()->json(['response' => $response, 'status' => false],200);
                      }
             }
    }

// forgot password 
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


// chage user profile password 
public function changePass(Request $request){
			
  $value = $request->bearerToken();
  $old_pass = $request->old_pass;
  $new_pass = $request->new_pass;
  $confirm_pass = $request->confirm_pass;

      $id = (new Parser())->parse($value)->getHeader('jti');
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
// update password 
public function updatePass(Request $request){
			
  $value = $request->bearerToken();
  //$old_pass = $request->old_pass;
  $new_pass = $request->new_pass;
  $confirm_pass = $request->confirm_pass;

  $id = (new Parser())->parse($value)->getHeader('jti');
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
// update fcm token 
protected function updateFCMDetails($user_id,$device_fcm,$device_id,$device_type){
              $user = FcmModel::firstOrNew(array('user_id' => $user_id,'device_fcm'=>$device_fcm));
              $user->device_fcm = $device_fcm;
              $user->user_id = $user_id;
              $user->device_id = $device_id;
              $user->device_type = $device_type;
              $user->save();        
}
    /*public function logout(Request $request)
    {
            $value = $request->bearerToken();
            $id = (new Parser())->parse($value)->getHeader('jti');
            $revoked = DB::table('oauth_access_tokens')->where('id', '=', $id)->update(['revoked' => 1]);
            Auth::logout();
     
          $response['message'] = 'responsefully Logout';
        
          return response()->json(['response' =>$response], $this->responseStatus); 
    }*/


}
