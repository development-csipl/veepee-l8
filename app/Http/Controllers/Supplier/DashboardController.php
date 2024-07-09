<?php

namespace App\Http\Controllers\Supplier;
use Session;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EnquiryModel;
use Auth;

class DashboardController extends Controller
{

    public function index()
    {
    	if(Auth::check()){
			return view('supplier.dashboard');
    	} else {
    		return redirect(route('supplier.login'));
    	}
        
    }
    
    public function create_enquiries(Request $request){
      
        if($request->all()){ 
                $data = array(
                 'user_id' => Auth::user()->id,
                 'category' => $request->category,
                 'content' => $request->content,
                 'reply'  =>'Your feedback has been submitted successfully. We will get back to you soon.',
                 
             );
                
                EnquiryModel::create($data);
               return redirect()->back()->with('success','Your feedback has been submitted successfully. We will get back to you soon.');
        } else {
            return view('supplier.enquiry');
        }
        
    }
    
     public function supplierlogout(){
         

        $user = Auth::user();
        Session::flush();
       
            return redirect()->intended(route('supplier.login'));
        
    }
}
