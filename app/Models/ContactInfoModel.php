<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ContactInfoModel extends Model
{

    public $timestamps = false;
    public $table = 'contact_info';
    protected $fillable = [
        'type','account_name','account_mob','sales_name','sales_mob'
    ];

    
}
