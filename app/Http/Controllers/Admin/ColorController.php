<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
// use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyStatesRequest;
use App\Http\Requests\StoreColorRequest;
use App\Http\Requests\UpdateColorRequest ;
use App\Models\ColorModel;
use Gate;
use Str;
use DB;
use Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
 
class ColorController extends Controller
{
   // use MediaUploadingTrait;

    public function index(){
        abort_if(Gate::denies('color_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
       // DB::enableQueryLog();
        $colors = ColorModel::paginate(20);
       // dd(DB::getQueryLog());
       // dd($Colors);
        return view('admin.colors.index', compact('colors'));
    }

    public function create(){
		abort_if(Gate::denies('color_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		return view('admin.colors.create', compact('countries'));
    }

    public function store(StoreColorRequest $request){
		//print_r($request->all());die;
		$productCategory = ColorModel::create(
                            [
                            'name' => $request->name,
                            'colorcode' => $request->colorcode,
                            'status'=>1,
                            'created_by'=>Auth::id()
                            ]
                            );
	
		return redirect()->route('admin.colors.index');
    }

    public function edit($id)
    {
        abort_if(Gate::denies('color_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		  $color = ColorModel::where('id','=',$id)->first();
         
        return view('admin.colors.edit', compact('color'));
    }

    public function update($id,UpdateColorRequest $request){
        
        $ColorModel = ColorModel::find($id);
        $ColorModel->update(['name' => $request->name, 
                            'colorcode' => $request->colorcode
                        ]);
        return redirect()->route('admin.colors.index');
    }

    public function show($id,ColorModel $ColorModel)
    {
       // echo $id; exit;
        abort_if(Gate::denies('color_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // DB::enableQueryLog();
        
            $color = ColorModel::find($id);
          
           // dd($station);
        return view('admin.colors.show', compact('color'));
    }

    public function destroy($id,ColorModel $ColorModel)
    {
        abort_if(Gate::denies('color_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $ColorModel = ColorModel::find($id);
        $ColorModel->delete();
        return back();
    }

    public function massDestroy(MassDestroyStatesRequest $request)
    {
        ColorModel::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}