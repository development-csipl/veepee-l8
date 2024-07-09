<?php

namespace App\Http\Requests;

use App\StatesModels;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateStateRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('state_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
         if ($this->method() == 'PUT'){

            $rules = 'sometimes|string|unique:states,state_name,' .$this->post('uid'); 
        }

        
        return [
            'state_name' => $rules
        ];
    }
}
