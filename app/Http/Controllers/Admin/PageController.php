<?php

namespace App\Http\Controllers\Admin;

use App\Entity\Page;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Pages\PageRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:manage-pages');
    }

    public function index()
    {
        $pages = Page::defaultOrder()->withDepth()->get();
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        $parents = Page::defaultOrder()->withDepth()->get();
        return view('admin.pages.create', compact('parents'));
    }

    public function store(PageRequest $request): RedirectResponse
    {
        $page = Page::create([
            'title' => $request['title'],
            'slug' => Str::slug($request['title']),
            'menu_title' => $request['menu_title'],
            'parent_id' => $request['parent'],
            'content' => $request['content'],
            'description' => $request['description'],
        ]);
        return redirect()->route('admin.pages.show', $page);
    }

    public function show(Page $page)
    {
        return view('admin.pages.show', compact('page'));
    }

    public function edit(Page $page)
    {
        $parents = Page::defaultOrder()->withDepth()->get();
        return view('admin.pages.edit', compact('page', 'parents'));
    }

    public function update(PageRequest $request, Page $page): RedirectResponse
    {
        $page->update([
            'title' => $request['title'],
            'slug' => Str::slug($request['title']),
            'menu_title' => $request['menu_title'],
            'parent_id' => $request['parent'],
            'content' => $request['content'],
            'description' => $request['description'],
        ]);
        //При изменинии любой страницы нужно удалять кэш, так как адреса могли измениться
        Cache::tags(Page::class)->flush();
        return redirect()->route('admin.pages.show', $page)->with('success', 'Страница успешно обновлена');
    }

    public function first(Page $page): RedirectResponse
    {
        if ($first = $page->siblings()->defaultOrder()->first()) {
            $page->insertBeforeNode($first);
        }
        return redirect()->route('admin.pages.index');
    }

    public function last(Page $page): RedirectResponse
    {
        if ($last = $page->siblings()->defaultOrder('desc')->first()) {
            $page->insertAfterNode($last);
        }
        return redirect()->route('admin.pages.index');
    }

    public function up(Page $page): RedirectResponse
    {
        $page->up();
        return redirect()->route('admin.pages.index');
    }

    public function down(Page $page): RedirectResponse
    {
        $page->down();
        return redirect()->route('admin.pages.index');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', 'Страница удалена');
    }
}
