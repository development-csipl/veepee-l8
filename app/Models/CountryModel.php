<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
//use Spatie\MediaLibrary\HasMedia\HasMedia;
//use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\InteractsWithMedia;


class CountryModel extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;//HasMediaTrait;

   
    public $table = 'countries';

    protected $fillable = [
        'country',
        'status'
    ];

    // public function country(){
    //     return $this->hasMany('App\Models\StationModel','country_id','id');
    // }
 
    public function branch(){
        return $this->hasOne('App\Models\BranchModel','country_id','id');
    }
    
    public function supplier(){
        return $this->hasMany('App\Models\SuppliersModels','country_id','id');
    }
    public function state(){
        return $this->hasmany('App\Models\StatesModels','country_id','id');
    }




    
}
