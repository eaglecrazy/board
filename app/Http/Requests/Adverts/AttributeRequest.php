<?php

namespace App\Http\Requests\Adverts;

use App\Entity\Adverts\Attribute;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttributeRequest extends FormRequest
{
        public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'name' => 'required|string|max:255|min:2',
            'type' => 'required|string|max:255', Rule::in(array_keys(Attribute::typesList())),
            'required' => 'nullable|string|max:255',
            'variants' => 'nullable|string',
            'sort' => 'required|integer'
        ];
    }
}
