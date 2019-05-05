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
}
