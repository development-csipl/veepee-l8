<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class BrandModel extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait;

   
    public $table = 'brands';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name', 
        'user_id',
        'status',
        'created_by'
    ];

    public function transport(){
        return $this->hasMany('App\Models\TransportsModels','branch_id','id');
    }
    public function items(){
        return $this->hasmany('App\Models\ItemModel','brand_id','id');
    }


    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    }


    
    
}
