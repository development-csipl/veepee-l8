<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model{

    public $table = 'role_user';

    protected $fillable = [
        'user_id', 'role_id'
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}

