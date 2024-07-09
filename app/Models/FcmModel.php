<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class FcmModel extends Model
{

    public $timestamps = false;
    public $table = 'user_fcm_data';
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = [
        'user_id', 'device_fcm','device_id', 'device_type','is_login',
    ];


    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    }


}
