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
        //return Equipment::find($id);
        $eq = Equipment::find($id);
        return response()->json($eq, 200);
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

      //$eq->update($request->all());

      $brand = BrandController::solveDependence($request->input('brand'));
      $type = TypeController::solveDependence($request->input('type'));

      $eq->update(Array('brands'=>$brand,
      'types'=>$type,
      'description'=>$request->input('description'),
      'modhttps://www.google.com/search?q=laravel+builder+count&ie=utf-8&oe=utf-8&client=firefox-b-abel'=>$request->input('model')));



      return response()->json($eq, 200);
    }

    public function delete(Request $request, $id) {
      $eq = Equipment::findOrFail($id);
      $eq->delete();

      return response()->json(Array("success"=>"Item delet"), 204);
    }


    public function search(Request $response) {
        $brand = $response->input('brand');
        $type = $response->input('type');

        $model = $response->input('model');

        $searchArray = Array();

        if ($brand != null)
            array_add($searchArray, ['brand'=>$brand]);

        if ($type != null)
            array_add($searchArray, ['type'=>$type]);

        $result = Instances::join('equipment', 'equipment.id', '=', 'instances.equipment')
            ->where($searchArray)
            ->orWhere('model', 'like', '%'.$model.'%')->get();

        return response()->json($result, 200);
    }
}
