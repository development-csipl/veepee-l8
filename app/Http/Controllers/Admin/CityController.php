<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CityModels;
use App\Models\StatesModels;
use App\Http\Requests\StoreCityRequest;
use App\Http\Requests\UpdateCityRequest ;
use Gate;
use Str;
use DB;
use Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CityController extends Controller
{
       public function index(Request $request){

        abort_if(Gate::denies('city_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if($request->all()){
            
            $users1 = CityModels::where('id','!=',NULL);
            
            if($request->city_name != ''){
                $users1->where('city_name','like','%'.$request->city_name.'%');
            }

            

            if($request->status != ''){
                $users1->where('status',$request->status);
            }
            
            $users1->orderby('id','desc');
            $cities = $users1->paginate(20);
            
            //echo '<pre>';print_r($users); die;;
            return view('admin.city.index', compact('cities'));
            
        } else {
            $cities = CityModels::paginate(20);
            return view('admin.city.index', compact('cities'));
        }
    }

    public function create()
    {
        abort_if(Gate::denies('states_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        //DB::enableQueryLog();
        $states = StatesModels::all();
        //dd(DB::getQueryLog());
        return view('admin.city.create',compact('states'));
    }

    public function store(Request $request)
    {
        //echo Auth::id(); exit;
      //   echo $request->state_id;
      // print_r($request->all());die;
        $productCategory = CityModels::create(
                            [
                            'state_id' => $request->state_id,
                            'city_name'=>$request->city_name,
                            'city_code'=>$request->city_code,
                            'status'=>1,
                            'created_by'=>Auth::id()
                            ]
                            );
    
        return redirect()->route('admin.city.index');
    }

    public function edit($id,CityModels $CityModels)
    {
        abort_if(Gate::denies('city_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
          $cityEdit = CityModels::where('cities.id','=',$id)->leftJoin('states', function($join) {
                  $join->on('states.id', '=', 'cities.state_id');
                })->first(['cities.id','cities.city_code','cities.city_name','states.state_name','states.id as state_id']);
         // dd($id);
            $states = StatesModels::all();
        return view('admin.city.edit', compact('cityEdit','states'));
    }

    public function update($id,Request $request, CityModels $citymodels)
    {
        // DB::enableQueryLog();
        // echo $id;
       //dd($request->all());
        $citymodels = CityModels::find($id);
        $citymodels->state_id = $request->state_id; 
        $citymodels->city_name = $request->city_name; 
        $citymodels->city_code = $request->city_code; 
        $citymodels->save();
       //dd(DB::getQueryLog());
        return redirect()->route('admin.city.index');
    }

    public function show($id,CityModels $CityModels)
    {
       // echo $id; exit;
        abort_if(Gate::denies('city_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // DB::enableQueryLog();
               $cities = CityModels::leftJoin('users', function($join) {
                  $join->on('users.id', '=', 'cities.created_by');
                })->leftJoin('states', function($join) {
                  $join->on('states.id', '=', 'cities.state_id');
                })->where('cities.id','=',$id)->first(['cities.id','cities.city_name','cities.city_code','cities.status','cities.created_at','users.name','states.state_name']);
         
          
           // dd($states);
        return view('admin.city.show', compact('cities'));
    }

    public function destroy($id,CityModels $CityModels)
    {
        abort_if(Gate::denies('city_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $CityModels = CityModels::find($id);
        $CityModels->delete();
        return back();
    }

    public function massDestroy(MassDestroyStatesRequest $request)
    {
print_r(request('ids'));
      echo "sdfsa";exit;
        CityModels::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
