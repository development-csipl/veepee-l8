<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\StoreTransportsRequest;
use App\Http\Requests\UpdateTransportsRequest;
use App\Models\TransportsModels;
use App\Models\BranchModel;
use Gate;
use Str;
use DB;
use Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TransportsController extends Controller{
   
    public function index(Request $request){

        abort_if(Gate::denies('transport_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $branches = BranchModel::where('status',1)->get(['name','id']);
        if($request->all()){
           // print_r($request->all()); die;
            //$users1 = User::join('buyers', 'users.id', '=', 'buyers.user_id')->select('users.id as buyer_id','users.*', 'buyers.*');
            $users1 = TransportsModels::where('id','!=',NULL);
           // print_r(); die;
            if($request->transport_name != ''){
                $users1->where('transport_name','like','%'.$request->transport_name.'%');
            }

            if($request->contact_person != ''){
               // print_r($request->email); die;
                $users1->where('contact_person',$request->contact_person);
            }
            
            if($request->contact_mobile != ''){
               // print_r($request->email); die;
                $users1->where('contact_mobile',$request->contact_mobile);
            }
            
            if($request->branch_id != ''){
               // print_r($request->email); die;
                $users1->where('branch_id',$request->branch_id);
            }

            if($request->address != ''){

                $users1->where('address',$request->address);
            }

            if($request->gst != ''){
                $users1->where('gst',$request->gst);
            }

            if($request->status != ''){
                $users1->where('status',$request->status);
            }
            $users1->orderby('created_at','desc');
            $transports = $users1->paginate(20);
            return view('admin.transports.index', compact('transports','branches'));
        } else {
            $transports = TransportsModels::orderBy('id','DESC')->paginate(20);
            return view('admin.transports.index', compact('transports','branches'));
        }
    }

    public function create(){
        abort_if(Gate::denies('transport_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        //DB::enableQueryLog();
        $branches = BranchModel::all();
       
        return view('admin.transports.create',compact('branches'));
    }

    public function store(StoreTransportsRequest $request){
        $productCategory = TransportsModels::create(
                            [
                            'branch_id' => $request->branch_id,
                            'transport_name'=>$request->transport_name,
                            'gst'=>$request->gst,
                            'address'=>$request->address,
                            'contact_person'=>$request->contact_person,
                            'contact_mobile'=>$request->contact_mobile,
                            'status'=>1,
                            'created_by'=>Auth::id()
                            ]
                            );
  //  dd(DB::getQueryLog());
        return redirect()->route('admin.transports.index');
    }

    public function edit($id,TransportsModels $TransportsModels)
    {
        abort_if(Gate::denies('transport_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $transports = TransportsModels::find($id);
        $branches = BranchModel::all();

        return view('admin.transports.edit', compact('branches','transports'));
    }

    public function update($id,UpdateTransportsRequest $request)
    {
       DB::enableQueryLog();
        // echo $id;
        //dd($request->all());
        $transportsmodels = TransportsModels::find($id);
        $transportsmodels->branch_id = $request->branch_id;
        $transportsmodels->transport_name =$request->transport_name;
        $transportsmodels->gst = $request->gst;
        $transportsmodels->address =$request->address;
        $transportsmodels->contact_person =$request->contact_person;
        $transportsmodels->contact_mobile =$request->contact_mobile;
        $transportsmodels->save();      


        // $transportsmodels->fill([$request->all()]); 
        // $transportsmodels->save();
       // dd(DB::getQueryLog());
        return redirect()->route('admin.transports.index');
    }

    public function show($id,TransportsModels $TransportsModels)
    {
       // echo $id; exit;
        abort_if(Gate::denies('transport_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // DB::enableQueryLog();
               $transports = TransportsModels::leftJoin('users', function($join) {
                  $join->on('users.id', '=', 'transports.created_by');
                })->where('transports.id','=',$id)->first(['transports.*','users.name']);
         
          
           // dd($states);
        return view('admin.transports.show', compact('transports'));
    }

    public function destroy($id,TransportsModels $TransportsModels)
    {
        abort_if(Gate::denies('transport_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $TransportsModels = TransportsModels::find($id);
        $TransportsModels->delete();
        return back();
    }

    public function massDestroy(MassDestroyStatesRequest $request)
    {
    print_r(request('ids'));
      echo "sdfsa";exit;
        CityModels::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
   
    public function ajaxTransport(Request $request){

        $transports = TransportsModels::where('branch_id',$request->id)->where('status',1)->orderBy('transport_name','ASC')->get();
        
        foreach($transports as $transport ){
           $output[$transport->id] = $transport->transport_name;
        }
        return $output;
        
    }
}
