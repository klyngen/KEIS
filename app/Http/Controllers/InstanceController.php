<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Instance;
use App\Equipment;

class InstanceController extends Controller
{
    public function getAllEquipment(Request $request, $id) {
      $in = Instance::where('equipment', $id)->get();

      if ($in->isNotEmpty()) {
        return response()->json($in, 200);
      }
      return response()->json(Array("error"=>"could not find any instances"), 404);
    }

    public function store(Request $request) {
      $result = $this->createDataArray($request);

      //dd($result);

      if (is_array($result)) {
        $instance = Instance::create($result);
        return response()->json($instance->id, 201);
      }

      return response()->json($result, 400);
    }

    public function RFIDExists($rfid) {
      $result = Instance::where("RFID", $rfid)->get();

      if ($result->isNotEmpty()) {
        return true;
      }
      return false;
    }

    public function update(Request $request, $id) {
      $result = $this->createDataArray($request, true);
      $instance = Instance::where('id', $id)->firstOrFail();
      $rfid = $request->input('rfid');

      if (is_array($result)) {
        $instance->update($result);
        return response()->json(Array("Success"=>"Instance updated"), 201);
      }

      return response()->json($result, 201);
    }

    private function createDataArray(Request $request, $update=false) {
      $id = Equipment::where('id', $request->input('equipment'))->get();

      // See if equipment exists
      if (!$id->isNotEmpty()) {
        return response()->json(Array("error"=>"Non-existent equipment-id"), 404);
      }

      // See if RFID exists
      if ($this->RFIDExists($request->input('RFID')) && !$update) {
        return response()->json(Array("error"=>"RFID already exists"),400);
      }

      $id = $id[0]->id;
      $instance = Array("equipment"=>$id,
      "RFID"=>$request->input("RFID"),
      "condition"=>$request->input("condition"),
      "purchasetime"=>$request->input("purchasetime"));

      return $instance;
    }
}
