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

      if (is_array($result)) {
          $instance = Instance::create($result);
          return response()->json(["success"=>"instance added"], 201);
      } else {
          return response()->json(["error"=>$result], 400);
      }


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
        return "Non-existent equipment-id";
      }

      // See if RFID exists
      if ($this->RFIDExists($request->input('RFID')) && !$update) {
        return "RFID already exists";
      }

      $id = $id[0]->id;
      $instance = Array("equipment"=>$id,
      "RFID"=>$request->input("RFID"),
      "condition"=>$request->input("condition"),
      "purchasetime"=>$request->input("purchasetime"));

      return $instance;
    }

    public function delete(Request $request) {
        $instance = Instance::where('RFID', '=', $request->input("RFID"))->first();
        if ($instance == null) {
            return response()->json(["error"=>"could not find the instance"], 404);
        }
        $instance->delete();
        return response()->json(["success"=>"deleted the instance with ID {$instance->id}"], 200);
    }

    public function rfid(Request $request) {
        error_log("some data".$request->input("RFID"));
        error_log($request);
        $instance = Instance::where('RFID', '=', $request->input("RFID"))->first();
        if ($instance == null) {
            return response()->json(["error"=>"could not find the instance"], 404);
        }
        return $instance;
    }

}
