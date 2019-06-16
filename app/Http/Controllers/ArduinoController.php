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
      }
      else{
        $data->ec_sensor = $request->json()->get('ec_sensor');
        $data->temp_sensor = $request->json()->get('temp_sensor');
        $data->ph_sensor = $request->json()->get('ph_sensor');
        $data->humid_sensor = $request->json()->get('humid_sensor');
        // $data->save();
      }
        $this->sendMessage();
        return response($data);
        // return response('Berhasil');
  }

    public function sendMessage() {
      $content = array(
          "en" => 'Gatau ini diisi apaan entaran aja deh ya ehehe'
      );
      $headings = array(
          "en" => 'Pembacaan Sensor Telah Diperbaharui'
      );
      $fields = array(
          'app_id' => "ad49c20e-4ff5-4a5f-b374-7404ce2cfde4",
          'included_segments' => array(
              'All'
          ),
          'data' => array(
              "foo" => "bar"
          ),
          'headings' => $headings,
          'contents' => $content,
          'url' => 'http://localhost:8000'
      );
      
      $fields = json_encode($fields);
      print("\nJSON sent:\n");
      print($fields);
      
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Content-Type: application/json; charset=utf-8',
          'Authorization: Basic NDhlYzUwZjktZWVhMC00YmJkLTg0OGItMjYxNjJjYTBhMzk0'
      ));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_HEADER, FALSE);
      curl_setopt($ch, CURLOPT_POST, TRUE);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
      
      $response = curl_exec($ch);
      curl_close($ch);
      // $response = 'bisa pokoknya';
      return $response;
  }
}
