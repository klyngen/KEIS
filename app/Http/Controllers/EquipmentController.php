<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Equipment;
use Mockery\Exception;


class EquipmentController extends Controller {
    // Rreturns all the equipment instances
    public function index() {
        try {
            return response()->json(['success'=> 'OK', 'data'=>Equipment::all()], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    public function show($id) {
        //return Equipment::find($id);
        try {
            $eq = Equipment::find($id);
            $eq->instances = $eq->instances(); 
            //$eq->instances = $eq::instances();
            return response()->json(['success'=> 'OK', 'data'=>$eq], 200);
        } catch (Exception $e) {
            return response()->json(['error'=>'could not fetch the equipment'], 500);
        }
    }

    public function store(Request $request) {

        $this->validate($request, ['model' => 'required', 'brands'=>'required', 'types'=>'required']);

        $brand = BrandController::solveDependence($request->input('brands'));
        $type = TypeController::solveDependence($request->input('types'));


        if ($brand == null) {
          return response()->json(["error"=>"missing brand"], 400);
        }

        if ($type == null) {
          return response()->json(["error"=>"missing type"], 400);
        }


        try {
            $eq = new Equipment();
            $eq->model = $request->input('model');
            $eq->brands = $brand;
            $eq->types = $type;
            if ($request->input('Description') == null) {
                $eq->Description = '';
            } else {
                $eq->Description = $request->input('Description');
            }
            $eq->save();

        } catch (Exception $e) {
            return response()->json(["error"=>"malformed request: $e"], 400);
        }


        return response()->json(["success"=>"equipment added", "data"=>$eq], 201);
    }

    public function update(Request $request, $id) {
      $eq = Equipment::findOrFail($id);

      $brand = BrandController::solveDependence($request->input('brands'));
      $type = TypeController::solveDependence($request->input('types'));

      $eq->update(Array('brands'=>$brand,
      'types'=>$type,
      'description'=>$request->input('Description'),
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

        

        if ($model != null){
            array_push($parameters, ['model', 'LIKE', "%$model%"]);
        }



        return response()->json(Equipment::where($parameters)->get(), 200);
    }
}
