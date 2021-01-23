<?php

namespace App\Http\Requests\Adverts;

use Illuminate\Foundation\Http\FormRequest;

class AdvertContentEditRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|min:3',
            'content' => 'required|string|min:10',
            'price' => 'required|integer|min:1|max:2147483646',
            'address' => 'string|nullable',
        ];
    }
}
