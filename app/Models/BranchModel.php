<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class BranchModel extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait;

   
    public $table = 'branches';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name', 
        'country_id', 
        'state_id', 
        'city_id', 
        'address', 
        'map_address', 
        'stay_facility', 
        'landline_no', 
        'mobile_no', 
        'weekly_off', 
        'status',
        'created_by'
    ];
 
    public function transport(){
        return $this->hasMany('App\Models\TransportsModels','branch_id','id');
    }
    public function country(){
        return $this->belongsTo('App\Models\CountryModel','country_id','id');
    }
    public function state(){
        return $this->belongsTo('App\Models\StatesModels','state_id','id');
    } 
    public function city(){
        return $this->belongsTo('App\Models\CityModels','city_id','id');
    }
    public function suppliers(){
        return $this->hasMany('App\Models\SuppliersModels','branch_id','id');
    }



    
}
