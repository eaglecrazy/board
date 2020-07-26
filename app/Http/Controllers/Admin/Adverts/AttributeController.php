<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Entity\Attribute;
use App\Entity\Category;
use App\Http\Requests\Adverts\AttributeRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class AttributeController extends Controller
{
    public function create(Category $category)
    {
        $types = Attribute::typesList();
        return view('admin.adverts.categories.attributes.create', compact('category', 'types'));
    }

    public function store(AttributeRequest $request, Category $category)
    {
        $category->attributes()->create([
            'name' => $request['name'],
            'type' => $request['type'],
            'required' => (bool)$request['required'],
            'variants' => array_map('trim', preg_split('#[\r\n]+#', $request['variants'])),
            'sort' => $request['sort'],
        ]);

        return redirect()->route('admin.adverts.categories.show', compact('category'));
    }

    public function show(Category $category, Attribute $attribute)
    {
        return view('admin.adverts.categories.attributes.show', compact('category', 'attribute'));
    }

    public function edit(Category $category, Attribute $attribute){
        $types = Attribute::typesList();
        return view('admin.adverts.categories.attributes.edit', compact('category', 'attribute', 'types'));
    }

    public function update(AttributeRequest $request, Category $category, Attribute $attribute)
    {
        $category->attributes()->findOrFail($attribute->id)->update([
            'name' => $request['name'],
            'type' => $request['type'],
            'required' => (bool)$request['required'],
            'variants' => array_map('trim', preg_split('#[\r\n]+#', $request['variants'])),
            'sort' => $request['sort'],
        ]);

        return redirect()->route('admin.adverts.categories.show', compact('category'));

    }

    public function destroy(Category $category, Attribute $attribute){
        $attribute->delete();
        return redirect()->route('admin.adverts.categories.show', compact('category'));
    }
}
