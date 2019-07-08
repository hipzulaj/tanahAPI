<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ModelTanahnya extends Model
{
    const CREATED_AT = null;
    const UPDATED_AT = 'waktu_diambil';

    protected $table = 'pembacaan_sensor';

    public static function NewSensorRead($data){
      DB::table('pembacaan_sensor')->insert($data);
    }

    public static function presentCondition($nama_alat, $nama_tanaman){
      $present = DB::table('sensors')
                  ->select('sensors.id', 'sensors.nama_alat', 'sensors.nama_tanaman', 'nilai', 'Status', 'ec_sensor', 'ph_sensor', 'temp_sensor', 'humid_sensor', 'waktu_diambil', 'batas_bawah_ec', 'batas_atas_ec', 'batas_bawah_ph', 'batas_atas_ph','batas_bawah_temp', 'batas_atas_temp','batas_bawah_humid', 'batas_atas_humid')
                  ->join('pembacaan_sensor', 'sensors.nama_alat', '=', 'pembacaan_sensor.nama_alat')
                  ->join('optimal_tanaman', 'sensors.nama_tanaman', '=','optimal_tanaman.nama_tanaman')
                  ->where('pembacaan_sensor.nama_alat', $nama_alat)
                  ->where('optimal_tanaman.nama_tanaman', $nama_tanaman)
                  ->orderBy('waktu_diambil', 'desc')
                  ->first();
      if($present==null) return false;
      else{
        return $present;
      }
    }
    
    public static function UpdateNilaiSensor($data){
      $result = DB::table('sensors')
          ->select('sensors.id', 'sensors.nama_tanaman', 'Status', 'batas_bawah_ec', 'batas_atas_ec', 'batas_bawah_ph', 'batas_atas_ph','batas_bawah_temp', 'batas_atas_temp','batas_bawah_humid', 'batas_atas_humid')
          ->join('optimal_tanaman', 'sensors.nama_tanaman', '=','optimal_tanaman.nama_tanaman')
          ->where('sensors.nama_alat', $data['nama_alat'])
          ->get();
      
      if($result[0]->Status == "Tidak Berjalan"){
        DB::table('sensors')->where('nama_alat', $data['nama_alat'])->update(['nilai' => 0]);
      }
      else if($result[0]->Status == "Berjalan"){
        $sensor = [
          'nilai' => 100
        ];
        
        if($data['ec_sensor'] < $result[0]->batas_bawah_ec || $data['ec_sensor'] > $result[0]->batas_atas_ec){
          $sensor['nilai']-=25;
        }
        if($data['ph_sensor'] < $result[0]->batas_bawah_ph || $data['ph_sensor'] > $result[0]->batas_atas_ph){
          $sensor['nilai']-=25;
        }
        if($data['temp_sensor'] < $result[0]->batas_bawah_temp || $data['temp_sensor'] > $result[0]->batas_atas_temp){
          $sensor['nilai']-=25;
        }
        if($data['humid_sensor'] > $result[0]->batas_bawah_humid || $data['humid_sensor'] < $result[0]->batas_atas_humid){
          $sensor['nilai']-=25;
        }
        DB::table('sensors')->where('nama_alat', $data['nama_alat'])->update(['nilai' => $sensor['nilai']]);
      }
    }

    public static function SearchSensorByNamaTanaman($nama_tanaman){
      $result = DB::table('sensors')
                ->select('nama_alat')
                ->join('optimal_tanaman', 'sensors.nama_tanaman', '=','optimal_tanaman.nama_tanaman')
                ->where('sensors.nama_tanaman',$nama_tanaman)
                ->get();
      if($result==null) return false;
      else return $result;
    }

    public static function UpdateNilaiSensorAfterEdit($id, $nilai){
      $result = DB::table('sensors')
          ->where('id', $id)
          ->update(['nilai' => $nilai]);
    }

    public static function sensorRead($jenis_sensor){
      $sensor_logs = DB::table('pembacaan_sensor')->select('id', $jenis_sensor, 'waktu_diambil')->get();
      return $sensor_logs;
    }

    public static function optimumIndicator($nama_tanaman){
      $indikator = DB::table('optimal_tanaman')->where('nama_tanaman', $nama_tanaman)->get(); //select('id', $nama_tanaman, 'batas_bawah_ec', 'batas_atas_ec','batas_bawah_ph', 'batas_atas_ph','batas_bawah_temp', 'batas_atas_temp','batas_bawah_humid','batas_atas_humid')
      return $indikator[0];
    }

    public static function listTanaman(){
      $list = DB::table('optimal_tanaman')->get();
      return $list;
    }

    public static function AddNewTanaman($data){
      DB::table('optimal_tanaman')->insert($data);
    }

    public static function getTanamanById($id){
      $data = DB::table('optimal_tanaman')->where('id',$id)->get();
      return $data;
    }

    public static function UpdateIndikatorTanaman($id, $data){
      DB::table('optimal_tanaman')->where('id',$id)->update($data);
    }

    public static function RemoveTanamanFromDB($id){
      $user = DB::table('optimal_tanaman')
              ->where('id', $id)
              ->delete();
    }

    public static function AddSensorsToDB($data){
      DB::table('sensors')->insert($data);
    }

    public static function ListSensors(){
      $list = DB::table('sensors')->get();
      return $list;
    }

    public static function getSensorById($id){
      $data = DB::table('sensors')->where('id',$id)->get();
      return $data;
    }

    public static function UpdateSensorDetail($id, $data){
      DB::table('sensors')->where('id',$id)->update($data);
    }

    public static function removeArduino($id){
      $user = DB::table('sensors')
              ->where('id', $id)
              ->delete();
    }

    public static function UpdateArduinoStatus($id_alat, $status){
      DB::table('sensors')->where('id',$id_alat)->update(['Status' => $status]);
      return true;
    }

    public static function AuthorizeArduino($nama_alat, $ip_address){
      $count = DB::table('sensors')
                  ->where('nama_alat', $nama_alat)
                  ->where('ip_address',$ip_address)
                  ->count();
      if($count == 1) return "true";
      else return "false";
    }

    public static function isAdmin($username){
      $user = DB::table('users')
              ->select('username','role')
              ->where('username', $username)->get();
      if($user->isEmpty()) {
        return false;
      }
      else if($user[0]->role == 'admin') return true;
      else return false;
    }

    public static function ListAllUsers(){
      $list = DB::table('users')->get();
      return $list;
    }

    public static function RemoveUserFromDB($id){
      DB::table('users')
      ->where('id', $id)->delete();
    }
    
    public static function MakeAdmin($id){
      $user = DB::table('users')
              ->where('id', $id)->update(['role' => 'admin']);
    }

    public static function RemoveAdmin($id){
      $user = DB::table('users')
              ->where('id', $id)->update(['role' => 'users']);
    }
}
