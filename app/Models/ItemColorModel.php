<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ItemColorModel extends Model 
{
  

    protected $dates = ['deleted_at'];
    public $timestamps = false;
    public $table = 'item_colors';

    protected $fillable = [
        'color_id',
        'item_id'
    ];

    public function items(){
        return $this->belongsTo('App\Models\ItemModel','item_id','id');
    }

    public function coloritem(){
        return $this->hasOne('App\Models\ColorModel','color_id','id');
    }
    
}
