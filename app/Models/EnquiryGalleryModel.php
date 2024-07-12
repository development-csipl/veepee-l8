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

class EnquiryGalleryModel extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;//HasMediaTrait;

    public $table = 'enquiries_gallery';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
          'enquiries_id', 'image_name',

    ];

    public function enquiry(){
        return $this->belongsTo('App\Models\EnquiryDataModel','enquiries_id','id');
    }
}
