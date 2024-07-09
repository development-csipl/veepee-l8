<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
// use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyStatesRequest;
use App\Http\Requests\StoreStateRequest;
use App\Http\Requests\UpdateStateRequest ;
use App\Models\StatesModels;
use App\Models\CountryModel;
use Gate;
use Str;
use DB;
use Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
 
class StatesController extends Controller
{
   // use MediaUploadingTrait;

    public function index(){
        abort_if(Gate::denies('state_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $states     = StatesModels::paginate(50);
        $countries  = CountryModel::where('status',1)->get();
        return view('admin.states.index', compact('states','countries'));
    }

    public function create()
    {
		abort_if(Gate::denies('states_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$countries = CountryModel::where('status',1)->get();
		return view('admin.states.create', compact('countries'));
    }

    public function store(StoreStateRequest $request)
    {
        //echo Auth::id(); exit;
		// print_r($request->all());die;
		$productCategory = StatesModels::create(
                            ['state_name'=>$request->state_name,
                            'country_id'=>$request->country_id,
                            'status'=>1,
                            'created_by'=>Auth::id()
                            ]
                            );
	
		return redirect()->route('admin.states.index');
    }

    public function edit($id,StatesModels $StatesModels)
    {
        abort_if(Gate::denies('state_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		  $stateEdit = StatesModels::where('id','=',$id)->first();
          $countries = CountryModel::where('status',1)->get();
        return view('admin.states.edit', compact('stateEdit','countries'));
    }

    public function update($id,UpdateStateRequest $request, StatesModels $StatesModels)
    {
        
        $StatesModels = StatesModels::where('id',$id)->update(
                            ['state_name'=>$request->state_name,
                                'country_id'=>$request->country_id,
                            ]);
        
        return redirect()->route('admin.states.index');
    }

    public function show($id,StatesModels $StatesModels)
    {
       // echo $id; exit;
        abort_if(Gate::denies('state_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // DB::enableQueryLog();
        
           $states = StatesModels::leftJoin('users', function($join) {
                  $join->on('users.id', '=', 'states.created_by');
                })->where('states.id','=',$id)->first(['states.id','states.state_name','states.status','states.created_at','users.name']);
          
           // dd($states);
        return view('admin.states.show', compact('states'));
    }

    public function destroy($id,StatesModels $StatesModels)
    {
        abort_if(Gate::denies('state_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $StatesModels = StatesModels::find($id);
        $StatesModels->delete();
        return back();
    }

    public function massDestroy(MassDestroyStatesRequest $request)
    {
print_r(request('ids'));
      echo "sdfsa";exit;
        StatesModels::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
