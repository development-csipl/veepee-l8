<?php

namespace App\Http\Requests;

use App\StationModel;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;

class StoreSuppliersRequest  extends FormRequest
{
    public function authorize()
    {
	
        abort_if(Gate::denies('supplier_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
			'supplier_id' =>  'required|unique:users,veepeeuser_id,id',
			
			'email' => 'required|email',
			'owner_name' =>  'required',
            'owner_contact' =>  'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10',
            'order_name' => 'required',
            'order_contact' =>  'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10',
          //'sister_firm' =>  'required',
            'notify_email' => 'required|email',
           // 'notify_sms' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10',
            'notify_whatsapp' =>  'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10',
            //'min_quantity' => 'integer',
            'catalog' =>  'mimes:pdf',
        /*  'market' =>  'required',
            'design' => 'required',
            'pattern' =>  'required',
            
            'packing' =>  'required',
            'fabric' => 'required',
            'catalog' =>  'required',
            'discount' =>  'required',*/
            'branch_id' => 'required|integer',
        ];
    }
}