<?php

namespace App\Http\Controllers;

use App\ModelTanahnya;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function showListTanaman(Request $request){
      $list = ModelTanahnya::listTanaman();
      return response()->json($list);
    }
}
