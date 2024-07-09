<?php

namespace App\Http\Requests;

use App\TransportsModels;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Validator;

class UpdateTransportsRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('transport_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
             'branch_id' => 'required',
            'transport_name' => 'required|max:200',
            'gst' => 'required|size:18',
            'address' => 'required|max:200',
            'contact_person' => 'required|max:100',
            'contact_mobile' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10',
        ];
    }
}
