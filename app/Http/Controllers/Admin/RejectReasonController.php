<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RejectReasonModel;
use App\Http\Requests\StoreCityRequest;
use App\Http\Requests\UpdateCityRequest ;
use Gate;
use Str;
use DB;
use Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RejectReasonController extends Controller
{
    public function index(){
        abort_if(Gate::denies('reason_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $reasons = RejectReasonModel::paginate(20);
        return view('admin.reason.index', compact('reasons'));
    }

    public function create()
    {
        abort_if(Gate::denies('reason_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $states = RejectReasonModel::all();
        return view('admin.reason.create',compact('states'));
    }

    public function store(Request $request)
    {
        $productCategory = RejectReasonModel::create(
                            [
                            'usertype' => $request->usertype,
                            'reason'=>$request->reason,
                            ]
                            );
    
        return redirect()->route('admin.reject-reason.index');
    }

    public function edit($id,RejectReasonModel $RejectReasonModel)
    {
        abort_if(Gate::denies('reason_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
          $reason = RejectReasonModel::where('id','=',$id)->first();
        return view('admin.reason.edit', compact('reason'));
    }

    public function update($id,Request $request, RejectReasonModel $RejectReasonModel)
    {
    	abort_if(Gate::denies('reason_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $RejectReasonModel = RejectReasonModel::find($id);
        $RejectReasonModel->usertype = $request->usertype; 
        $RejectReasonModel->reason = $request->reason; 
        $RejectReasonModel->save();
       //dd(DB::getQueryLog());
        return redirect()->route('admin.reject-reason.index');
    }

    public function destroy($id,RejectReasonModel $RejectReasonModel)
    {
        abort_if(Gate::denies('reason_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $RejectReasonModel = RejectReasonModel::find($id);
        $RejectReasonModel->delete();
        return back();
    }
}
