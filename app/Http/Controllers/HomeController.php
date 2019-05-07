<?php

namespace App\Http\Controllers;

use App\ModelTanahnya;
use Illuminate\Http\Request;

class HomeController extends Controller
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

    public function index(){
      $data = ModelTanahnya::presentCondition();
      return response()->json($data);
    }
}
