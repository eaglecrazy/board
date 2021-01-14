<?php

namespace App\Http\Controllers\Cabinet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DialogController extends Controller
{
    public function allDialogs(){
        return view('cabinet.dialogs.index');
    }
}
