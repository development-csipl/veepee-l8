<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class OrderGalleryModel extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait;

    public $table = 'orders_gallery';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
          'order_id', 'image_name','image_status'

    ];

    public function order(){
        return $this->belongsTo('App\Models\OrderModel','order_id','id');
    }
}
