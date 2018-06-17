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
        return response()->json(Type::all(), 200);
    }

}
