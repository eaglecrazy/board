<?php

namespace App\Http\Requests\Admin\Pages;

use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $data = [
            'menu_title' => 'required|string|max:255',
            'parent' => 'nullable|integer|exists:pages,id',
            'content' => 'nullable|string',
            'description' => 'nullable|string',
        ];
        if(isset($this->page->id)){
            $data['title'] = ['required','string','max:255','unique:pages,title,' . $this->page->id];
        } else {
            $data['title'] = ['required','string','max:255','unique:pages,title'];
        }

        return $data;
    }
}
