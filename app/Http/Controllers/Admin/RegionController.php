<?php

namespace App\Http\Controllers\Admin;

use App\Entity\Region;
use App\Events\RegionDeleteEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegionController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:manage-regions');
    }

    public function index(Request $request)
    {
        $regions = Region::where('parent_id', null)->orderBy('name')->get();
        $region = null;
        return view('admin.regions.index', compact('regions', 'region'));
    }

    public function create(Request $request)
    {
        $parent = null;
        if($request->get('parent')){
            $parent = Region::findOrFail($request->get('parent'));
        }
        return view('admin.regions.create', compact('parent'));
    }

    public function create_inner(Region $region){
        return view('admin.regions.create', ['parent' => $region]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|min:3|max:255|unique:regions,name,NULL,id,parent_id,' . ($request['parent'] ?: 'NULL'),
            'important' => 'nullable|string|max:3',
            'parent' => 'nullable|exists:regions,id',
        ]);

        $region = Region::create([
            'name' => $name = $request['name'],
            'slug' => Str::slug($name),
            'parent_id' => $request['parent'],
            'important' => (bool)$request['important'],
        ]);
        return redirect()->route('admin.regions.show', $region)->with('success', 'Регион создан.');
    }

    public function show(Region $region)
    {
        $regions = Region::where('parent_id', $region->id)->orderBy('name')->get();
        return view('admin.regions.show', compact('region', 'regions'));
    }

    public function edit(Region $region)
    {
        return view('admin.regions.edit', compact('region'));
    }

    public function update(Request $request, Region $region)
    {
        $this->validate($request, [
//            'name' => 'required|string|min:3|max:255|unique:regions,name,NULL,id,parent_id,' . ($request['parent'] ?: 'NULL'),
            'name' => 'required|string|min:3|max:255|unique:regions,name,' . $region->id . ',id,parent_id,' .$region->parent_id,
            'important' => 'nullable|string|max:3',
        ]);

        $region->update([
            'name' => $name = $request['name'],
            'slug' => Str::slug($name),
            'important' => (bool)$request['important'],
        ]);
        return redirect()->route('admin.regions.show', compact('region'))->with('success', 'Регион успешно отредактирован.');
    }

    public function destroy(Region $region)
    {
        $region->delete();
        event(new RegionDeleteEvent($region));
        //удаление региона, это причина перестроить индекс

        return redirect()->route('admin.regions.index')->with('success', 'Регион удалён.');
    }

}
