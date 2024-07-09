<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
// use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyStatesRequest;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest ;
use App\Models\BrandModel;
use Gate;
use Str;
use DB;
use Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
 
class BrandController extends Controller
{
   // use MediaUploadingTrait;

    public function index($user_id)
    {
        abort_if(Gate::denies('brands_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
       // DB::enableQueryLog();
        $brands = BrandModel::where('user_id',$user_id)->paginate(50);
        return view('admin.brands.index', compact('brands','user_id'));
    }

    public function create(Request $request, $user_id){
    	abort_if(Gate::denies('brands_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    	if($request->all()){
    		BrandModel::create(['name' => $request->name, 'user_id' => $user_id, 'status' => 1 , 'created_by' => Auth::id()]);
    		return redirect()->route('admin.brands.index',['user_id'=>$user_id]);
    	} else {
    		
			return view('admin.brands.create',compact('user_id'));
    	}
		
    }


    public function update(Request $request, $id){

    	abort_if(Gate::denies('brands_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    	$brand = BrandModel::where('id',$id)->first();
    	if($request->all()){
    		$brand->update(['name' => $request->name]);
    		return redirect()->route('admin.brands.index',['user_id'=>$brand->user_id]);
    	} else {
        	return view('admin.brands.edit', compact('brand'));
    	}
		
    }

    public function show($id,BrandModel $BrandModel)
    {
       // echo $id; exit;
        abort_if(Gate::denies('brands_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
           $brands = BrandModel::leftJoin('users', function($join) {
                  $join->on('users.id', '=', 'states.created_by');
                })->where('states.id','=',$id)->first(['states.id','states.brands_name','states.status','states.created_at','users.name']);
          
           // dd($brands);
        return view('admin.brands.show', compact('states'));
    }

    public function destroy($id,BrandModel $BrandModel){
        abort_if(Gate::denies('brands_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $BrandModel = BrandModel::find($id);
        $BrandModel->delete();
        return back();
    }

}

