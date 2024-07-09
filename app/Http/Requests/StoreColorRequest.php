<?php

namespace App\Http\Requests;

use App\ColorModel;
use Gate;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreColorRequest extends FormRequest
{
    public function authorize(){
        abort_if(Gate::denies('color_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules(){

         return [
            'colorcode' =>'required|unique:colors,colorcode,id'
        ];
    }
}
