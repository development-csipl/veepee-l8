<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Spatie\MediaLibrary\HasMedia\HasMedia;
// use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\Models\Media;

class CityModels extends Model implements HasMedia
{
    use SoftDeletes,InteractsWithMedia;// HasMediaTrait;

   
    public $table = 'cities';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'city_name',
        'city_code',
        'created_at',
        'updated_at',
        'deleted_at',
        'status',
		'created_by',
		'state_id'
    ];

    public function city(){
        return $this->hasMany('App\Models\StationModel','city_id','id');
    }
     
    public function branch(){
        return $this->hasMany('App\Models\BranchModel','city_id','id');
    }  
    public function suppliers(){
        return $this->hasMany('App\Models\SuppliersModels','city_id','id');
    }    
    public function state(){
        return $this->belongsTo('App\Models\StatesModels','state_id','id');
    }

    
}
