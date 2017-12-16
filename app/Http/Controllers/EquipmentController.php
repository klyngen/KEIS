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
        $eq = Equipment::create($request->all());
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

      return response()->json(null, 204);
    }


}
