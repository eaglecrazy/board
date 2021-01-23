<?php

namespace App\Http\Requests\Admin\Users;

use App\Entity\User\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,id,' . $this->user->id],
            'role' => ['required', 'string', Rule::in(array_keys(User::rolesList()))],
        ];

        if($request['password']){
            $rules['password'] = ['required', 'string', 'min:1', 'confirmed'];
        }

        return $rules;
    }
}
