<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
//use Spatie\MediaLibrary\HasMedia\HasMedia;
//use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\Models\Media;

class OrderModel extends Model implements HasMedia
{
    use SoftDeletes,InteractsWithMedia;//, HasMediaTrait;

    public $table = 'orders';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
          'vporder_id',
          'buyer_id',
          'supplier_id',
          'branch_id',
          'brand_id',
          'marka',
          'transport_one_id',
          'transport_two_id',
          'supply_start_date',
          'orderlast_date',
          'station',
          'pkt_cases',
          'remaining_pkt',
          'order_amount',
          'spend_order_amount',
          'supplier_accept',
          'buyer_accept',
          'order_form',
          'supplier_bill',
          'transport_bill',
          'eway_bill',
          'credit_note',
          'debit_note',
          'courier_doc',
          'veepee_invoice_number',
          'invoice',
          'status',
          'supplier_status_flag',
          'order_otp',
          'order_date',
          'merchandiser_name',
          'reason',
          'users_id',
          'face',
          'color_size_range_etc',
          'optional',
          'case_no',
          'amount',
          'remark_by_customer',
          'created_at',
          'order_by',
          
    ];

    public function supplier(){
        return $this->belongsTo('App\User','supplier_id','id');
    }

    public function buyer(){
        return $this->belongsTo('App\User','buyer_id','id');
    }

    public function orderitems(){
        return $this->hasMany('App\Models\OrderItemModel','order_id','id');
    }
    
  

    public function orderimages(){
        return $this->hasMany('App\Models\OrderGalleryModel','order_id','id');
    }

    public function ordertracking(){
        return $this->hasMany('App\Models\OrderTrackingModel','order_id','id');
    }

    public function orderdelivery(){
        return $this->hasMany('App\Models\OrderDeliveryModel','order_id','id');
    }


    public function ordercancel(){
        return $this->hasOne('App\Models\OrderCancelModel','order_id','id');
    }

}