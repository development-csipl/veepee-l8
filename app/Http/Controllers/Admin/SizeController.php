<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
// use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyStatesRequest;
use App\Http\Requests\StoreSizeRequest;
use App\Http\Requests\UpdateSizeRequest ;
use App\Models\SizeModel;
use Gate;
use Str;
use DB;
use Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
 
class SizeController extends Controller
{
   // use MediaUploadingTrait;

    public function index(){
        abort_if(Gate::denies('size_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
       // DB::enableQueryLog();
        $sizes = SizeModel::paginate(20);
       // dd(DB::getQueryLog());
       // dd($sizes);
        return view('admin.sizes.index', compact('sizes'));
    }

    public function create(){
		abort_if(Gate::denies('size_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		return view('admin.sizes.create', compact('countries'));
    }

    public function store(StoreSizeRequest $request)
    {
        //echo Auth::id(); exit;
		// print_r($request->all());die;
		$productCategory = SizeModel::create(
                            [
                            'name' => $request->name,
                            'status'=>1,
                            'created_by'=>Auth::id()
                            ]
                            );
	
		return redirect()->route('admin.sizes.index');
    }

    public function edit($id,SizeModel $StationModel)
    {
        abort_if(Gate::denies('size_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		  $size = SizeModel::where('id','=',$id)->first();
         
        return view('admin.sizes.edit', compact('size'));
    }

    public function update($id,UpdateSizeRequest $request, SizeModel $SizeModel)
    {
        
        $SizeModel = SizeModel::find($id);
        
        $SizeModel->update(['name' => $request->name]);
        return redirect()->route('admin.sizes.index');
    }

    public function show($id,SizeModel $SizeModel)
    {
       // echo $id; exit;
        abort_if(Gate::denies('size_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // DB::enableQueryLog();
        
           $station = $SizeModel = SizeModel::find($id);
          
           // dd($station);
        return view('admin.sizes.show', compact('station'));
    }

    public function destroy($id,SizeModel $SizeModel)
    {
        abort_if(Gate::denies('size_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $SizeModel = SizeModel::find($id);
        $SizeModel->delete();
        return back();
    }

    public function massDestroy(MassDestroyStatesRequest $request)
    {
        SizeModel::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}