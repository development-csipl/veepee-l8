<?php

namespace App\Http\Requests;

use App\User;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules(){
        if ($this->method() == 'PUT') 
        {

            $email_rules = 'sometimes|email|string|unique:users,email,' .$this->post('uid'); 
            $veepeeid_rules = 'sometimes|unique:users,veepeeuser_id,' .$this->post('uid');
        }

        return [
            'email' => $email_rules,
            'veepeeuser_id' => $veepeeid_rules,
            'name' => 'required|string|max:50',
          
            'roles.*'  =>'integer',
            'roles'    =>'required|array',
        
        ];
    }
}
