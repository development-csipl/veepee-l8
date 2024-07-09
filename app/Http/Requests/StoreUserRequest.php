<?php

namespace App\Http\Requests;

use App\User;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules(){

        return [
            'email' => 'required|email|unique:users',
            'veepeeuser_id' => 'required|unique:users',
            'name' => 'required|string|max:50',
            'password' => 'required|min:8',
            'roles.*'  =>'integer',
            'roles'    =>'required|array',
        
        ];

    }
}
