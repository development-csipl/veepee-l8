<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class OrderTrackingModel extends Model
{

    public $timestamps = false;
    public $table = 'order_tracking';
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = [
         'order_id', 'delivery_id', 'event', 'user_id', 'status', 'flag',
    ];


    public function items(){
        return $this->belongsTo('App\Models\ItemModel','item_id','id');
    }

    public function itemsize(){
        return $this->belongsTo('App\Models\SizeModel','size_id','id');
    }

    public function delivery(){
        return $this->belongsTo('App\Models\OrderDeliveryModel','delivery_id','id');
    }
    
}
