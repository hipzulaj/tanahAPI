<?php

namespace App\Http\Controllers;

use App\ModelTanahnya;
use Illuminate\Http\Request;

class ArduinoController extends Controller
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

    public function updateCondition(Request $request){
      $data = new ModelTanahnya();

      if($request->isJson()){

        $data->ec_sensor = $request->json()->get('ec_sensor');
        $data->temp_sensor = $request->json()->get('temp_sensor');
        $data->ph_sensor = $request->json()->get('ph_sensor');
        $data->humid_sensor = $request->json()->get('humid_sensor');
        // $data->save();

        return response($data);
      }
      else{

        $data->ec_sensor = $request->json()->get('ec_sensor');
        $data->temp_sensor = $request->json()->get('temp_sensor');
        $data->ph_sensor = $request->json()->get('ph_sensor');
        $data->humid_sensor = $request->json()->get('humid_sensor');
        // $data->save();

        return response("Berhasil Tambah data");
      }
  }
}
