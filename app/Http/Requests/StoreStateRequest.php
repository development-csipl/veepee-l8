<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreStateRequest extends FormRequest
{
    public function authorize()
    {
	
        abort_if(Gate::denies('states_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
           'state_name' => 'required|unique:states',
            
        ];
    }
}
