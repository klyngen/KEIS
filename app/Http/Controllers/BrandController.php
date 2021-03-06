<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Brand;

class BrandController extends Controller
{
    public static function solveDependence($b) {

      if ($b == null) {
        return array();
      }

      \Log::info("This is the parameter".gettype($b));
      // Fetch brands
      $brand = Brand::select('id')->where('name', strtoupper($b))->get();


      if ($brand->isNotEmpty()) { // If brands have been found
        return $brand[0]->id;
      }

      // If no brands have been found
      return Brand::create(Array("name"=>strtoupper($b)))->id;
    }

    public function index() {
        try {
            return response()->json(['success'=>'OK', 'data'=>Brand::all()], 200);    
        } catch (Exception $e) {
            return response()->json(['error'=>'could not find any brands'], 500);
        }
        
    }
}
