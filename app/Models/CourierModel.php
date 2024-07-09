<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class CourierModel extends Model
{
  

   
    public $table = 'courier_delivery';


    protected $fillable = [
        'order_id', 'delivery_id', 'courier_name', 'courier_doc', 'courier_id', 'courier_date',
    ];

 
    public function delivery(){
        return $this->belongsTo('App\Models\OrderDeliveryModel','delivery_id','id');
    }
    
}
