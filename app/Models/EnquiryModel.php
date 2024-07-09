<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class EnquiryModel extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait;

   
    public $table = 'enquiries';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id', 'category', 'name', 'phone', 'status',
    ];
 
    



    
}
