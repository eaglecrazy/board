<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Entity\Adverts\Category;
use App\Http\Requests\Adverts\CategoryRequest;
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

    public function store(CategoryRequest $request)
    {
        $category = Category::create([
            'name' => $name = $request['name'],
            'slug' => Str::slug($name),
            'parent_id' => $request['parent'],
        ]);
        return redirect()->route('admin.adverts.categories.show', compact('category'));
    }

    public function show(Category $category)
    {
        $parentAttributes = $category->parentAttributes();
        $attributes = $category->attributes()->orderBy('sort')->get();
        return view('admin.adverts.categories.show', compact('category', 'attributes', 'parentAttributes'));
    }

    public function edit(Category $category)
    {
        $current = $category;
        $categories = Category::defaultOrder()->withDepth()->get();
        return view('admin.adverts.categories.edit', compact('categories', 'current'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
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

    public function first(Category $category){
        if($first = $category->siblings()->defaultOrder()->first()){
            $category->insertBeforeNode($first);
        }
        return redirect()->route('admin.adverts.categories.index');
    }

    public function last(Category $category){
        if($last = $category->siblings()->defaultOrder('desc')->first()){
            $category->insertAfterNode($last);
        }
        return redirect()->route('admin.adverts.categories.index');
    }

    public function up(Category $category){
        $category->up();
        return redirect()->route('admin.adverts.categories.index');
    }

    public function down(Category $category){
        $category->down();
        return redirect()->route('admin.adverts.categories.index');
    }
}
