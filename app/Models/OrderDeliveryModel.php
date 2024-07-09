<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class OrderDeliveryModel extends Model implements HasMedia
{
    use  HasMediaTrait;

   
    public $table = 'order_delivery';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
         'order_id', 'no_of_case','supplier_invoice', 'price', 'transport_bill', 'bo_amount', 'bo_case_number', 'supplier_bill', 'order_form', 'eway_bill', 'credit_note', 'debit_note', 'courier_doc', 'veepee_invoice_number', 'invoice', 'supplier_firm_status', 'bilty_date_status', 'amount_status', 'supplly_station_status', 'case_status', 'reject_reason', 'remark', 'status', 'dispatch','created_by'
    ];
    
    public static function checkDuplicate($invoice,$id){
        $count = SELF::where('veepee_invoice_number',$invoice)->where('id','!=',$id)->count();
        return ($count > 1) ? true : false;
    }
    
    public static function checkSupplierDuplicate($invoice,$id){
        $count = SELF::where('supplier_invoice',$invoice)->where('id','!=',$id)->count();
        dd($count);
        return ($count > 1) ? true : false;
    }
  
    public function order(){
        return $this->belongsTo('App\Models\OrderModel','order_id','id');
    }

    public function casetracking(){
        return $this->hasMany('App\Models\OrderTrackingModel','delivery_id','id');
    }

    public function supplierbill(){
        return $this->hasMany('App\Models\SupplierBillModel','delivery_id','id');
    } 

    public function courier(){
        return $this->hasOne('App\Models\CourierModel','delivery_id','id');
    }  

    public function downloads(){
        return $this->hasOne(OrderImageDownload::class,'order_delivery_id');
    }   

    
}
