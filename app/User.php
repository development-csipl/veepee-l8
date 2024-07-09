<?php

namespace App;

use Carbon\Carbon;
use Hash;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use SoftDeletes, Notifiable, HasApiTokens;

    public $table = 'users';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at',
        'email_verified_at',
    ];

    protected $fillable = [
        'name', 'email', 'number', 'gender','gst','otp', 'veepeeuser_id', 'profile_pic', 'device_type', 'device_id', 'device_fcm', 'email_verified_at', 'password', 'remember_token', 'user_type', 'branch_id', 'status','block', 'password_updated'
    ];

    public function getEmailVerifiedAtAttribute($value)
    {
        //return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setEmailVerifiedAtAttribute($value)
    {
        $this->attributes['email_verified_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function transports(){
        return $this->hasMany('App\Models\TransportsModels','created_by','id');
    }

    public function sizes(){
        return $this->hasMany('App\Models\SizeModel','created_by','id');
    }

    public function colors(){
        return $this->hasMany('App\Models\ColorModel','created_by','id');
    }

    public function supplier(){
        return $this->hasOne('App\Models\SuppliersModels','user_id','id');
    }

    public function buyer(){
        return $this->hasOne('App\Models\BuyerModel','user_id','id');
    }

    public function brand(){
        return $this->hasMany('App\Models\BrandModel','user_id','id');
    }


    public function supplierorder(){
        return $this->hasMany('App\Models\OrderModel','supplier_id','id');
    }

    public function buyerorder(){
        return $this->hasMany('App\Models\OrderModel','buyer_id','id');
    }

    public function userrole(){
        return $this->hasMany(Role::class);
    }


    public function fcm(){
        return $this->hasMany('App\Models\FcmModel','user_id','id');
    }
    
}
