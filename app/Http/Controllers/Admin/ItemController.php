<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItemModel;
use App\Models\ColorModel;
use App\Models\SizeModel;
use App\Models\BrandModel;
use App\Models\ItemSizeModel;
use App\Models\ItemColorModel;
use Illuminate\Support\Facades\Validator;
use Gate;
use Str;
use DB;
use Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
 
class ItemController extends Controller
{
   // use MediaUploadingTrait;

    public function index($user_id)
    {
        abort_if(Gate::denies('item_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $brands = BrandModel::where('user_id',$user_id)->where('status',1)->get('id');
        $items = ItemModel::wherein('brand_id',$brands)->paginate(50);
        return view('admin.items.index', compact('items','user_id'));
    }

    public function create(Request $request, $user_id){
    	abort_if(Gate::denies('item_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    	
    	if($request->all()){

            $validation_rule = Validator::make($request->all(), [
                'brand_id'=>'required',
                'name'=>'required',
                'min_range' => 'required|integer',
                'max_range'=>'required|integer|gt:min_range',
            ]);
            $validation_rule->validate();
            $item = $this->store(new ItemModel,$request);    
    		//$item = ItemModel::create(['brand_id' => $request->brand_id, 'name' => $request->name, 'min_range' => $request->min_range,'max_range' => $request->max_range, 'category' => $request->category,'season' => $request->season, 'article_no' => $request->article_no, 'quantity' => $request->quantity, 'discount' => $request->discount, 'status' => 1]);

            if(!empty($request->sizes)){
                foreach ($request->sizes as $key => $value) {
                    if($value!= ''){
                        ItemSizeModel::create(['item_id' => $item->id, 'size_id' => $value]);
                    }
                }
            }

            if(!empty($request->colors)){
                foreach ($request->colors as $key => $value) {
                    if($value!= ''){
                        ItemColorModel::create(['item_id' => $item->id, 'color_id' => $value]);
                    }
                }
            }

    		return redirect()->route('admin.items.index',['user_id'=>$user_id]);
    	} else {

            
            $brands = BrandModel::where('user_id',$user_id)->where('status',1)->get();
    		$colors = ColorModel::where('status',1)->get();
            $sizes = SizeModel::where('status',1)->get();
			return view('admin.items.create',compact('colors','sizes','brands','user_id'));
    	}
    }


    public function update(Request $request, $item_id){

    	abort_if(Gate::denies('item_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $item = ItemModel::where('id',$item_id)->first();
    	if($request->all()){
            //dd($request->all());
            $validation_rule = Validator::make($request->all(), [
                'brand_id'=>'required',
                'name'=>'required',
                'min_range' => 'required|integer',
                'max_range'=>'required|integer|gt:min_range',
            ]);
            $validation_rule->validate();
            
            $item = $this->store($item,$request);
            
            //$item->update(['brand_id' => $request->brand_id, 'name' => $request->name, 'min_range' => $request->min_range, 'max_range' => $request->max_range,'category' => $request->category,'season' => $request->season, 'article_no' => $request->article_no, 'quantity' => $request->quantity,'discount' => $request->discount]);
            
            if(!empty($request->sizes)){
                ItemSizeModel::where('item_id',$item_id)->delete();
                foreach ($request->sizes as $key => $value) {
                    if($value!= ''){
                        ItemSizeModel::create(['item_id' => $item->id, 'size_id' => $value]);
                    }
                }
            }

            if(!empty($request->colors)){
                ItemColorModel::where('item_id',$item_id)->delete();
                foreach ($request->colors as $key => $value) {
                    if($value!= ''){
                        ItemColorModel::create(['item_id' => $item->id, 'color_id' => $value]);
                    }
                }
            }
            //return redirect()->route('admin.items.index',['user_id'=>$item->brands->suppliers->user_id]);
            return redirect()->route('admin.items.index',['user_id'=>$item->brands->user_id]);
        } else {

            $brands = BrandModel::where('user_id',$item->brands->user_id)->where('status',1)->get();
            $colors = ColorModel::where('status',1)->get();
            $sizes = SizeModel::where('status',1)->get();
            $selectedcolors = array();
            foreach ($item->colors as $key => $value) {
                $selectedcolors[] = $value->color_id;
            }

            $selectedsizes = array();
            foreach ($item->sizes as $key => $value) {
                $selectedsizes[] = $value->size_id;
            }
            return view('admin.items.edit',compact('colors','sizes','brands','item','selectedcolors','selectedsizes'));
        }
		
    }

   

    public function destroy($id,BrandModel $BrandModel){
        abort_if(Gate::denies('item_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $item = ItemModel::find($id);
        ItemSizeModel::where('item_id',$item->id)->delete();
        ItemColorModel::where('item_id',$item->id)->delete();
        $item->delete();
        return back();
    }
    
    public function store($item,$request){
        $item->brand_id     = $request->brand_id; 
        $item->name         = $request->name;
        $item->min_range    = $request->min_range;
        $item->max_range    = $request->max_range;
        $item->category     = $request->category;
        $item->season       = $request->season;
        $item->article_no   = $request->article_no;
        $item->quantity     = $request->quantity;
        $item->discount     = $request->discount;
        $item->save();
        return $item;
    }

}

