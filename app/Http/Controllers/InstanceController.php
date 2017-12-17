<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Instance;
use App\Equipment;

class InstanceController extends Controller
{
    public function getAllEquipment(Request $request, $id) {
      $in = Instance::select()->where('equipment', $id)->get();

      if ($in->isNotEmpty()) {
        return response()->json($id[0], 200);
      }
      return response()->json(Array("error"=>"could not find any instances"), 404);
    }

    public function store(Request $request) {
      $id = Equipment::where('id', $request->input('equipment'))->get();

      // See if equipment exists
      if (!$id->isNotEmpty()) {
        return response()->json(Array("error"=>"Non-existent equipment-id"), 404);
      }

      // See if RFID exists
      if ($this->RFIDExists($request->input('RFID'))) {
        return response()->json(Array("error"=>"RFID already exists"),400);
      }

      $id = $id[0]->id;

      $instance = Instance::create(Array("equipment"=>$id,
      "RFID"=>$request->input("RFID"),
      "condition"=>$request->input("condition"),
      "purchasetime"=>$request->input("purchasetime")));

      return response()->json($instance, 201);
    }

    public function RFIDExists($rfid) {
      $result = Instance::where("RFID", $rfid)->get();

      if ($result->isNotEmpty()) {
        return true;
      }
      return false;
    }
}
