<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lcobucci\JWT\Parser;
use App\Models\{OrderModel,OrderItemModel,OrderGalleryModel,OrderTrackingModel,OrderCancelModel,OrderDeliveryModel,RejectReasonModel,BuyerModel,SiteInfoModel,CourierModel};
use App\Jobs\{OrderNotification,OrderSmsNotification,SMSDeliveryNotification};
use App\User;
use Carbon\Carbon;
use DB;

class CronsController extends Controller{

    public function deleteOrders(){
        $oneYearBackDate =  date("Y-m-d",strtotime("-1 year")); 
        $order = OrderModel:: where("created_at", "<=", $oneYearBackDate)->whereNull('deleted_at')->get()->toArray();
        $updatedId = array_column($order, 'id');
        //print_r($last_names); die;
        $updateData = OrderModel::whereIn('id', $updatedId)->update([
            'deleted_at' => Carbon::now()
        ]);
        //print_r(json_encode($order)); die;
        //print_r(Carbon::now()); die;
        dd( count($order)); die;
    }


}