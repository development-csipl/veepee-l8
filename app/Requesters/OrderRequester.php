<?php

namespace App\Requesters;

use App\Models\{OrderModel,TransportsModels,OrderItemModel,ItemModel,BrandModel,OrderTrackingModel,OrderDeliveryModel,SupplierBillModel,OrderGalleryModel,CourierModel,OrderCancelModel,BranchModel};
use App\User;

class OrderRequester {
  
    public $csipl    =   '';
    public $itmes    =   '';
    
    public function __construct(){
        
        $this->csipl   =  new \stdClass();
        
    }
    
    public function order($id){
        
        $this->order          =   OrderModel::find($id);
        $this->csipl->order   =   $this->order;
        $this->csipl->buyer   =   User::find($this->order->buyer_id);
        $this->csipl->suplier =   User::find($this->order->supplier_id);
        $this->csipl->branch  =   BranchModel::find($this->order->branch_id);
        $this->csipl->brand   =   BrandModel::where('id',$this->order->brand_id)->first();
        $this->csipl->oitem   =   OrderItemModel::where('order_id',$this->order->id)->first();
        $this->csipl->items   =   ItemModel::whereIn('id',OrderItemModel::where('order_id',$this->order->id)->get()->pluck('item_id')->toArray())->get();
        $this->csipl->tnsp1   =   TransportsModels::find($this->order->transport_one_id);
        $this->csipl->tnsp2   =   TransportsModels::find($this->order->transport_two_id);
        $this->csipl->gallery =   OrderGalleryModel::where('order_id',$this->order->id)->get();
        $this->csipl->FPath   =   asset('/images/order_request/');
        return $this->csipl;
        
    }
    
}
