<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{SuppliersModels};
use App\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Mail;

class ForgotPasswordController  extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    
    public function index(Request $request){
        if($request->all()){
            
            if($request->veepeeuser_id != '')
            {
                $sisfrm = SuppliersModels::where('account',$request->veepeeuser_id)->first(); 
                $user = User::where('veepeeuser_id',$request->veepeeuser_id)->first();
                if(!is_null($user))
                {
                    $user->username = rand(10000,99999);
                    $user->save();
                    $user->username;
                   
                    $link = url('reset-password/'.$user->username.'/'.$user->veepeeuser_id);
                    $email = $sisfrm->notify_email;
                     
                    $data = array('user_name' =>$user->name,'link' => $link);
                    
                    $a=Mail::send('emails.supplier_reset_password',$data, function ($message) use($email) {
                        $message->to($email)->subject('Password Re-set');
                    });

                    //send whatsapp notification
                    $whatsappTemplate = 'reset_pwd_link_cnew';
                    $whatsappParameters = array(
                        array(
                            "name" => "name",
                            "value" => $user->name,
                            ),
                        array(
                            "name" => "link",
                            "value" => $link,
                            ),
                    );
                    //print_r($sisfrm->notify_whatsapp); die;
                    whatsappCurl($whatsappTemplate, $sisfrm->notify_sms, $whatsappParameters);
                     
                    return redirect()->back()->with('success','Forgot Password link has been sent successfully to your registered email.');
                }
                else 
                {
                    return redirect()->back()->with('error','Please enter valid Veepee ID.');
                }
            }
            else 
            {
                return redirect()->back()->with('error','Please enter valid Veepee ID.');
            }
        } 
        else 
        {
            return view('supplier.forgot_password');
        }
        
    }
    
    public function update_password(Request $request,$otp,$vpid){
        if($request->all()){
            $request->validate([
                'password' => ['required'],
                'confirm_password' => ['same:password'],
            ]);

            $user = User::where('veepeeuser_id', $vpid)->where('username',$otp)->first();
            if($user){
                $user->username = NULL;
                $user->password = bcrypt($request->password);
                $user->save();
                return redirect(url('supplier/login'))->with('success','Password has been reset successfully.');
            } else {
                return redirect(url('supplier-forgot-password'))->with('error','This link has been expired. Please try again');
            }
                
            
        } else {
            
            return view('supplier.reset_password');
        }
    }
}
