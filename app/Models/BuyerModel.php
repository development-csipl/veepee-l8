<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Spatie\MediaLibrary\HasMedia\HasMedia;
// use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\Models\Media;

class BuyerModel extends Model implements HasMedia
{
    use SoftDeletes,InteractsWithMedia;//HasMediaTrait;

   
    public $table = 'buyers';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
       'user_id', 'country_id', 'state_id', 'city_id', 'address', 'gst', 'account', 'station_id', 'owner_name', 
       'owner_contact', 'sister_firm', 'notify_email', 'notify_sms', 'notify_whatsapp', 'credit_limit', 'order_name', 'order_contact','bypass'
    ];

   
	
    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    }

    public function city(){
        return $this->belongsTo('App\Models\CityModels','city_id','id');
    }

    
}
