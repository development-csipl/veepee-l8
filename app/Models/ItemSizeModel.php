<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ItemSizeModel extends Model
{

    public $timestamps = false;
    public $table = 'item_sizes';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'item_id',
        'size_id'
    ];


    public function items(){
        return $this->belongsTo('App\Models\ItemModel','item_id','id');
    }

    public function itemsize(){
        return $this->belongsTo('App\Models\SizeModel','size_id','id');
    }
    
}
