<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierBillModel extends Model{
public $timestamps = false;
    public $table = 'supplier_delivery_image';


    protected $fillable = [
       'delivery_id', 'order_id', 'image'
    ];

   
	
    public function delivery(){
        return $this->belongsTo('App\Models\OrderDeliveryModel','delivery_id','id');
    }   
}

