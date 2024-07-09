<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{OrderModel,TransportsModels,OrderItemModel,ItemModel,BrandModel,OrderTrackingModel,OrderDeliveryModel,SuppliersModels,SupplierBillModel,OrderGalleryModel,CourierModel,OrderCancelModel,BranchModel,OrderImageDownload,RejectReasonModel,BuyerModel};
use App\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Gate;
use Auth;
use DB;

class TestController extends Controller{
 public function testcheckdeliveryorder(Request $request){
        //dd($request->order_id);
        $user_id = Auth::id();
        $order_id=$request->order_id;
        $delivered_check = OrderDeliveryModel::where('order_id',$order_id)->first();
        $order = OrderModel::where('id',$order_id)->first();
        $RPCase = $order->pkt_cases - (delivered_cases_sum($order->id));
        $RPAmount = $order->order_amount - remaing_amount($order->id);
         

        if($delivered_check && $order){

            //echo "delivered_check,order"; die;

            // $data = array(
            //         'price' => $request->amount,
            //         'bo_amount' => $request->amount,
            //         'bo_case_number' => $request->quantity,
            //         'no_of_case' => $request->quantity,
            //         //'supplier_invoice' => $request->supplier_invoice_no,
                         
            //  );
            // $delivered_check->update($data);
            if(($order->pkt_cases == delivered_cases_sum($order->id)) OR (($order->order_amount - remaing_amount($order->id)) <= 10000)){
                // if($order->pkt_cases == delivered_cases_sum($order->id)){
                print_r("Completed");
                print_r(delivered_cases_sum($order->id)); //die;
                $objOrder = OrderDeliveryModel::select('id')->where('price','0')->where('order_id',$order_id)->get();
                dd($objOrder->isEmpty());
                if($objOrder->isEmpty()){ dd('if condition');
                    $order->update(['status'=>'Completed']);
                }
 
                 
            }else{
                print_r("Confirm");
                //print_r($order->pkt_cases); die;
                if( !OrderDeliveryModel::where('order_id',$order_id)->whereNull('status')->count()){
                    $order = OrderModel::select('id')->where('id',$order_id)->first();

                    $objOrder = OrderDeliveryModel::select('id')->where('price','0')->where('order_id',$order_id)->get();
                    if($objOrder->isEmpty()){
                        //$order->status = "Confirm";
                    }

                    //$order->save();
                }
                //$order->update(['status'=>'Confirm']);
            }
            $result['status'] = true;
            $result['message'] = "Data updated successfully";
            return response()->json(['data' => $result]);
            //return json_encode($result);
        }else{
            $result['status'] = false;
            $result['message'] = "Data not found";
            return response()->json(['data' => $result]);
        }
        
    }
}