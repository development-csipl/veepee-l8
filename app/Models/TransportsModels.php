<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class TransportsModels extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait;

   
    public $table = 'transports';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'branch_id',
        'transport_name',
        'gst',
        'address',
        'contact_person',
        'contact_mobile',
        'created_at',
        'updated_at',
        'deleted_at',
        'status',
		'created_by',
    ];

    public function branch(){
        return $this->belongsTo('App\Models\BranchModel','branch_id','id');
    }

    public function user(){
        return $this->belongsTo('App\User','created_by','id');
    }

    
}
