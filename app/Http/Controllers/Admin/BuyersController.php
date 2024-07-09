<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\StoreBuyerRequest;
use App\Http\Requests\UpdateBuyerRequest;
use App\Models\CountryModel;
use App\Models\CityModels;
use App\Models\StatesModels;
use App\Models\StationModel;
use App\Models\BuyerModel;
use App\Models\BranchModel;
use App\User;
use Gate;
use Str;
use DB;
use Auth;
use Mail;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BuyersController extends Controller
{
   
    public function index(Request $request){
        abort_if(Gate::denies('buyer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if($request->all()){
           // print_r($request->all()); die;
            //$users1 = User::join('buyers', 'users.id', '=', 'buyers.user_id')->select('users.id as buyer_id','users.*', 'buyers.*');
            $users1 = User::where('user_type','buyer');
           // print_r(); die;
            if($request->name != ''){
                $users1->where('users.name','like','%'.$request->name.'%');
            }

            if($request->email != ''){
               // print_r($request->email); die;
                $users1->where('users.email',$request->email);
            }

            if($request->veepeeuser_id != ''){

                $users1->where('users.veepeeuser_id',$request->veepeeuser_id);
            }

            if($request->gst != ''){
                $users1->where('users.gst',$request->gst);
            }

            if($request->status != ''){
                $users1->where('users.status',$request->status);
            }
            $users1->orderby('users.created_at','desc');
            $users = $users1->paginate(20);
            return view('admin.buyers.index', compact('users'));
        } else {
            $users = User::where('user_type','buyer')->orderby('created_at','desc')->paginate(20);
            return view('admin.buyers.index', compact('users'));
        }
        
    }

    public function create()
    {
        abort_if(Gate::denies('buyer_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $countries = CountryModel::where('status',1)->get();
        $states = StatesModels::where('status',1)->get();
        $branches = BranchModel::where('status',1)->get(['name','id']);
        $stations = StationModel::where('status',1)->get(); 
        $sister_firm = User::where('user_type','buyer')->where('status',1)->get();       
        return view('admin.buyers.create',compact('countries','states','branches','stations','sister_firm'));
    }

    public function store(StoreBuyerRequest $request){
        
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*_";
        $password = substr( str_shuffle( $chars ), 0, 8 );
        $uemail = strtolower($request->email);
        $noofuser = User::where('user_type','buyer')->count();
        $veepeeuser_id =  'A'.str_pad($noofuser+1, 4, '0', STR_PAD_LEFT);
        $logindata = array(
            'name' => ucwords($request->name), 
            'email' => $uemail, 
            'veepeeuser_id' => $veepeeuser_id,
            'password' => bcrypt('password'),
            'user_type' => 'buyer', 
            'gst' => $request->gst, 
        );

       $user = User::create($logindata);

        $sisterfirms = '';
        if(!empty($request->sister_firm)){
            foreach ($request->sister_firm as $key => $value) {
                $sisterfirms = $sisterfirms.','. $value;
            }
        }
        

        $profiledata = array(
            'user_id' => $user->id, 
            'country_id' => 1, 
            'state_id' => $request->state_id, 
            'city_id' => $request->city_id, 
            'address' => ucwords($request->address), 
            'gst' => $request->gst, 
            'account' => $veepeeuser_id,//$request->account, 
            'owner_name' => ucwords($request->owner_name), 
            'owner_contact' => $request->owner_contact, 
            'credit_limit' => $request->credit_limit,
            'order_name' => $request->order_name, 
            'order_contact' =>  $request->order_contact, 
            'sister_firm' => ltrim($sisterfirms,','), 
            'notify_email' => $request->notify_email, 
            'notify_sms' => $request->notify_whatsapp,//$request->notify_sms, 
            'notify_whatsapp' => $request->notify_whatsapp, 
            'bypass' => $request->bypass ?? 0,  
            
        );

        $is_saved = BuyerModel::create($profiledata);
        if($is_saved){
            $data = array('user_name' => $veepeeuser_id,'password' => $password,'user_type'=>'buyer');
             Mail::send('emails.registeration_email',$data, function ($message) use($uemail) {
                $message->to($uemail)->subject('Veepee Account Credential');
            });
            return redirect()->route('admin.buyers.index')->withSuccess(['Success Message here!']);
        } else {
            return redirect()->route('admin.buyers.index')->withSuccess(['Error Try Again!!!']);
        }
    }

    public function edit($id){

        abort_if(Gate::denies('buyer_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $user = User::where('id',$id)->first(); 
        $countries = CountryModel::where('status',1)->get();
        $states = StatesModels::where('status',1)->get();
        $cities = CityModels::where('state_id',$user->buyer->state_id)->where('status',1)->get();
        $branches = BranchModel::where('status',1)->get(['name','id']);
        $stations = StationModel::where('status',1)->get(); 
        $sisterfirms = User::where('user_type','buyer')->where('status',1)->get(); 
              
        return view('admin.buyers.edit',compact('countries','branches','stations','user','states','cities','sisterfirms'));
    }

    public function update($id,UpdateBuyerRequest $request){
       
       $user = User::where('id',$id)->first();
        $logindata = array(
            'name' => ucwords($request->name), 
            'email' => $request->email, 
            'gst' => $request->gst, 
            /*'number' => $request->number, */
            //'veepeeuser_id' => $request->veepeeuser_id,
            /*'gender' => $request->gender, */
            //'password' => bcrypt($password),
            //'user_type' => 'buyer', 
        );

       $user->update($logindata);

       $sisterfirms = '';
       if($request->sister_firm){
            foreach ($request->sister_firm as $key => $value) {
                $sisterfirms = $sisterfirms.','. $value;
            }
        }

       $buyer = BuyerModel::where('user_id',$user->id)->first();
        $profiledata = array(
           // 'user_id' => $user->id, 
            'country_id' => 1, 
            'state_id' => $request->state_id, 
            'city_id' => $request->city_id, 
            'address' => ucwords($request->address), 
            'gst' => $request->gst, 
            /*'account' => $request->account,*/ 
            /*'station_id' => $request->station_id, */
            'owner_name' => ucwords($request->owner_name), 
            'owner_contact' => $request->owner_contact, 
            'credit_limit' => $request->credit_limit,
            'order_name' => $request->order_name, 
            'order_contact' =>  $request->order_contact, 
            'sister_firm' => ltrim($sisterfirms,','), 
            'notify_email' => $request->notify_email, 
            'notify_sms' => $request->notify_whatsapp,//$request->notify_sms, 
            'notify_whatsapp' => $request->notify_whatsapp, 
            'bypass' => $request->bypass ?? 0,  
            
        );

        $is_saved = $buyer->update($profiledata);
        if($is_saved){
            return redirect()->route('admin.buyers.index')->withSuccess(['Success Message here!']);
        } else {
            return redirect()->route('admin.buyers.index')->withSuccess(['Error Try Again!!!']);
        }
    }

    public function show($id){
        abort_if(Gate::denies('buyer_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $user = User::where('id',$id)->first();
        return view('admin.buyers.show', compact('user'));

    }

    public function destroy($id,BuyerModel $BuyerModel){
        abort_if(Gate::denies('buyer_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        User::where('id',$id)->delete();
        $BuyerModel = BuyerModel::where('user_id',$id)->first();
        if($BuyerModel){
            $BuyerModel->delete();
        }
        
        return back();
    }

}
