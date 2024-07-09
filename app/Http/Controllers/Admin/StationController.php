<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
// use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyStatesRequest;
use App\Http\Requests\StoreStationRequest;
use App\Http\Requests\UpdateStateRequest ;
use App\Models\StationModel;
use App\Models\CountryModel;
use App\Models\CityModels;
use App\Models\StatesModels;
use Gate;
use Str;
use DB;
use Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
 
class StationController extends Controller
{
   // use MediaUploadingTrait;

    public function index(){
        abort_if(Gate::denies('station_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
       // DB::enableQueryLog();
        $stations = StationModel::paginate(20);
       // dd(DB::getQueryLog());
       // dd($stations);
        return view('admin.stations.index', compact('stations'));
    }

    public function create()
    {
		abort_if(Gate::denies('station_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$countries = CountryModel::where('status',1)->get();
		return view('admin.stations.create', compact('countries'));
    }

    public function store(StoreStationRequest $request)
    {
        //echo Auth::id(); exit;
		// print_r($request->all());die;
		$productCategory = StationModel::create(
                            ['country_id'  => $request->country_id, 
                            'state_id' => $request->state_id, 
                            'city_id' => $request->city_id,
                            'status'=>1,
                            'created_by'=>Auth::id()
                            ]
                            );
	
		return redirect()->route('admin.station.index');
    }

    public function edit($id,StationModel $StationModel)
    {
        abort_if(Gate::denies('station_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		  $station = StationModel::where('id','=',$id)->first();
          $countries  = CountryModel::where('status',1)->get();
          $states = StatesModels::where('country_id',$station->country_id)->get();
          $cities = CityModels::where('state_id',$station->state_id)->get();
         // dd($id);
        return view('admin.stations.edit', compact('station','states','cities','countries'));
    }

    public function update($id,UpdateStateRequest $request, StationModel $StationModel)
    {
        
        $StationModel = StationModel::find($id);
        $StationModel->fill([$request->all()]); 
        $StationModel->save();
        return redirect()->route('admin.station.index');
    }

    public function show($id,StationModel $StationModel)
    {
       // echo $id; exit;
        abort_if(Gate::denies('station_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // DB::enableQueryLog();
        
           $station = $StationModel = StationModel::find($id);
          
           // dd($station);
        return view('admin.stations.show', compact('station'));
    }

    public function destroy($id,StationModel $StationModel)
    {
        abort_if(Gate::denies('state_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $StationModel = StationModel::find($id);
        $StationModel->delete();
        return back();
    }

    public function massDestroy(MassDestroyStatesRequest $request)
    {
print_r(request('ids'));
      echo "sdfsa";exit;
        StationModel::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
