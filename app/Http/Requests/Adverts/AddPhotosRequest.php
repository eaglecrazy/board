<?php

namespace App\Http\Requests\Adverts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class AddPhotosRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(Request $request) : array
    {
        $max = 4 - $request->advert->photos()->count();
        return [
            'files.*' => 'required|mimes:jpg,jpeg,png|max:4096',
            'files' => 'files_count:' . $max,
        ];
    }
}
