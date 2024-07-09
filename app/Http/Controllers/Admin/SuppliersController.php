<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSuppliersRequest;
use App\Http\Requests\UpdateSuppliersRequest;
use App\Models\CountryModel;
use App\Models\CityModels;
use App\Models\StatesModels;
use App\Models\StationModel;
use App\Models\SuppliersModels;
use App\Models\BranchModel;
use App\User;
use Gate;
use Str;
use DB;
use Auth;
use Mail;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuppliersController extends Controller
{
   
    public function index(Request $request){
        
       // send_sms('9625627912', 'testing');
        /*https://stackoverflow.com/questions/48467363/call-to-undefined-method-illuminate-database-query-builderisempty-on-eager-loa*/
        abort_if(Gate::denies('supplier_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $branches = BranchModel::where('status',1)->get();
        
        if($request->all()){
            //print_r($request->all()); die;
            //$users1 = User::select('users.*');
            
            $users1 = User::where('user_type','supplier');
            
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

            if($request->branch_id != ''){
               
                $users1->where('users.branch_id',$request->branch_id);
            }

            if($request->status != ''){
                $users1->where('users.status',$request->status);
            }
            
            $users1->orderby('users.veepeeuser_id','desc');
            $users = $users1->paginate(20);
            
            //echo '<pre>';print_r($users); die;;
            return view('admin.suppliers.index', compact('users','branches'));
            
        } else {
            $users =  User::where('user_type','supplier')->orderby('created_at','desc')->paginate(20);
            return view('admin.suppliers.index', compact('users','branches'));
            
        }
        
    }

    public function create()
    {
        abort_if(Gate::denies('supplier_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $countries = CountryModel::where('status',1)->get();
        $states = StatesModels::where('status',1)->get();
        $branches = BranchModel::where('status',1)->get(['name','id']);
        $stations = StationModel::where('status',1)->get();        
        $sisterfirms = User::where('user_type','supplier')->orderby('name','asc')->where('status',1)->get();
        return view('admin.suppliers.create',compact('countries','states','branches','stations','sisterfirms'));
    }

    public function store(StoreSuppliersRequest $request)
    {   
        
     

       // echo "<pre>"; print_r($request->all()); die;
        $sisterfirms = '';
        if(!empty($request->sister_firm)){
            foreach ($request->sister_firm as $key => $value) {
                $sisterfirms = $sisterfirms.','. $value;
            }
        }
        
        $chars      = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*_";
        $password   = substr( str_shuffle( $chars ), 0, 8 );
        $uemail     = strtolower($request->email);
        $noofuser   = User::where('user_type','supplier')->count();
        //$veepeeuser_id = 'L'.str_pad($noofuser+1, 4, '0', STR_PAD_LEFT);
        $logindata = array(
            'name' => ucwords($request->name), 
            'email' => $uemail, 
            'veepeeuser_id' => $request->supplier_id,
            'gender' => $request->gender, 
            'password' => bcrypt('password'),
            'user_type' => 'supplier', 
            'branch_id' => $request->branch_id,
            'gst' => $request->gst, 
        );

       $user = User::create($logindata);

       $catalogname = '';
       if ($files = $request->file('catalog')) {
        // Define upload path
            $destinationPath = public_path('/catalog');
            $catalog = $request->file('catalog');
            $catalogname = time().'-'.$user->id.'.'.$catalog->getClientOriginalExtension();
            $catalog->move($destinationPath, $catalogname);
        }
        
        $profiledata = array(
            'user_id' => $user->id, 
            'country_id' => 1, 
            'state_id' => $request->state_id, 
            'city_id' => $request->city_id, 
            'address' => ucwords($request->address),
            'account' => $request->supplier_id, 
            'gst' => $request->gst,
            'owner_name' => ucwords($request->owner_name), 
            'owner_contact' => $request->owner_contact, 
            'order_name' => $request->order_name, 
            'order_contact' =>  $request->order_contact, 
            'sister_firm' => ltrim($sisterfirms,','), 
            'category' => $request->category, 
            'market' => $request->market, 
            'design' => $request->design, 
            'pattern' => $request->pattern, 
            'min_quantity' => $request->min_quantity, 
            'packing' => $request->packing, 
            'fabric' => $request->fabric,
            'branch_id' => $request->branch_id, 
            'notify_email' => $request->notify_email, 
            'notify_sms' => $request->notify_whatsapp,// $request->notify_sms, 
            'notify_whatsapp' => $request->notify_whatsapp, 
            'catalog' => $catalogname
        );

        $is_saved = SuppliersModels::create($profiledata);
        if($is_saved){
            
            $data = array('user_name' => $request->supplier_id,'password' => $password,'user_type'=>'supplier');
                Mail::send('emails.registeration_email',$data, function ($message) use($uemail) {
                $message->to($uemail)->subject('Veepee Account Credential');
            });
             
            return redirect()->route('admin.suppliers.index')->withSuccess(['Success Message here!']);
        } else {
            return redirect()->route('admin.suppliers.index')->withSuccess(['Error Try Again!!!']);
        }
    }

    public function edit($id)
    {

        abort_if(Gate::denies('supplier_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = User::find($id);
        $countries = CountryModel::where('status',1)->get();
        $branches = BranchModel::where('status',1)->get(['name','id']);
        $stations = StationModel::where('status',1)->get();    
        $states = StatesModels::where('status',1)->get();
        $cities = CityModels::where('state_id',$user->supplier->state_id)->get();    
       // $sisterfirms = SuppliersModels::where('user_id',$id)->get();
        $sisterfirms = User::where('branch_id',$user->branch_id)->get();
        
        return view('admin.suppliers.edit', compact('countries','branches','stations','user','states','cities','sisterfirms'));
    }

    public function update($id,UpdateSuppliersRequest $request)
    {
        
       $user = User::where('id',$id)->first();
        $logindata = array(
            'name' => ucwords($request->name),
            'email' => $request->email,  
            'branch_id' => $request->branch_id,
            'veepeeuser_id' => $request->supplier_id, 
            'gst' => $request->gst, 

        );

       $user->update($logindata);

       $supplier = SuppliersModels::where('user_id',$id)->first();

       $catalogname = '';
       if ($files = $request->file('catalog')) {
        // Define upload path
            $destinationPath = public_path('/catalog');
            @unlink($destinationPath.'/'.$supplier->catalog);
            $catalog = $request->file('catalog');
            $catalogname = time().'-'.$user->id.'.'.$catalog->getClientOriginalExtension();
            $catalog->move($destinationPath, $catalogname);
        }

        $sisterfirms = @implode(',',@$request->sister_firm);

        $profiledata = array(
            'user_id' => $user->id, 
            'country_id' => 1, 
            'state_id' => $request->state_id, 
            'city_id' => $request->city_id, 
            'address' => ucwords($request->address),
            'gst' => $request->gst, 
            'account' => $request->supplier_id,
            //'account' => $request->account, 
            'station_id' => $request->station_id, 
            'owner_name' => $request->owner_name, 
            'owner_contact' => $request->owner_contact, 
            'order_name' => ucwords($request->order_name), 
            'order_contact' =>  $request->order_contact, 
            'sister_firm' =>  @ltrim($sisterfirms,','), 
            'category' => $request->category, 
            'market' => $request->market, 
            'design' => $request->design, 
            'pattern' => $request->pattern, 
            'min_quantity' => $request->min_quantity, 
            'packing' => $request->packing, 
            'fabric' => $request->fabric, 
            'discount' => $request->discount, 
            'branch_id' => $request->branch_id, 
            'notify_email' => $request->notify_email ?? 0, 
            'notify_sms' => $request->notify_whatsapp ?? 0,//$request->notify_sms ?? 0, 
            'notify_whatsapp' => $request->notify_whatsapp ?? 0, 
            'catalog' =>($catalogname == '') ? $supplier->catalog : $catalogname
        );
        //echo '<pre>'; print_r($profiledata); die;
        $is_saved = $supplier->update($profiledata);
        if($is_saved){
            
            return redirect()->route('admin.suppliers.index')->withSuccess(['Success Message here!']);
        } else {
            return redirect()->route('admin.suppliers.index')->withSuccess(['Error Try Again!!!']);
        }
        
    }

    public function show($id){
        abort_if(Gate::denies('supplier_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $user = User::where('id',$id)->first();
        return view('admin.suppliers.show', compact('user'));
    }

    public function destroy($id,SuppliersModels $SuppliersModels)
    {
        abort_if(Gate::denies('supplier_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        User::where('id',$id)->delete();
        $SuppliersModels = SuppliersModels::where('user_id',$id)->first();
        if($SuppliersModels){
            $SuppliersModels->delete();
        }
        
        return back();
    }

    public function massDestroy(MassDestroyStatesRequest $request)
    { 
        //   print_r(request('ids'));
        //   echo "sdfsa";exit;
        CityModels::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
