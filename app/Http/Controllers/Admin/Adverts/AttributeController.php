<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Entity\Adverts\Attribute;
use App\Entity\Adverts\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\Adverts\AttributeRequest;

class AttributeController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:manage-adverts-categories');
    }

    public function create(Category $category)
    {
        $types = Attribute::typesList();
        return view('admin.adverts.categories.attributes.create', compact('category', 'types'));
    }

    public function store(AttributeRequest $request, Category $category)
    {
        $data = [
            'name' => $request['name'],
            'type' => $request['type'],
            'required' => (bool)$request['required'],
            'sort' => $request['sort'],
        ];

        if($data['type'] !== Attribute::TYPE_BOOL){
            $data['variants'] = array_map('trim', preg_split('#[\r\n]+#', $request['variants']));
        } else {
            $data['variants'] = [''];
        }

        $category->attributes()->create($data);

        return redirect()->route('admin.adverts.categories.show', compact('category'))->with('success', 'Атрибут создан.');
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
        $data = [
            'name' => $request['name'],
            'type' => $request['type'],
            'required' => (bool)$request['required'],
            'sort' => $request['sort'],
        ];

        if($data['type'] !== Attribute::TYPE_BOOL){
            $data['variants'] = array_map('trim', preg_split('#[\r\n]+#', $request['variants']));
        } else {
            $data['variants'] = [''];
        }

        $category->attributes()->findOrFail($attribute->id)->update($data);




        return redirect()->route('admin.adverts.categories.show', compact('category'));

    }

    public function destroy(Category $category, Attribute $attribute){
        $attribute->delete();
        return redirect()->route('admin.adverts.categories.show', compact('category'))->with('success', 'Атрибут удален.');
    }
}
