<?php
   
namespace App\Http\Controllers\Auth;
   
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\User;
  
class ChangePasswordController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {   
        if(@Auth::check()->user_type == 'supplier'){
            $this->middleware('supplier');
        } else {
            $this->middleware('auth');
        }
        

    }
   
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('auth.change_password');
    } 
   
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update(Request $request)
    {   
       // print_r($request->all()); die;
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);
   
        $user_id = Auth::user()->id;
   
        User::find($user_id)->update(['password'=> Hash::make($request->new_confirm_password)]);
   
       return redirect()->back()->with('success','Your password has been changed successfully');
    }
}