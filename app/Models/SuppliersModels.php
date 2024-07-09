<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class SuppliersModels extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait;

   
    public $table = 'suppliers';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'country_id',
        'gst',
        'state_id',
        'city_id',
        'address',
        'account',
        'station_id',
        'owner_name',
        'owner_contact',
        'order_name',
        'order_contact',
        'sister_firm',
        'category',
        'market',
        'design',
        'pattern',
        'min_quantity',
        'packing',
        'fabric',
        'discount',
        'branch_id',
        'notify_email',
        'notify_sms',
        'notify_whatsapp',
        'catalog',
        'created_at',
        'updated_at',
        'deleted_at',
        'status',
		'created_by',
    ];

    public function branch(){
        return $this->belongsTo('App\Models\BranchModel','branch_id','id');
    }
	
    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    }

    public function abc(){
        return $this->belongsTo('App\Models\CountryModel','country_id','id');
    }

    public function state(){
        return $this->belongsTo('App\Models\StatesModels','state_id','id');
    }
    
    public function city(){
        return $this->belongsTo('App\Models\CityModels','city_id','id');
    }
    
    public function brands(){
        return $this->hasMany('App\Models\BrandModel','user_id','user_id');
    } 

    public function branchs(){
        return $this->belongsTo(CityModels::class,'branch_id');
    }
	


    
}
