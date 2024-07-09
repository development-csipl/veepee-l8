<?php namespace App\Http\Controllers\Api\Auth;
use App\Http\Controllers\Controller;
use App\User;
use App\Http\Controllers\Api\ReferredController;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Cart;
use App\Models\CartItem;
use GuzzleHttp\Client;
use Auth;
use Mail;
use App\Mail\Frontend\RegisterQuery;

class RegisterController extends Controller
{
    use RegistersUsers;

    public function register(Request $request)
    {
        /* $this->validate($request, [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6'
        ], [
            'password.confirmed' => 'The password does not match.'
        ]);
         return response()->json(['success' => "fd",'error'=>''],200); 
         */

    // $messages = array(
    //     'password_confirmation.same' => 'We need to know your e-mail address!',
    //     'password.same' => 'We need to know your e-mail address!',
    // );
    $validator =   Validator::make($request->all(), [
            'name' => 'required|min:3',
            'mobile' => 'required|unique:users,mobile',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'required|min:6'
           
        ], ['password.same' => 'Password does not match with confirm password']);
       
        if($validator->fails()){
            $response['message'] = $validator->messages()->first();
           return response()->json(['response' => $response,'status' => false],401); 
        } else {
             event(new Registered($this->create($request->all())));
             $userdata = array('email'=> $request->email, 'password'  =>  $request->password );
             Auth::attempt($userdata);
             $user = Auth::user();
             if($request->referral){ 
                $ref= new ReferredController();
                event($ref->registrationReferral($request->all(),$user));
             }
            // $upload=!empty($user->profile_image) ? url('/').'/images/user_img/'. $user->profile_image : '';
             $response['token'] =  $user->createToken($user->name)->accessToken; 
             $response['user_id'] = Auth::user()->id;
             $response['email'] = $user->email;
             $response['gender'] = !empty($user->gender) ?  $user->gender : '';
             $response['dob'] = !empty($user->dob) ?  $user->dob : '';
             $response['profile_image'] = '' ;
             $response['message'] = 'Successfully Registered';
             $mailResponse=array('email'=>$user->email,'name'=>$user->name);
             Mail::to($user->email,'Veepee International')->send(new RegisterQuery($mailResponse));
            return response()->json(['response' => $response,'status' => true],200); 
        }

       
    }


 


    public function googlelogin(Request $request, User $userD) {
     
        $userData =  User::where("email",$request->email)->first();
        if($userData === null)
        {
          
               $userD->name = $request->name;
               $userD->email =  $request->email;
               $userD->save();

             if (Auth::loginUsingId($userD->id))
             {
                         $user = Auth::user();
                        // $upload=!empty($user->profile_image) ? url('/').'/images/user_img/'. $user->profile_image : '';
                         $response['token'] =  $user->createToken($user->name)->accessToken; 
                         $response['user_id'] = Auth::user()->id;
                         $response['user_name'] = $user->name;
                         $response['email'] = $user->email;
                         $response['gender'] =  '';
                         $response['dob'] =  '';
                         $response['profile_image'] = '' ;
                         $response['message'] = 'Successfully Registered';
						 $response['qnt'] ='';
                         $response['cartKey']='fab';                                   
                         $cartId= Cart::where('userID',Auth::user()->id)->orderBy('id','desc')->get('id')->first(); 
							if($cartId){
							  $response['cartKey']=$cartId->id;
								if(!empty($request->cartkey) && $request->cartkey!='fab'){
									$res = $this->CheckCartKey(Auth::user()->id,$request->cartkey,$cartId->id);
								}                           
							  if($response['cartKey']){
							   $response['qnt'] = CartItem::where(['cart_id' => $response['cartKey']])->count();
							  }
							}
							else{
								$response['cartKey'] = !empty($request->cartkey) ? $request->cartkey : 'fab';
							}
                         $mailResponse=array('email'=>$user->email,'name'=>$user->name);
                       //  Mail::to($user->email,'Fabrento')->send(new RegisterQuery($mailResponse));
                        return response()->json(['response' => $response,'status' => true],200); 


            }




        }
        else
        {
                   
                      if (Auth::loginUsingId($userData->id))
                       {
                                  $user = Auth::user(); 
                                  $response['token'] =  $user->createToken($user->name)->accessToken; 
                                  $response['user_id'] = Auth::user()->id;
                                  $response['user_name'] = $user->name;
                                  $response['email'] = $user->email;
                                  $response['gender'] =  '';
                                  $response['dob'] =  '';
                                  $response['profile_image'] = '' ;
                                  $response['messages'] = "Successfully Login";
								  $response['qnt'] ='';
                                  $response['cartKey']='fab';                                   
                                  $cartId= Cart::where('userID',Auth::user()->id)->orderBy('id','desc')->get('id')->first(); 
                                    if($cartId){
                                      $response['cartKey']=$cartId->id;
                                        if(!empty($request->cartkey) && $request->cartkey!='fab'){
                                            $res = $this->CheckCartKey(Auth::user()->id,$request->cartkey,$cartId->id);
                                        }                           
                                      if($response['cartKey']){
                                       $response['qnt'] = CartItem::where(['cart_id' => $response['cartKey']])->count();
                                      }
                                    }
									else{
									$response['cartKey'] = !empty($request->cartkey) ? $request->cartkey : 'fab';
									}
                                   return response()->json(['response' => $response,'status'=>true], 200); 

                         }
          

        }
        
        //return view ( 'index' )->withDetails ( $user )->withService ( $service );
    }




    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'password' => bcrypt($data['password']),
        ]);
    }
	  public function CheckCartKey($user_id,$Requestcartkey,$tabelCartKey){
        $res =  CartItem::where(['cart_id' => $Requestcartkey])->update(array('cart_id' => $tabelCartKey));
        Cart::where('id', $Requestcartkey)->delete();
    }
}
