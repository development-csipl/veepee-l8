<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Session;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Route;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'admin/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function ulogin(Request $request){
         Session::regenerate(true);
        if($request->all()){
            // Validate the form data
            $this->validate($request, [
                'email'   => 'required|email',
                'password' => 'required|min:6'
            ]);
              
            // Attempt to log the user in

            if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
                // if successful, then redirect to their intended location
                $user = Auth::user();
                if($user->status === 0){
                    print_r('User has been deactivated by Admin.');
                   return redirect(url('logout'));
                         
                    }
                if($user->user_type == 'supplier'){
                    if($user->password_updated === 0){
                        return redirect(route('change_password_form'));
                    } else {
                        return redirect(url('admin/dashboard'));
                    }
                    
                } elseif ($user->user_type == 'admin'){
                    return redirect(url('admin/dashboard'));
                } else {
                    print_r('Not a authorised user.');
                }
                
            } 

            // if unsuccessful, then redirect back to the login with the form data
            return redirect()->back()->withInput($request->only('email', 'remember'));
        } else {
              
            return view('admin.login');
        }
    }
    
    public function supplier_login(Request $request){
        Session::regenerate(true);
        if($request->all()){
            
            // Validate the form data
            $this->validate($request, [
                'veepeeuser_id'   => 'required',
                'password' => 'required|min:6'
            ]);
              
            // Attempt to log the user in

            $user = User::where('veepeeuser_id',$request->veepeeuser_id)->first();
         // print_r($request->all()); die;
            if(!@$user->email){
            
                    return redirect()->back()->with('error','This is not a valid ID');
                } else {
                if($user->block === 0){
                /*if($user->status === 1){*/
                   // print_r($user); die;
                    if (Auth::attempt(['veepeeuser_id' => $request->veepeeuser_id, 'password' => $request->password], $request->remember)) {
                        // if successful, then redirect to their intended location
                        $luser = Auth::user();
                      // print_r($luser); die;
                        if($luser->user_type == 'supplier'){
                            if($luser->password_updated === 0){
                                //return redirect(route('change_password_form'));
                                return redirect(url('admin/dashboard'));
                            } else {
                                return redirect(url('admin/dashboard'));
                            }
                            
                        } elseif ($luser->user_type == 'admin'){
                            return redirect(url('admin/dashboard'));
                        } else {
                            print_r('Not a authorised user.');
                        } 
                    } else {
                        return redirect()->back()->with('error','Please enter valid username and password.');
                    }
                /*} else {
                    return redirect()->back()->with('error','Your account is Inactive. Please contact to manager.');
                }*/
                
                }else {
                    return redirect()->back()->with('error','Your account is blocked. Please contact to manager.');
                }
                
            
            // if unsuccessful, then redirect back to the login with the form data
            return redirect()->back()->withInput($request->only('veepeeuser_id', 'remember'));
            }
        } else {
            return view('supplier.login');
        }

    }
    

    public function logout(){

        $user = Auth::user();
        Session::flush();
        
            return redirect()->intended(url('login'));
        
    }
    
    public function supplierlogout(){
        exit;

        $user = Auth::user();
        Session::flush();
       
            return redirect()->intended(route('supplier.login'));
        
    }
}
