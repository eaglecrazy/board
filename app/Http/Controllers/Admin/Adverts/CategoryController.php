<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Entity\Adverts\Category;
use App\Events\CategoryDeleteEvent;
use App\Events\CategoryUpdateEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Adverts\CategoryRequest;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:manage-adverts-categories');
    }

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

    public function create_inner(Category $category)
    {
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
        return redirect()->route('admin.adverts.categories.show', compact('category'))->with('success', 'Категория создана.');
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
        //если изменился родитель, то будем переиндексировать
        if ($category->parent_id != $request['parent']) {
            $categoryIds = array_merge($category->getDescendantsIds(), [$category->id]);
        }

        $category->update([
            'name' => $name = $request['name'],
            'slug' => Str::slug($name),
            'parent_id' => $request['parent'],
        ]);

        if (isset($categoryIds)) {
            event(new CategoryUpdateEvent($categoryIds));
        }

        return redirect()->route('admin.adverts.categories.show', compact('category'))->with('success', 'Категория успешно отредактирована.');
    }

    public function destroy(Category $category)
    {
        $parentId = $category->parent_id;
        //категорию верхнего уровня удалить нельзя
        if ($parentId === null) {
            return back()->with('error', 'Нельзя удалить корневую категорию. Для удаления сделайте категорию дочерней в редактировании.');
        }
        $categoryIds = array_merge($category->getDescendantsIds(), [$category->id]);
        $category->delete();
        event(new CategoryDeleteEvent($categoryIds, $parentId));
        return redirect()->route('admin.adverts.categories.index')->with('success', 'Категория удалена.');
    }

    public function first(Category $category)
    {
        if ($first = $category->siblings()->defaultOrder()->first()) {
            $category->insertBeforeNode($first);
        }
        return redirect()->route('admin.adverts.categories.index');
    }

    public function last(Category $category)
    {
        if ($last = $category->siblings()->defaultOrder('desc')->first()) {
            $category->insertAfterNode($last);
        }
        return redirect()->route('admin.adverts.categories.index');
    }

    public function up(Category $category)
    {
        $category->up();
        return redirect()->route('admin.adverts.categories.index');
    }

    public function down(Category $category)
    {
        $category->down();
        return redirect()->route('admin.adverts.categories.index');
    }
}
