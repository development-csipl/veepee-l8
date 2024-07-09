<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Gate;
use Str;
use DB;
use Auth;

class SettingController extends Controller {
    
    public $setting     = null;
    public $data        = [];
    
    public function index(){
        //abort_if(Gate::denies('settings_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $settings = Setting::paginate(20);
        
        //$this->data['days']     =   (Setting::where('name','autocancel')->first())->content; 
        //$this->data['reason']     =   (Setting::where('name','autocancel')->first())->reason;
        //return view('admin.settings.index')->with('data',$this->data);
        return view('admin.settings.index', compact('settings'));
        
    }
    
    public function operate(Request $request){
        return $this->{$request->method}($request);
    }
    public function edit($id)
    {
        //abort_if(Gate::denies('color_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		  $setting = Setting::where('id','=',$id)->first(); 
        return view('admin.settings.edit', compact('setting'));
    }
    public function update($id,Request $request){
        $setting            =   Setting::find($id);
        $setting->content   =   $request->content;
        $setting->reason   =   $request->reason;
        //dd($setting);
        if($setting->update()){
            return redirect()->back()->withSuccess('Days updated successfully.');
        }else{
            return redirect()->back()->withError('There is some issue,please try again.');
        }
    }
    public function autocancel($request){
        $setting            =   Setting::find(1);
        $setting->content   =   $request->days;
        $setting->reason   =   $request->reason;
        if($setting->save()){
            return redirect()->back()->withSuccess('Days updated successfully.');
        }else{
            return redirect()->back()->withError('There is some issue,please try again.');
        }
    }
}
