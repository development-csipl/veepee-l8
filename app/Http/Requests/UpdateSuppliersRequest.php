<?php

namespace App\Http\Requests;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateSuppliersRequest  extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('supplier_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules(){

        if ($this->method() == 'PUT') {
            //$gst_rules = 'sometimes|size:15|string|unique:users,gst,' .$this->post('uid'); 
            $gst_rules = 'sometimes|string|unique:users,veepeeuser_id,' .$this->post('uid'); 
        }

         return [
            'name' =>'required',
           // 'country_id' =>  'required|integer',
            'state_id' => 'required|integer',
            'city_id' => 'required|integer',
            'address' => 'required',
            'gst' =>  'required|size:15',
            //'gst' =>  $gst_rules,          
            //'supplier_id' =>  $gst_rules,
            'email' =>'required|email',
            'owner_name' =>  'required',
            'owner_contact' =>  'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10',
            'order_name' => 'required',
            'order_contact' =>  'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10',
          //'sister_firm' =>  'required',
            'notify_email' => 'required|email',
           // 'notify_sms' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10',
            'notify_whatsapp' =>  'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10',
            'catalog' =>  'mimes:pdf',
        /*  'market' =>  'required',
            'design' => 'required',
            'pattern' =>  'required',
            'min_quantity' => 'required',
            'packing' =>  'required',
            'fabric' => 'required',
            'catalog' =>  'required',
            'discount' =>  'required',*/
            'branch_id' => 'required|integer',
        ];
    }
}
