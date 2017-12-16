<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Instance;

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

    }
}
