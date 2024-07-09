<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class StatesModels extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait;

    
    public $table = 'states';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'state_name',
        'country_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'status',
		'created_by',
    ];

    public function station(){
        return $this->hasMany('App\Models\StationModel','state_id','id');
    }

    public function cities(){
        return $this->hasMany('App\Models\CityModels','state_id','id');
    }
    
    public function branch(){
        return $this->hasMany('App\Models\BranchModel','state_id','id');
    }    
    public function supllier(){
        return $this->hasMany('App\Models\SuppliersModels','state_id','id');
    }
    public function country(){
        return $this->belongsTo('App\Models\CountryModel','country_id','id');
    }

    
}
