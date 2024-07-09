<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class OrderCancelModel extends Model
{

    public $timestamps = false;
    public $table = 'order_cancel';
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = [
        'order_id', 'supplier_id','reason', 'status', 'cancelled_by',
    ];

    public function orders(){
        return $this->belongsTo('App\Models\OrderModel','order_id','id');
    }


}
