<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Entity\Category;
use App\Entity\Region;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::defaultOrder()->withDepth()->get();
        return view('admin.adverts.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = Category::defaultOrder()->withDepth()->get();
        return view('admin.adverts.categories.create', compact('parents'));
    }

    public function create_inner(Category $category){
        $current = $category;
        $parents = Category::defaultOrder()->withDepth()->get();
        return view('admin.adverts.categories.create', compact('parents', 'current'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'parent' => 'nullable|integer|exists:advert_categories,id',
        ]);

        $category = Category::create([
            'name' => $name = $request['name'],
            'slug' => Str::slug($name),
            'parent_id' => $request['parent'],
        ]);

        return redirect()->route('admin.adverts.categories.show', compact('category'));
    }

    public function show(Category $category)
    {
        return view('admin.adverts.categories.show', compact('category'));
    }

    public function edit(Request $request, Category $category)
    {
        $current = $category;
        $categories = Category::defaultOrder()->withDepth()->get();
        return view('admin.adverts.categories.edit', compact('categories', 'current'));
    }

    public function update(Request $request, Category $category)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'parent' => 'nullable|integer|exists:advert_categories,id',
        ]);

        $category->update([
            'name' => $name = $request['name'],
            'slug' => Str::slug($name),
            'parent_id' => $request['parent'],
        ]);

        return redirect()->route('admin.adverts.categories.show', compact('category'));
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.adverts.categories.index');
    }
}
