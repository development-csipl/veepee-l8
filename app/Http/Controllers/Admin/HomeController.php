<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\SiteInfoModel;
use App\Models\BankdetailModel;
use App\Models\OrderModel;
use App\Models\EnquiryModel;
use App\Models\EnquiryDataModel;
use App\Models\EnquiryGalleryModel;
use App\Models\ContactInfoModel;
use Gate;
use Str;
use DB;
use Auth;
use Illuminate\Http\Request;

class HomeController
{
    public function index(){
        //echo "Work in progress.";
        //exit;
        $user =  Auth::user();
        $new = 0;
        $accepted = 0;
        $waitingforresponse = 0;
        $rejected = 0;
        $lastsixmonth = 0;
        $user_type  = getRole($user->id);
       // print_r($user_type); die;
       $rejdate = date('Y-m-d',strtotime(date('Y-m-d') .' -365 day'));
        if(@$user->user_type == 'supplier'){
            $new = OrderModel::select('id')->where('status','New')->where('supplier_id',$user->id)->count();
            $waitingforresponse = OrderModel::select('id')->where('status','Waiting for approval')->where('supplier_id',$user->id)->count();
            //$rejected  = OrderModel::select('id')->where('status','Rejected')->where('supplier_id',$user->id)->count();
            $rejected = OrderModel::where('status','Rejected')->where('updated_at','>=', $rejdate)->where('supplier_id',$user->id)->count();
            //$accepted =  OrderModel::select('id')->where('status','Confirm')->where('supplier_id',$user->id)->count();
            $accepted = OrderModel::whereIn('status',['Confirm','Processing'])->where('supplier_id',$user->id)->where('created_at','>=',date('Y-m-d 00:00:00',strtotime('-6 month')))->where('created_at','<=',date('Y-m-d 23:59:59'))->count();
            $lastsixmonth =   OrderModel::select('id')->where('status','Completed')->where('orders.created_at','>=',date('Y-m-d 00:00:00',strtotime('-6 month')))->where('orders.created_at','<=',date('Y-m-d 23:59:59'))->where('supplier_id',$user->id)->count();

        } else {
            $new = OrderModel::where('status','New')->count();
            $waitingforresponse = OrderModel::select('id')->where('status','Waiting for approval')->count();
            $rejected = OrderModel::select('id')->where('status','Rejected')->count();
            $accepted =  OrderModel::select('id')->where('status','Confirm')->count();
            $lastsixmonth =  OrderModel::select('id')->where('status','Completed')->where('orders.created_at','>=',date('Y-m-d 00:00:00',strtotime('-6 month')))->where('orders.created_at','<=',date('Y-m-d 23:59:59'))->count();
        }
        return view('admin.home',compact('new','accepted','waitingforresponse','rejected','lastsixmonth'));
    }


