<?php

namespace App\Http\Requests\Cabinet;

use Illuminate\Foundation\Http\FormRequest;

class ProfileEditReruest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255|regex:/\+7\(\d\d\d\)\d\d\d-\d\d-\d\d/',
        ];
    }
}

/**
 * @SWG\Definition(
 *     definition="ProfileEditRequest",
 *     type="object",
 *     @SWG\Property(property="name", type="string"),
 *     @SWG\Property(property="last_name", type="string"),
 *     @SWG\Property(property="phone", type="string"),
 * )
 */
