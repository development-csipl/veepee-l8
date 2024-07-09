<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ColorModel extends Model
{
  

   
    public $table = 'colors';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'colorcode',
        'status',
		'created_by'
    ];

    public function user(){
        return $this->belongsTo('App\User','created_by','id');
    }

    public function itemcolor(){
        return $this->hasMany('App\Models\ColorModel','color_id','id');
    }
    
}
