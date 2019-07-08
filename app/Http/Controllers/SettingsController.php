<?php

namespace App\Http\Controllers;

use App\ModelTanahnya;
use Illuminate\Http\Request;

class SettingsController extends Controller
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

    public function showListTanaman(Request $request){
      $list = ModelTanahnya::listTanaman();
      return response()->json($list);
    }

    public function AddNewTanamanToDB(Request $request){
      $username = $request->header('username');
      $result = ModelTanahnya::isAdmin($username);
      if($result){
        $data = array(
          "nama_tanaman" => $request->json()->get('nama_tanaman'),
          "batas_bawah_ec" => $request->json()->get('batas_bawah_ec'),
          "batas_atas_ec" => $request->json()->get('batas_atas_ec'),
          "batas_bawah_ph" => $request->json()->get('batas_bawah_ph'),
          "batas_atas_ph" => $request->json()->get('batas_atas_ph'),
          "batas_bawah_temp" => $request->json()->get('batas_bawah_temp'),
          "batas_atas_temp" => $request->json()->get('batas_atas_temp'),
          "batas_bawah_humid" => $request->json()->get('batas_bawah_humid'),
          "batas_atas_humid" => $request->json()->get('batas_atas_humid'),
        );
        ModelTanahnya::AddNewTanaman($data);
        return response("OK", 201);
      }
      else return response("Unauthorized", 401);
    }

    public function editTanaman(Request $request, $id){
      $username = $request->header('username');
      $result = ModelTanahnya::isAdmin($username);
      if($result){
        $data = ModelTanahnya::getTanamanById($id);
        return response()->json($data);
      }
      else return response("Unauthorized", 401);
    }

    public function editTanamanToDB(Request $request, $id){
      $username = $request->header('username');
      $result = ModelTanahnya::isAdmin($username);
      if($result){
        $data = array(
          "nama_tanaman" => $request->json()->get('nama_tanaman'),
          "batas_bawah_ec" => $request->json()->get('batas_bawah_ec'),
          "batas_atas_ec" => $request->json()->get('batas_atas_ec'),
          "batas_bawah_ph" => $request->json()->get('batas_bawah_ph'),
          "batas_atas_ph" => $request->json()->get('batas_atas_ph'),
          "batas_bawah_temp" => $request->json()->get('batas_bawah_temp'),
          "batas_atas_temp" => $request->json()->get('batas_atas_temp'),
          "batas_bawah_humid" => $request->json()->get('batas_bawah_humid'),
          "batas_atas_humid" => $request->json()->get('batas_atas_humid'),
        );
        ModelTanahnya::UpdateIndikatorTanaman($id, $data);
        return response("OK", 201);
      }
      else return response("Unauthorized", 401);
    }

    public function RemoveTanaman(Request $request, $id){
      $username = $request->header('username');
      $result = ModelTanahnya::isAdmin($username);
      if($result){
        ModelTanahnya::RemoveTanamanFromDB($id);
        return response("OK", 200);
      }
      else return response("Unauthorized", 401);
    }

    //User Management
    public function ShowUsers(Request $request){
      $username = $request->header('username');
      $result = ModelTanahnya::isAdmin($username);
      if($result){
        $list = ModelTanahnya::ListAllUsers();
        return response()->json($list);
      }
      else return response("Unauthorized", 401);
    }

    public function RemoveUsers(Request $request, $id){
      $username = $request->header('username');
      $result = ModelTanahnya::isAdmin($username);
      if($result){
        ModelTanahnya::RemoveUserFromDB($id);
        return response("OK", 200);
      }
      else return response("Unauthorized", 401);
    }

    public function MakeAsAdmin(Request $request, $id){
      $username = $request->header('username');
      $result = ModelTanahnya::isAdmin($username);
      if($result){
        ModelTanahnya::MakeAdmin($id);
        return response("OK", 200);
      }
      else return response("Unauthorized", 401);
    }

    public function DeleteFromAdmin(Request $request, $id){
      $username = $request->header('username');
      $result = ModelTanahnya::isAdmin($username);
      if($result){
        ModelTanahnya::RemoveAdmin($id);
        return response("OK", 200);
      }
      else return response("Unauthorized", 401);
    }
}