    public function site_info(Request $request){
        $sitedata = SiteInfoModel::first();
        if($request->all()){
            if($sitedata){
                 $tnc_supplier = '';
               if ($files = $request->file('tnc_supplier')) {
                // Define upload path
                    $destinationPath = public_path('/catalog');
                    $catalog = $request->file('tnc_supplier');
                    $tnc_supplier = time().'tnc_supplier.'.$catalog->getClientOriginalExtension();
                    $catalog->move($destinationPath, $tnc_supplier);
                }
                
                $tnc_buyer = '';
               if ($files = $request->file('tnc_buyer')) {
                // Define upload path
                    $destinationPath = public_path('/catalog');
                    $catalog = $request->file('tnc_buyer');
                    $tnc_buyer = time().'tnc_buyer.'.$catalog->getClientOriginalExtension();
                    $catalog->move($destinationPath, $tnc_buyer);
                }
                
                
                $about_us = '';
               if ($files = $request->file('about_us')) {
                // Define upload path
                    $destinationPath = public_path('/catalog');
                    $catalog = $request->file('about_us');
                    $about_us = time().'about_us.'.$catalog->getClientOriginalExtension();
                    $catalog->move($destinationPath, $about_us);
                }
                
                
                $home = '';
               if ($files = $request->file('home')) {
                // Define upload path
                    $destinationPath = public_path('/catalog');
                    $catalog = $request->file('home');
                    $home = time().'home.'.$catalog->getClientOriginalExtension();
                    $catalog->move($destinationPath, $home);
                }
                
                
                $profile = '';
               if ($files = $request->file('profile')) {
                // Define upload path
                    $destinationPath = public_path('/catalog');
                    $catalog = $request->file('profile');
                    $profile = time().'profile.'.$catalog->getClientOriginalExtension();
                    $catalog->move($destinationPath, $profile);
                }
                
                $home_banner = '';
                $home_banners=array();
               if ($files = $request->file('home_banner')) {
                    
                   if(count($files)>0){
                 
                   foreach($files as $catalogs){
                    $j=rand();   
                // Define upload path
                    $destinationPath = public_path('/home_banner');
                    //$catalog=$catalog->getClientOriginalName();
                    $home_banner = $j.'home_banner.'.$catalogs->getClientOriginalExtension();
                    $home_banners[]=$home_banner;
                    $catalogs->move($destinationPath, $home_banner);
                     
                   }
                   }
                    
                }
                
                
                
                $data = array(
                 'email' => $request->email,
                 'name' => $request->name,
                 'address' => $request->address,
                 'phone' => $request->phone,
                 'privacy_policy' => $request->privacy_policy,
                 'hotel_url' => $request->hotel_url,
                 'branch_url' => $request->branch_url,
                 'contact_url' => $request->contact_url,
                 'website_url' => $request->website_url,
                 'home_banner' => ($home_banner=='')?$sitedata->home_banner:implode(",",$home_banners),
                 'banner_link' => $request->banner_link,
                 'tnc_supplier' => ($tnc_supplier == '') ? $sitedata->tnc_supplier : $tnc_supplier,
                 'tnc_buyer' => ($tnc_buyer == '') ? $sitedata->tnc_buyer : $tnc_buyer,
                 'about_us' =>($about_us == '') ? $sitedata->about_us : $about_us,
                 'home' => ($home == '') ? $sitedata->home : $home,
                 'profile' => ($profile == '') ? $sitedata->profile : $profile,
                 'min_order_amount' => $request->min_order_amount,
                 'max_order_dispatch_day' => $request->max_order_dispatch_day,
                  
             );
                
                $sitedata->update($data);
                 
                return redirect()->back();
            } else {
                
                
                
                $tnc_supplier = '';
               if ($files = $request->file('tnc_supplier')) {
                // Define upload path
                    $destinationPath = public_path('/catalog');
                    $catalog = $request->file('tnc_supplier');
                    $tnc_supplier = time().'tnc_supplier.'.$catalog->getClientOriginalExtension();
                    $catalog->move($destinationPath, $tnc_supplier);
                }
                
                $tnc_buyer = '';
               if ($files = $request->file('tnc_buyer')) {
                // Define upload path
                    $destinationPath = public_path('/catalog');
                    $catalog = $request->file('tnc_buyer');
                    $tnc_buyer = time().'tnc_buyer.'.$catalog->getClientOriginalExtension();
                    $catalog->move($destinationPath, $tnc_buyer);
                }
                
                
                $about_us = '';
               if ($files = $request->file('about_us')) {
                // Define upload path
                    $destinationPath = public_path('/catalog');
                    $catalog = $request->file('about_us');
                    $about_us = time().'about_us.'.$catalog->getClientOriginalExtension();
                    $catalog->move($destinationPath, $about_us);
                }
                
                
                $home = '';
               if ($files = $request->file('home')) {
                // Define upload path
                    $destinationPath = public_path('/catalog');
                    $catalog = $request->file('home');
                    $home = time().'home.'.$catalog->getClientOriginalExtension();
                    $catalog->move($destinationPath, $home);
                }
                
                
                $profile = '';
               if ($files = $request->file('profile')) {
                // Define upload path
                    $destinationPath = public_path('/catalog');
                    $catalog = $request->file('profile');
                    $profile = time().'profile.'.$catalog->getClientOriginalExtension();
                    $catalog->move($destinationPath, $profile);
                }
                
                $data = array(
                 'email' => $request->email,
                 'name' => $request->name,
                 'privacy_policy' => $request->privacy_policy,
                 'address' => $request->address,
                 'phone' => $request->phone,
                 'tnc_supplier' => $tnc_supplier,
                 'tnc_buyer' => $tnc_buyer,
                 'about_us' => $about_us,
                 'home' => $home,
                 'profile' => $profile,
                 'min_order_amount' => $request->min_order_amount,
                 'max_order_dispatch_day' => $request->max_order_dispatch_day,
                  
             );
                
                SiteInfoModel::create($data);
                return redirect()->back();
            }
        } else {
            return view('admin.site_info',compact('sitedata'));
        }
        
    }
    
