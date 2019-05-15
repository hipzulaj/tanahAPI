<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ModelTanahnya extends Model
{
    const CREATED_AT = null;
    const UPDATED_AT = 'waktu_diambil';

    protected $table = 'pembacaan_sensor';

    public static function presentCondition(){
      $present = DB::table('pembacaan_sensor')->orderBy('waktu_diambil', 'desc')->first();
      return $present;
    }

    public static function sensorRead($jenis_sensor){
      $sensor_logs = DB::table('pembacaan_sensor')->select('id', $jenis_sensor, 'waktu_diambil')->get();
      return $sensor_logs;
    }

    public static function optimumIndicator($nama_tanaman){
      $indikator = DB::table('optimal_tanaman')->where('nama_tanaman', $nama_tanaman)->get(); //select('id', $nama_tanaman, 'batas_bawah_ec', 'batas_atas_ec','batas_bawah_ph', 'batas_atas_ph','batas_bawah_temp', 'batas_atas_temp','batas_bawah_humid','batas_atas_humid')
      return $indikator[0];
    }
}
