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

    public function indicator($nama_tanaman){
      $indikator = ModelTanahnya::optimumIndicator($nama_tanaman);
      return $indikator;
      // return response()->json($data);
    }

    public function getSensorData($nama_tanaman){
      $sensor_result = ModelTanahnya::presentCondition();

      $sensor = [
        'id' => $sensor_result->id,
        'ec' => $sensor_result->ec_sensor,
        'temp' => $sensor_result->temp_sensor,
        'ph' => $sensor_result->ph_sensor,
        'humid' => $sensor_result->humid_sensor,
        'time' => $sensor_result->waktu_diambil,
        'ec_status' => 'OK',
        'ph_status' => 'OK',
        'temp_status' => 'OK',
        'humid_status' => 'OK',
        'nilai' => 100
      ];

      $indikator = $this->indicator($nama_tanaman);
      if($sensor_result->ec_sensor < $indikator->batas_bawah_ec || $sensor_result->ec_sensor > $indikator->batas_atas_ec){
        $sensor['ec_status'] = 'Not OK';
        $sensor['nilai']-=25;
      }
      if($sensor_result->ph_sensor < $indikator->batas_bawah_ph || $sensor_result->ph_sensor > $indikator->batas_atas_ph){
        $sensor['ph_status'] = 'Not OK';
        $sensor['nilai']-=25;
      }
      if($sensor_result->temp_sensor < $indikator->batas_bawah_temp || $sensor_result->temp_sensor > $indikator->batas_atas_temp){
        $sensor['temp_status'] = 'Not OK';
        $sensor['nilai']-=25;
      }
      if($sensor_result->humid_sensor < $indikator->batas_bawah_humid || $sensor_result->humid_sensor > $indikator->batas_atas_humid){
        $sensor['humid_status'] = 'Not OK';
        $sensor['nilai']-=25;
      }
      return response()->json($sensor);
      // print_r($sensor);
    }
}
