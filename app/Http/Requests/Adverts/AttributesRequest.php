<?php

namespace App\Http\Requests\Adverts;

use App\Entity\Adverts\Advert\Advert;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property Advert $advert
 */
class AttributesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $items = [];
        foreach ($this->advert->category->allAttributes() as $attribute) {
            $items['attributes.' . $attribute->id] = $attribute->getValidationRules();
        }
        return $items;
    }
}
