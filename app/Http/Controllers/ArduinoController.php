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
      $this->client = new \GuzzleHttp\Client();
    }

    public function updateCondition(Request $request){

      $checkAuth = app('App\Http\Controllers\AuthController')->AuthorizeArduino($request);

      if($checkAuth == "true"){

        $data = array(
          'nama_alat' => $request->json()->get('nama_alat'),
          'ec_sensor' => $request->json()->get('ec_sensor'),
          'temp_sensor' => $request->json()->get('temp_sensor'),
          'ph_sensor' => $request->json()->get('ph_sensor'),
          'humid_sensor' => $request->json()->get('humid_sensor'),
          'waktu_diambil' => \Carbon\Carbon::now('Asia/Jakarta')->toDateTimeString()
        );
        ModelTanahnya::NewSensorRead($data);
        $this->UpdateNilai($data);
        return response('Berhasil',201);
      }
      else return response("Unauthorized", 401);
  }

  public function UpdateNilai($data){
    $result = ModelTanahnya::UpdateNilaiSensor($data);
    return response(200);
    // print_r($result);
    // echo $result[0]->id;
  }

  public function UpdateNilaiAfterEdit(Request $request, $id){
    $nilai = $request->json()->get('nilai');
    $result = ModelTanahnya::UpdateNilaiSensorAfterEdit($id, $nilai);
    return response(200);
    // print_r($result);
    // echo $result[0]->id;
  }

  public function AddSensors(Request $request){
    $username = $request->header('username');
    $result = ModelTanahnya::isAdmin($username);
    if($result){
      $data = array(
      'nama_alat' => $request->json()->get('nama_alat'),
      'ip_address' => $request->json()->get('ip_address'),
      'nama_tanaman' => $request->json()->get('nama_tanaman'),
      'Status' => 'Silahkan Refresh Sensor Terlebih Dahulu',
      'nilai' => 0
      );
      ModelTanahnya::AddSensorsToDB($data);
      return response("OK", 201);
    }
    else return response("Unauthorized", 401);
  }

  public function editSensor(Request $request, $id){
    $username = $request->header('username');
    $result = ModelTanahnya::isAdmin($username);
    if($result){
      $data = ModelTanahnya::getSensorById($id);
      return response()->json($data);
    }
    else return response("Unauthorized", 401);
  }

  public function editSensorToDB(Request $request, $id){
    $username = $request->header('username');
    $result = ModelTanahnya::isAdmin($username);
    if($result){
      $data = array(
        'nama_alat' => $request->json()->get('nama_alat'),
        'ip_address' => $request->json()->get('ip_address'),
        'nama_tanaman' => $request->json()->get('nama_tanaman'),
      );
      ModelTanahnya::UpdateSensorDetail($id, $data);
      return response("OK", 201);
    }
    else return response("Unauthorized", 401);
  }

  public function RemoveSensor(Request $request, $id){
    $username = $request->header('username');
    $result = ModelTanahnya::isAdmin($username);
    if($result){
      ModelTanahnya::removeArduino($id);
      return response("OK", 200);
    }
    else return response("Unauthorized", 401);
  }

  public function ShowListSensors(Request $request){
    $list = ModelTanahnya::ListSensors();
    return response()->json($list);
  }

  // public function Status(Request $request){
  //   $this->client->get('192.168.1.200:80/status');
  // }
}
