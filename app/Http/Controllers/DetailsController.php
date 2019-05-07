<?php

namespace App\Http\Controllers;

use App\ModelTanahnya;
use Illuminate\Http\Request;

class DetailsController extends Controller
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

    public function showECLog(){
      $jenis_sensor = "ec_sensor";
      $data = ModelTanahnya::sensorRead($jenis_sensor);
      return response()->json($data);
    }

    public function showTempLog(){
      $jenis_sensor = "temp_sensor";
      $data = ModelTanahnya::sensorRead($jenis_sensor);
      return response()->json($data);
    }

    public function showPHLog(){
      $jenis_sensor = "ph_sensor";
      $data = ModelTanahnya::sensorRead($jenis_sensor);
      return response()->json($data);
    }

    public function showHumidLog(){
      $jenis_sensor = "humid_sensor";
      $data = ModelTanahnya::sensorRead($jenis_sensor);
      return response()->json($data);
    }
}
