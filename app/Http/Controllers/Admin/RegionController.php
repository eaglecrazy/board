<?php

namespace App\Http\Controllers\Admin;

use App\Entity\Region;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class RegionController extends Controller
{
    public function index(Request $request)
    {
        $regions = Region::orderBy('name')->paginate(30);
        return view('admin.regions.index', compact('regions'));
    }

    public function create()
    {
        return view('admin.regions.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255|unique:regions,name,NULL,id,parent_id,' . ($request['parent'] ?: 'NULL'),
            'parent' => 'optional|exists:regions,id',
        ]);

        $region = Region::create([
            'name' => $name = $request['name'],
            'slug' => Str::slug($name),
            'parent_id' => $request['parent'],
        ]);

        return redirect()->route('admin.regions.show', $region);
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
            'name' => 'required|string|max:255|unique:regions,name,' . $region->id . 'id,parent_id,' . $region->parent_id
        ]);

        $region->update([
            'name' => $name = $request['name'],
            'slug' => Str::slug($name)
        ]);

        return redirect()->route('admin.regions.show');
    }

    public function destroy(Region $region)
    {
        $region->delete();
        return redirect()->route('admin.regions.index');
    }

}
