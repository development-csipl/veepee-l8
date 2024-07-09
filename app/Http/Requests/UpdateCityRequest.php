<?php

namespace App\Http\Requests;

use App\StatesModels;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateCityRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('city_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {   
        
        if ($this->method() == 'PUT'){

            $rules = 'sometimes|string|unique:cities,city_name,' .$this->post('uid'); 
        }

        
       
        return [
            'state_id' => 'required',
            
			'city_name'=> $rules
            
        ];
    }
}
