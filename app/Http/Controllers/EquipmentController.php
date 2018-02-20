<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Equipment;
use Mockery\Exception;


class EquipmentController extends Controller {
    // Rreturns all the equipment instances
    public function index() {
        return Equipment::all();
    }

    public function show($id) {
        //return Equipment::find($id);
        $eq = Equipment::find($id);
        return response()->json($eq, 200);
    }

    public function store(Request $request) {

        $brand = BrandController::solveDependence($request->input('brand'));
        $type = TypeController::solveDependence($request->input('type'));


        if ($brand == null) {
          return response()->json(["error"=>"missing brand"], 400);
        }

        if ($type == null) {
          return response()->json(["error"=>"missing type"], 400);
        }

        try {
          $eq = Equipment::create(Array("brands"=>$brand,
              "types"=>$type,
              "description"=>$request->input('description'),
              "model"=>$request->input("model")));
        } catch (Exception $e) {
            return response()->json(["error"=>"malformed request: $e"], 400);
        }


        return response()->json(["success"=>"equipment added"], 201);
    }

    public function update(Request $request, $id) {
      $eq = Equipment::findOrFail($id);

      $brand = BrandController::solveDependence($request->input('brand'));
      $type = TypeController::solveDependence($request->input('type'));

      $eq->update(Array('brands'=>$brand,
      'types'=>$type,
      'description'=>$request->input('description'),
      'model'=>$request->input('model')));

      return response()->json(["success"=>"equipment updated"], 200);
    }

    public function delete(Request $request, $id) {
      $eq = Equipment::findOrFail($id);
      try {
          $eq->delete();
      } catch (Exception $e) {
          return response()->json(["error"=>"could not delete equipment. Remember that there cannot be any instances left"], 500);
      }
      return response()->json(Array("success"=>"Item deleted"), 204);
    }


    public function search(Request $response) {
        $brand = BrandController::solveDependence($response->input('brand'));
        $type = TypeController::solveDependence($response->input('type'));
        $model = $response->input('model');
        //error_log($brand, $type, $model);
        $parameters = Array();
        if ($brand != null)
            array_push($parameters, ['brands', '=', $brand]);

        if ($type != null)
            array_push($parameters, ['types', '=', $type]);

        if (count($model) > 0) {
            array_push($parameters, ['model', 'LIKE', "%$model%"]);
            //array_push($parameters, ['Description', 'LIKE', "%$model%"]);
        }



        return response()->json(Equipment::where($parameters)->get(), 200);
    }
}