    public function bank_details(Request $request){
        $sitedata = BankdetailModel::first();
        if($request->all()){
            if($sitedata){
                  
                
                
                
                $data = array(
                  
                  
                 'field1_value' => $request->field1_value,
                  
                 'field2_value' => $request->field2_value,
                  
                 'field3_value' => $request->field3_value,
                  
                 'field4_value' => $request->field4_value,
                  
                 'field5_value' => $request->field5_value,
                  
                 'field6_value' => $request->field6_value,
             );
                
                $sitedata->update($data);
                 
                return redirect()->back();
            } else {
                
                
                
                 
                
                $data = array(
                  
                  'field1_value' => $request->field1_value,
                  
                 'field2_value' => $request->field2_value,
                  
                 'field3_value' => $request->field3_value,
                  
                 'field4_value' => $request->field4_value,
                  
                 'field5_value' => $request->field5_value,
                  
                 'field6_value' => $request->field6_value,
             );
                
                BankdetailModel::create($data);
                return redirect()->back();
            }
        } else {
            return view('admin.bank_detail',compact('sitedata'));
        }
        
    }

    public function contact_details(Request $request){
        $sitedata = ContactInfoModel::where('id', 1)->first();
        $sitedata1 = ContactInfoModel::where('id', 2)->first();
        if($request->all()){
            $editData = ContactInfoModel::where('id', $request->id)->first();
            if($editData){
                $data = array(
                 'account_name' => $request->account_name,
                  
                 'account_mob' => $request->account_mob,
                  
                 'sales_name' => $request->sales_name,
                  
                 'sales_mob' => $request->sales_mob
             );
                $editData->update($data);
                return redirect()->back();
            }
        } else {
            return view('admin.contact_info',compact('sitedata','sitedata1'));
        }
        
    }
    
    
    
