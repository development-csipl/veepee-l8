<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
// use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyStatesRequest;
use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest  ;
use App\Models\StatesModels;
use App\Models\BranchModel;
use App\Models\CountryModel;
use App\Models\CityModels;
use Gate;
use Str;
use DB;
use Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
 
class BranchController  extends Controller
{
   // use MediaUploadingTrait;

   

    public function index()
    {

        abort_if(Gate::denies('branchs'), Response::HTTP_FORBIDDEN, '403 Forbidden');
       // DB::enableQueryLog();
        $branches = BranchModel::orderby('created_at','desc')->paginate(20);
        $title = 'Branch List';
        
       // dd(DB::getQueryLog());
       // dd($branches);
        return view('admin.branches.index', compact('branches','title'));
    }

    public function create()
    {
		abort_if(Gate::denies('branch_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		//DB::enableQueryLog();
		//$parentcategory = StatesModels::where('parent_id','=',0)->get();
		//dd(DB::getQueryLog());
        $data = array(
            'states' => StatesModels::all(),
            'cities' => CityModels::all(),
            'countries' => CountryModel::where('status',1)->get(['country','id']),
            'title' => 'Create Branch'

        );

		return view('admin.branches.create',$data);
    }

    public function store(StoreBranchRequest $request)
    {
        //echo Auth::id(); exit;
		// print_r($request->all());die;
        $user_id =  Auth::user()->id;
		$productCategory = BranchModel::create(
                            ['name' => $request->name, 
                            'country_id' => $request->country_id, 
                            'state_id' => $request->state_id, 
                            'city_id' => $request->city_id, 
                            'address' => $request->address, 
                            'map_address' => $request->map_address, 
                            'stay_facility' => $request->stay_facility, 
                            'landline_no' => $request->landline_no, 
                            'mobile_no' => $request->mobile_no, 
                            'weekly_off' => $request->weekly_off, 
                            'status' => $request->status ?? 1,
                            'created_by' => $user_id
                            ]
                            );
	
		return redirect()->route('admin.branches.index');
    }

    public function edit($id,BranchModel $StatesModels)
    {
        abort_if(Gate::denies('branch_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		  
         // dd($id);
          $data = array(
            'states' => StatesModels::all(),
            'cities' => CityModels::all(),
            'countries' => CountryModel::where('status',1)->get(['country','id']),
            'title' => 'Create Branch',
            'branch' => BranchModel::where('id','=',$id)->first()

        );
        return view('admin.branches.edit', $data);
    }

    public function update($id,UpdateBranchRequest $request, BranchModel $branchmodel){
        $branchmodel = BranchModel::find($id);
        $branchmodel->update(['name' => $request->name, 
                            'country_id' => $request->country_id, 
                            'state_id' => $request->state_id, 
                            'city_id' => $request->city_id, 
                            'address' => $request->address, 
                            'map_address' => $request->map_address, 
                            'stay_facility' => $request->stay_facility, 
                            'landline_no' => $request->landline_no, 
                            'mobile_no' => $request->mobile_no, 
                            'weekly_off' => $request->weekly_off, 
                            'status' => $request->status ?? 1,
                            ]); 
        
        return redirect()->route('admin.branches.index');
    }

    public function show($id,StatesModels $StatesModels)
    {
       // echo $id; exit;
        abort_if(Gate::denies('branch_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // DB::enableQueryLog();
        
           $branch =  $branchmodel = BranchModel::find($id);;
          
           // dd($branch);
        return view('admin.branches.show', compact('branch'));
    }

    public function destroy($id,StatesModels $StatesModels)
    {
        abort_if(Gate::denies('branch_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $branchmodels = BranchModel::find($id);
        $branchmodels->delete();
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
