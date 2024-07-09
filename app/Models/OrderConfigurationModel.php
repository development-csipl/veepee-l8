<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderConfigurationModel extends Model {
    public $table = 'order_configuration';

    protected $dates = [
        'created_at',
        'updated_at'
        
    ];
    
}