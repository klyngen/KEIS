<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Type;

class TypeController extends Controller
{
    public static function solveDependence($name) {
        if ($name == null) {
            return array();
        }

      $type = Type::select('id')->where('name', strtoupper($name))->get();

      if ($type->isNotEmpty()) {
        return $type[0]->id;
      }
      return Type::create(Array("name"=>strtoupper($name)))->id;
    }

    public function index() {

        try {
            return response()->json(['success'=>'OK', 'data'=> Type::all()], 200);    
        } catch (Exception $e) {
            return response()->json(['error'=>'could not find any brands'], 500);
        }
      
    }

}
