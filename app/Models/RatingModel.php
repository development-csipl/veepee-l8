<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class RatingModel extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait;

   
    public $table = 'ratings';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
         'user_id', 'brand_id', 'order_id', 'rating', 'status', 
    ];


    
}
