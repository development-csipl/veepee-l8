<?php

namespace App\Http\Requests;

use App\StationModel;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;

class StoreStationRequest extends FormRequest
{
    public function authorize()
    {
	
        abort_if(Gate::denies('station_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'country_id' => [
                'required',
            ],
            'state_id' => [
                'required',
            ],
            'city_id' => [
                'required',
            ]
        ];
    }
}