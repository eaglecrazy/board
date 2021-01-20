<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function image(Request $request): string
    {
        $this->validate($request, [
            'file' => 'required|mimes:jpg,jpeg,png',
        ]);

        $file = $request->file('file');
        $path = $file->store('images', 'public');

        return asset('storage/') . '/' .  $path;
//        return Storage::disk('public')->url($path);
    }
}
