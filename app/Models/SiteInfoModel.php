<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class SiteInfoModel extends Model
{

    public $timestamps = false;
    public $table = 'important_info';
    protected $fillable = [
        'email', 'name', 'address', 'phone','privacy_policy', 'tnc_supplier', 'tnc_buyer', 'about_us', 'home', 'profile', 'min_order_amount', 'max_order_dispatch_day','hotel_url','branch_url','contact_url','website_url','home_banner','banner_link','bank_name','account_holder_name','account_number','ifsc_number',
        'comm_address','mobile_number'
    ];

    
}
