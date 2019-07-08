<?php
namespace App\Http\Controllers;

use App\ModelTanahnya;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Response;
ini_set('max_execution_time', 180);

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
    $this->client = new \GuzzleHttp\Client(['http_errors' => false, 'timeout'=>10.0]);
  }

    public function Index(Request $request){
      $data = ModelTanahnya::ListSensors();
      return response()->json($data);
    }

    public function Indicator($nama_tanaman){
      $indikator = ModelTanahnya::optimumIndicator($nama_tanaman);
      return $indikator;
      // return response()->json($data);
    }

    public function getSensorData(Request $request, $nama_alat, $nama_tanaman){
      $alat = urldecode($nama_alat);
      $tanaman = urldecode($nama_tanaman);
      $sensor_result = ModelTanahnya::presentCondition($alat, $tanaman);
      // print_r($sensor_result);
      if(!($sensor_result)) return response("Not Found", 404);
      else{
          $sensor = [
          'id' => $sensor_result->id,
          'ec' => $sensor_result->ec_sensor,
          'temp' => $sensor_result->temp_sensor,
          'ph' => $sensor_result->ph_sensor,
          'humid' => $sensor_result->humid_sensor,
          'time' => $sensor_result->waktu_diambil,
          'batas_bawah_ec' => $sensor_result->batas_bawah_ec,
          'batas_atas_ec' => $sensor_result->batas_atas_ec,
          'batas_bawah_ph' => $sensor_result->batas_bawah_ph,
          'batas_atas_ph' => $sensor_result->batas_atas_ph,
          'batas_bawah_temp' => $sensor_result->batas_bawah_temp,
          'batas_atas_temp' => $sensor_result->batas_atas_temp,
          'batas_bawah_humid' => $sensor_result->batas_bawah_humid,
          'batas_atas_humid' => $sensor_result->batas_atas_humid,
          'ec_status' => 'OK',
          'ph_status' => 'OK',
          'temp_status' => 'OK',
          'humid_status' => 'OK',
          'nilai' => 100
        ];
        
        if($sensor_result->ec_sensor < $sensor_result->batas_bawah_ec || $sensor_result->ec_sensor > $sensor_result->batas_atas_ec){
          $sensor['ec_status'] = 'Not OK';
          $sensor['nilai']-=25;
        }
        if($sensor_result->ph_sensor < $sensor_result->batas_bawah_ph || $sensor_result->ph_sensor > $sensor_result->batas_atas_ph){
          $sensor['ph_status'] = 'Not OK';
          $sensor['nilai']-=25;
        }
        if($sensor_result->temp_sensor < $sensor_result->batas_bawah_temp || $sensor_result->temp_sensor > $sensor_result->batas_atas_temp){
          $sensor['temp_status'] = 'Not OK';
          $sensor['nilai']-=25;
        }
        if($sensor_result->humid_sensor > $sensor_result->batas_bawah_humid || $sensor_result->humid_sensor < $sensor_result->batas_atas_humid){
          $sensor['humid_status'] = 'Not OK';
          $sensor['nilai']-=25;
        }
        return response()->json($sensor);
      }
      // print_r($sensor);
    }

    public function UpdateAllNilaiByTanaman(Request $request, $nama_tanaman){
      $tanaman = urldecode($nama_tanaman);
      $alat = ModelTanahnya::SearchSensorByNamaTanaman($tanaman);
      for($i=0;$i<count($alat);$i++){
        $sensor_result = ModelTanahnya::presentCondition($alat[$i]->nama_alat, $tanaman);
        if(!($sensor_result)){
          return response("Not Found", 404);
          continue;
        }
        else{
          $sensor = [
          'id' => $sensor_result->id,
          'ec' => $sensor_result->ec_sensor,
          'temp' => $sensor_result->temp_sensor,
          'ph' => $sensor_result->ph_sensor,
          'humid' => $sensor_result->humid_sensor,
          'time' => $sensor_result->waktu_diambil,
          'batas_bawah_ec' => $sensor_result->batas_bawah_ec,
          'batas_atas_ec' => $sensor_result->batas_atas_ec,
          'batas_bawah_ph' => $sensor_result->batas_bawah_ph,
          'batas_atas_ph' => $sensor_result->batas_atas_ph,
          'batas_bawah_temp' => $sensor_result->batas_bawah_temp,
          'batas_atas_temp' => $sensor_result->batas_atas_temp,
          'batas_bawah_humid' => $sensor_result->batas_bawah_humid,
          'batas_atas_humid' => $sensor_result->batas_atas_humid,
          'ec_status' => 'OK',
          'ph_status' => 'OK',
          'temp_status' => 'OK',
          'humid_status' => 'OK',
          'nilai' => 100
        ];
        
          if($sensor_result->ec_sensor < $sensor_result->batas_bawah_ec || $sensor_result->ec_sensor > $sensor_result->batas_atas_ec){
            $sensor['ec_status'] = 'Not OK';
            $sensor['nilai']-=25;
          }
          if($sensor_result->ph_sensor < $sensor_result->batas_bawah_ph || $sensor_result->ph_sensor > $sensor_result->batas_atas_ph){
            $sensor['ph_status'] = 'Not OK';
            $sensor['nilai']-=25;
          }
          if($sensor_result->temp_sensor < $sensor_result->batas_bawah_temp || $sensor_result->temp_sensor > $sensor_result->batas_atas_temp){
            $sensor['temp_status'] = 'Not OK';
            $sensor['nilai']-=25;
          }
          if($sensor_result->humid_sensor > $sensor_result->batas_bawah_humid || $sensor_result->humid_sensor < $sensor_result->batas_atas_humid){
            $sensor['humid_status'] = 'Not OK';
            $sensor['nilai']-=25;
          }
          ModelTanahnya::UpdateNilaiSensorAfterEdit($sensor['id'], $sensor['nilai']);;
        }
      }
      return response("Updated", 201);
    }

    public function RefreshSensor(Request $request){
      $list_alat = ModelTanahnya::ListSensors();
      
      //Yang dulu kek gini
      // $req = $this->client->get('192.168.1.200:80/refresh');
      // $response = $req->getStatusCode();
      // return $response;
      // $test = 'OK';
      // return response()->json($test);
      
      // Yang baru kek gini
      foreach($list_alat as $alat){
      // //   $targetArduinoStatus = "http://".$alat->ip_address.":80/status/"; 
        $targetRefresh = "http://".$alat->ip_address.":80/refresh/";

        try {
          $req = $this->client->get($targetRefresh);
        } catch (RequestException $e) {
            $status = 'Tidak Berjalan';
            ModelTanahnya::UpdateArduinoStatus($alat->id, $status);
            echo Psr7\str($e->getRequest());
            continue;
            if ($e->hasResponse()) {
              echo Psr7\str($e->getResponse());
          }
        }
        $status = 'Berjalan';
        ModelTanahnya::UpdateArduinoStatus($alat->id, $status);
      }
      $this->SendMessage();
      return response('berhasil refresh', 200);

      // Debug
      // print_r($list_alat);
      // echo ($list_alat[0]->nama_alat);
    }

    public function SendMessage() {
      $content = array(
          "en" => 'Pembacaan sensor telah diperbaharui silahkan cek aplikasi untuk melihat keadaan tanah anda.'
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
