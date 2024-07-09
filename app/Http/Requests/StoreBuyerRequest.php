<?php

namespace App\Http\Requests;

use App\StationModel;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;

class StoreBuyerRequest extends FormRequest
{
    public function authorize()
    {
	
        abort_if(Gate::denies('buyer_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        
        return [
            'name' =>'required',
			//'country_id' =>  'required|integer',
			'state_id' => 'required|integer',
			'city_id' => 'required|integer',
			'address' => 'required',
			'gst' =>  'required|size:15',
			//'gst' =>  'required|size:15|unique:users,gst,id',			
			'email' => 'required|email',
            'credit_limit' => 'required',
			'owner_name' =>  'required',
            'owner_contact' =>  'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10',
            'order_name' => 'required',
            'order_contact' =>  'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10',
            'notify_email' => 'required|email',
           // 'notify_sms' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10',
            'notify_whatsapp' =>  'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10',
        /* 'gender' =>  'required', 
            'sister_firm' =>  'required',
            'market' =>  'required',
            'design' => 'required',
            'pattern' =>  'required',
            'min_quantity' => 'required',
            'packing' =>  'required',
            'fabric' => 'required',
            'catalog' =>  'required',
            'discount' =>  'required',*/
            
        ];
    }
}