<?php

namespace App\Http\Requests;

use App\Models\CityModels;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;

class StoreCityRequest extends FormRequest
{
    public function authorize()
    {
	
        abort_if(Gate::denies('city_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'state_id' => [
                'required',
            ], 
			'city_name'=> [
                'required|unique:cities',
            ],
            /*'city_code' => [
                'required',
            ],*/
            
        ];
    }
}
