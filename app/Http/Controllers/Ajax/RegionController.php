<?php

namespace App\Http\Controllers\Ajax;

use App\Entity\Region;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function get(Request $request){
        $parent = $request->get('parent');
        return Region::where('parent_id', $parent)
            ->orderBy('name')
            ->select('id', 'name')
            ->get()
            ->toArray();
    }
}
