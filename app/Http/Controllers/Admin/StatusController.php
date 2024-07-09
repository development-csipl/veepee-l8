<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Gate;
use Str;
use DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StatusController extends Controller
{
   
private $tbl = [
"colors" => "colors",
"countries" => "countries",  
"items" => "items",
"sizes" => "sizes",
"stations" => "stations",
"users" => "users",
"transports" => "transports",
"states"=>"states",
"cities"=>"cities",
"transports"=>"transports",
"suppliers"=>"suppliers",
"branches" => "branches",
"brands" => "brands",
"enquiries" => "enquiries",
"user_fcm_data"=>"user_fcm_data"
];

 
  public function statusupdate(Request $request)
  {
    
    $active  =   'Active';
    $Inactive  =   'Inactive';
    
    if($request->status == 1){
    	DB::table($this->tbl[$request->tbl])->where('id', $request->pid)->update(['status' => $request->status]);
      $data['html'] = '<a href="javascript:;" data-tbl="'.$request->tbl.'"   class="btn btn-xs btn-success actinact" data-id="'.$request->pid.'" data-value="0">'.$active.'</a>';
    } else if($request->status== 0){    
      DB::table($this->tbl[$request->tbl])->where('id', $request->pid)->update(['status' => $request->status]);        
      $data['html'] = '<a href="javascript:;" data-tbl="'.$request->tbl.'"  class="btn btn-xs btn-danger actinact" data-id="'.$request->pid.'" data-value="1">'.$Inactive.' </a>';
    }

    echo json_encode($data);

  }
  
  public function loginstatusupdate(Request $request)
  {
    
    $active  =   'LogedIn';
    $Inactive  =   'LogedOut';
    
       
      DB::table($this->tbl['user_fcm_data'])->where('device_id', $request->pid)->update(['is_login' =>0,'device_fcm'=>'']);        
      $data['html'] = '<a href="javascript:;" data-tbl="user_fcm_data"  class="btn btn-xs btn-danger actinact" data-id="'.$request->pid.'" data-value="1">'.$Inactive.' </a>';
  

    echo json_encode($data);

  }
}
