<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class BankdetailModel extends Model
{

    public $timestamps = false;
    public $table = 'bank_detail';
    protected $fillable = [
        'field1','field1_value','field2','field2_value','field3','field3_value',
        'field4','field4_value','field5','field5_value','field6','field6_value'
    ];

    
}
