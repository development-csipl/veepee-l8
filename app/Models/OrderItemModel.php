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

class OrderItemModel extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;//HasMediaTrait;

    public $table = 'order_items';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
          'order_id', 'item_id', 'brand_id', 'brand_name', 'name', 'quantity', 'color', 'size', 'range','article_no'

    ];

    public function order(){
        return $this->belongsTo('App\Models\OrderModel','order_id','id');
    }
}