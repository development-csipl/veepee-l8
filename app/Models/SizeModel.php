<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class SizeModel extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait;

    public $table = 'sizes';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'status',
		'created_by',
    ];

    public function sizeitem(){
        return $this->hasMany('App\Models\SizeModel','size_id','id');
    }

    public function user(){
        return $this->belongsTo('App\User','created_by','id');
    }

    
}