     public function enquiries(Request $request){
       //$enquiries =  EnquiryModel::paginate(20);
       //$enquiries =  EnquiryDataModel::query();
       $enquiries = DB::table('enquiries_data')->leftJoin('users', 'enquiries_data.user_id', '=', 'users.id')
            ->select(['enquiries_data.*', 'users.name as user_firm_name','users.veepeeuser_id']);
       if($request->enq_id != ''){
            $enquiries->where('enquiries_data.enq_id','like','%'.$request->enq_id.'%');
        }
        if($request->acc_no != ''){
            $enquiries->where('users.veepeeuser_id','like','%'.$request->acc_no.'%');
        }
       if($request->per_name != ''){
            $enquiries->where('users.name','like','%'.$request->per_name.'%');
        }
        if($request->per_mobile != ''){
            $enquiries->where('enquiries_data.per_mobile','like','%'.$request->per_mobile.'%');
        }
        if($request->enq_status != ''){
            $enquiries->where('enquiries_data.enq_status','like','%'.$request->enq_status.'%');
        }
        if($request->per_query != ''){
            $enquiries->where('enquiries_data.per_query','like','%'.$request->per_query.'%');
        }
        if($request->start_date != '' && $request->end_date != ''){
            $enquiries->where('enquiries_data.created_at','>=',date('Y-m-d 00:00:00',strtotime($request->start_date)))->where('enquiries_data.created_at','<=',date('Y-m-d 23:59:59',strtotime($request->end_date)));
         }
         if($request->export == 'download'){
            $enquiries1 = $enquiries->orderby('id','desc')->get();
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=report.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );
                $columns = array('ID','Person Name','User ID', 'Mobile', 'Query','Firm Name', 'Solution Person Name', 'Expected Solution Date','Remark','Problem','Solution','Status','Created Time');
        
                $callback = function() use ($enquiries1, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach($enquiries1 as $value) {
                        $comp_id = $value->enq_id;
                    fputcsv($file, array(
                    //    getUser($value->user_id)->name,
                    //    getUser($value->user_id)->veepeeuser_id ?? '',
                    //    getUser($value->user_id)->user_type ?? '',
                        $comp_id ?? '',
                    $value->per_name ?? '',
                    $value->user_id ?? '',
                    $value->per_mobile ?? '',
                    $value->per_query ?? '',
                    $value->firm_name ?? '',
                    $value->solv_per_name ?? '',
                    $value->expt_solv_date ?? '',
                    $value->prob_desc ?? '',
                    $value->admin_prob_desc ?? '',
                    $value->solu_desc ?? '',
                    $value->enq_status ?? '',
                    $value->created_at ?? '',
                    
                    
                    ));
                }
                fclose($file);
            };
            return \Response::stream($callback, 200, $headers);
         }
        $enquiries = $enquiries->orderBy('id', 'desc')->paginate(20);
       //print_r($enquiries); die;
       return view('admin.enquiry',compact('enquiries'));
    }

    public function editEnquiry($id){
        $enquiries =  EnquiryDataModel::where('id', $id)->first();
        if($enquiries) {
            $images = EnquiryGalleryModel::where('enquiries_id',$enquiries->id)->get();
                $Gimages=[];
                if($images){
                    foreach ($images as $key1=>$row) {
                
                        $Gimages[]=url('/images/enquiry_request/'.$row['image_name']);    
                    }
                }
                
                $enquiries['image'] = $Gimages;
        }
        return response()->json([
            'status' => 200,
            'data' => $enquiries,
        ]);
     }

     public function updateEnquiry(Request $request, $id){
        $enquiries =  EnquiryDataModel::where('id', $id)->first();
        
        if($enquiries) {
            $enquiries->solv_per_name = $request->solv_per_name;
            $enquiries->expt_solv_date = $request->expt_solv_date;
            $enquiries->prob_desc = $request->prob_desc;
            $enquiries->admin_prob_desc = $request->admin_prob_desc;
            if(!empty($request->solu_desc)){
                $enquiries->enq_status = "solved";
            }
            $enquiries->solu_desc = $request->solu_desc;
            $enquiries->save();
        }
        return response()->json([
            'status' => 200,
            'message' => "Record Updates Successfully!!",
        ]);
     }
    
    public function exportenquiries(){
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=report.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );
        
        $enquiries =  EnquiryDataModel::all();
        
    
        $columns = array('ID','Person Name','User ID', 'Mobile', 'Query','Firm Name', 'Solution Person Name', 'Expected Solution Date','Problem','Solution','Status','Created Time');
        
        $callback = function() use ($enquiries, $columns) {
          $file = fopen('php://output', 'w');
          fputcsv($file, $columns);

          foreach($enquiries as $value) {
                $comp_id = 'C-000'.$value->id;
              fputcsv($file, array(
            //    getUser($value->user_id)->name,
            //    getUser($value->user_id)->veepeeuser_id ?? '',
            //    getUser($value->user_id)->user_type ?? '',
                $comp_id ?? '',
               $value->per_name ?? '',
               $value->user_id ?? '',
               $value->per_mobile ?? '',
               $value->per_query ?? '',
               $value->firm_name ?? '',
               $value->solv_per_name ?? '',
               $value->expt_solv_date ?? '',
               $value->prob_desc ?? '',
               $value->solu_desc ?? '',
               $value->enq_status ?? '',
               $value->created_at ?? '',
               
              
             ));
          }
          fclose($file);
      };
      return \Response::stream($callback, 200, $headers);
      
    }
    
    public function edit_label(Request $request){
        $lblname = $request->lblname;
        $lblid=  $request->lblid;
        $sitedata = BankdetailModel::first(); 
            if($lblname!=''){
                  
                
                
                
                $data = array(
                  
                  
                 $lblid => $lblname,
                  
                  
             );
                
                $sitedata->update($data);
                 
               echo "success";
            }  
         else {
            echo "error";
        }
    }

    public function enquiriesData(){
        $enquiriess =  EnquiryDataModel::paginate(10);
        return view('admin.enquiry',compact('enquiriess'));
     }
}
