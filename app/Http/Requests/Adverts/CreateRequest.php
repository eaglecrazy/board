<?php

namespace App\Http\Requests\Adverts;


use App\Entity\Adverts\Category;
use App\Entity\Region;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;

/**
 * @property Category $category
 * @property Region $region
 */
class CreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $items = [];

        foreach ($this->category->allAttributes() as $attribute) {
            $rules = [
                $attribute->required ? 'required' : 'nullable',
            ];
            if ($attribute->isInteger()) {
                $rules[] = 'integer';
            } elseif ($attribute->isFloat()) {
                $rules[] = 'numeric';
            } else {
                $rules[] = 'string';
                $rules[] = 'max:255';
                $rules[] = 'min:2';
            }
            if ($attribute->isSelect()) {
                $rules[] = Rule::in($attribute->variants);
            }
            $items['attributes.' . $attribute->id] = $rules;
        }

        return array_merge([
            'title' => 'required|string|min:3',
            'content' => 'required|string|min:10',
            'price' => 'required|integer|min:1|max:2147483646',
            'address' => 'string|nullable',
            'files.*' => 'required|mimes:jpg,jpeg,png|max:1024',
            'files' => 'files_count:4'
        ], $items);
    }
}
