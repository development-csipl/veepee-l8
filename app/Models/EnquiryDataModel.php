<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class EnquiryDataModel extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait;

   
    public $table = 'enquiries_data';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id','per_name','per_mobile','per_query', 'firm_name','per_attachment','solv_per_name','expt_solv_date','prob_desc','solu_desc','enq_status','satisfy','enq_id','admin_prob_desc'
    ];
    
 

    
}
