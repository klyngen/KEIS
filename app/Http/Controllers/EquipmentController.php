<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Equipment;


class EquipmentController extends Controller {
    // Rreturns all the equipment instances
    public function index() {
        return Equipment::all();
    }

    public function show($id) {
        return Equipment::find($id);
    }

    public function store(Request $request) {
        $data = $request->json()->all();
        //return Array($request->input('model'));

        $brand = BrandController::solveDependence($request->input('brand'));
        $type = TypeController::solveDependence($request->input('type'));
        //return $brand;

        $eq = Equipment::create(Array("brands"=>$brand,
            "types"=>$type,
            "description"=>$request->input('description'),
            "model"=>$request->input("model")));

        return response()->json($eq, 201);
    }

    public function update(Request $request, $id) {
      $eq = Equipment::findOrFail($id);
      $eq->update($request->all());


      return response()->json($eq, 200);
    }

    public function delete(Request $request, $id) {
      $eq = Equipment::findOrFail($id);
      $eq->delete();

      return response()->json(Array("error"=>"Could not delete the item"), 204);
    }
}
