<?php

namespace App\Http\Requests\Admin\Users;

use Illuminate\Foundation\Http\FormRequest;
use App\Entity\User;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,id,' . $this->user->id],
            'password' => ['required', 'string', 'min:1', 'confirmed'],
            'role' => ['required', 'string', Rule::in(array_keys(User::rolesList()))],
        ];
    }
}
