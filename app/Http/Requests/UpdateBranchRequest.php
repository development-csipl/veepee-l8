<?php

namespace App\Http\Requests;

use App\Models\BranchModel;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateBranchRequest  extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('branch_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'address' => 'required',
            'map_address' => 'required',
            'stay_facility' => 'required',
            'landline_no' => 'required',
            'mobile_no' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10',
        ];
    }
}
