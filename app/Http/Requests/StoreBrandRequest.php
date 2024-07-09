<?php

namespace App\Http\Requests;

use App\BranchModel;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreBrandRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('brand_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'required|unique:brands,name,id',
            ],
        ];
    }
}