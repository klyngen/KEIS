<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Brand;

class BrandController extends Controller
{
    public static function solveDependence($b) {
      $brand = Brand::select('id')->where('name', strtoupper($b))->get();

      if ($brand->isNotEmpty()) {
        return $brand[0]->id;
      }
      
      return Brand::create(Array("name"=>strtoupper($b)))->id;
    }
}
