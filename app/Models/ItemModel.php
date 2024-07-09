<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;
//use Spatie\MediaLibrary\HasMedia\HasMedia;
//use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
//use Spatie\MediaLibrary\Models\Media;

class ItemModel extends Model
{
   

   
    public $table = 'items';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'brand_id', 'name', 'min_range', 'max_range', 'article_no', 'quantity', 'status','discount','order_id'
    ];

    public function brands(){
        return $this->belongsTo('App\Models\BrandModel','brand_id','id');
    }

    public function colors(){
        return $this->hasMany('App\Models\ItemColorModel','item_id','id');
    }

    public function sizes(){
        return $this->hasMany('App\Models\ItemSizeModel','item_id','id');
    }
}
