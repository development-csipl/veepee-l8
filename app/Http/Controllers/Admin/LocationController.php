<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\StatesModels;
use App\Models\CityModels;
use App\Models\BranchModel;
use App\Models\BrandModel;
use App\Models\ItemModel;
use App\Models\OrderModel;
use App\Models\SuppliersModels;
use App\User;
use DB;
use Auth;

class LocationController extends Controller{
    
    function states(Request $request){
        $states = StatesModels::where('country_id', $request->get('id') )->get();
        //you can handle output in different ways, I just use a custom filled array. you may pluck data and directly output your data.
        $output = [];
        foreach( $states as $state )
        {
           $output[$state->id] = $state->state_name;
        }
        return $output;
    }
    
    function cities(Request $request){    
        $cities = CityModels::where('state_id', $request->get('id') )->get();
        $output = [];
        foreach( $cities as $city )
        {
           $output[$city->id] = $city->city_name;
        }
        return $output;
    }
    
    function stations(Request $request){    
        $cities = CityModels::where('id', $request->get('id') )->get();
        $output = [];
        foreach( $cities as $city )
        {
           $output[$city->id] = $city->city_name;
        }
        return $output;
    }
    
    function branches(Request $request){    
        $branches = BranchModel::where('id', $request->get('id') )->get();
        $output = [];
        foreach( $branches as $branche )
        {
           $output[$branche->id] = $branche->name;
        }
        return $output;
    }
    
    function supplierfirm(Request $request){    
        $supplier = User::where('user_type','supplier')->where('branch_id', $request->get('id'))->get();
        $output = [];
        foreach($supplier as $supplier )
        {
           $output[$supplier->id] = $supplier->name.' / '.$supplier->veepeeuser_id;
        }
        return $output;
    }
    
    function brands(Request $request){
        /*
        $brands = DB::table('brands')
                ->join('users', 'users.id', '=', 'brands.user_id')
                ->where('users.branch_id',$request->get('id'))
                ->select('brands.name','brands.id','brands.user_id')->get();
        $output = [];
        */
        $checkorders = OrderModel::where('status','Confirm')->where('supplier_id',$request->get('id'))->where('buyer_id',$request->get('buyerid'))->get();
        if(count($checkorders)<=0){
        
         $output = [];
        
        } else{
            $output['msg'] = 'hi';
        }
        $brands = BrandModel::with(['user'])->where('status',1)->where('user_id',$request->get('id'))->get();
        foreach($brands as $brands ){
           $output[$brands->id] = $brands->name;
        }
        return $output;
         
    }
    
    
      
    function items_old(Request $request){
        
        $Brand  = BrandModel::find($request->id);
        $User   = User::find($Brand->user_id);
        $items  = ItemModel::where('brand_id',$request->id)->get();
        $output = [];

        foreach($items as $item ){
           $output[$item->id] = ucfirst(strtolower($item->name."(".$item->season.")"));
        }
        
        return array('data'=>$output,'name'=>$User->name,'account'=>$User->veepeeuser_id,'status'=>$User->status);
    }

    function items(Request $request){
        
         $Brand  = BrandModel::find($request->id);
         $User   = User::find($Brand->user_id);
        // $items  = ItemModel::where('brand_id',$request->id)->get();
         $output = [];
        
        $brand_id  = $request->id;
        $suplliers =[];
        $suplliers  = DB::table('suppliers')
                        ->join('users', 'users.id', '=', 'suppliers.user_id')
                        ->leftjoin('brands', 'brands.user_id', '=', 'suppliers.user_id')
                        ->leftjoin('items', 'items.brand_id', '=', 'brands.id')
                        /*  ->join('countries', 'countries.id', '=', 'suppliers.country_id')*/
                        ->join('states', 'states.id', '=', 'suppliers.state_id')
                        ->join('cities', 'cities.id', '=', 'suppliers.city_id')
                        // ->join('cities as cty', 'cty.id', '=', 'suppliers.station_id')
                        ->where('items.status', '=', 1)
                        ->where('brands.id',$brand_id)
                        ->where('users.status', '=', 1)
                        ->first(['suppliers.*','states.state_name','cities.city_name','users.*']);
        $brands = BrandModel::where('user_id', $suplliers->user_id)->get()->toArray();
        if($brands){
            $brandIds = array_column($brands, 'id');
        }else{
            $brandIds = [];
        }
                    
        $items =ItemModel::where(['items.status'=>'1'])->whereIn('items.brand_id', $brandIds)
        ->leftjoin('brands', 'brands.id', '=', 'items.brand_id')
        ->get(['brands.name as brand_name','items.*']);

        foreach($items as $item ){
           $output[$item->id] = ucfirst(strtolower($item->name."(".$item->brand_name.")"));
        }
        //print_r($output);
        return array('data'=>$output,'name'=>$User->name,'account'=>$User->veepeeuser_id,'status'=>$User->status);
    }
    
    function suppliers(Request $request){ 
        $user_id = Auth::id();
        //print_r($user_id); die;
        $loginUser = User::select('user_type')->where('id',$user_id)->first(); 
        if($loginUser->user_type == 'supplier'){
            $suppliers = SuppliersModels::with(['user', 'city'])->where('status',1)->where('branch_id', $request->get('id'))->where('user_id',$user_id)->get();
        } else{
            $suppliers = SuppliersModels::with(['user', 'city'])->where('status',1)->where('branch_id', $request->get('id'))->get();
        } 
        $output    = [];
        foreach($suppliers as $supplier ){
           $output[$supplier->user->id] = ucwords($supplier->user->name)." / ".strtoupper($supplier->user->veepeeuser_id);
        }
        return $output;
    }

}
