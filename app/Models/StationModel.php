<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Spatie\MediaLibrary\HasMedia\HasMedia;
// use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
// use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\InteractsWithMedia;

class StationModel extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;//HasMediaTrait;

   
    public $table = 'stations';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'country_id', 'state_id', 'city_id', 'status','created_by',
    ];

    public function country(){
        return $this->belongsTo('App\Models\CountryModel','country_id','id');
    }

    public function city(){
        return $this->belongsTo('App\Models\CityModels','city_id','id');
    }

    public function state(){
        return $this->belongsTo('App\Models\StatesModels','state_id','id');
    }

    public function user(){
        return $this->belongsTo('App\User','created_by','id');
    }

    
}
