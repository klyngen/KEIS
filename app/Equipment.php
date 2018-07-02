<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Brand;
use App\Type;
use App\Instance;

/**
 * App\Equipment
 *
 * @property-read mixed $available
 * @property-read mixed $brands
 * @property-read mixed $rented
 * @property-read mixed $total
 * @property-read mixed $types
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Instance[] $instances
 * @mixin \Eloquent
 */
class Equipment extends Model
{
    protected $appends = ["types", "brands", "available", "total", "rented"];
    protected $fillTable = ["brands", "types", "model", "description"];
    protected $fillable = ["brands", "types", "model", "description"];

    public function instances() {
        return Instance::where('equipment', '=', $this->id)->get();
        //return $this->hasMany('App\Instance');
    }

    public function getBrandsAttribute() {
        return Brand::select('name')->where('id', '=', $this->attributes['brands'])->first()->name;

    }

    public function getTypesAttribute() {
        return Type::select('name')->where('id', '=', $this->attributes['types'])->first()->name;
    }

    public function getAvailableAttribute() {
        $available = Instance::where('rented', '=', 0)->where('equipment', '=', $this->attributes['id'])->count();
        error_log($available);
        return $available;
    }

    public function getTotalAttribute() {
        return Instance::where('equipment', '=', $this->attributes['id'])->count();
    }

    public function getRentedAttribute() {
        $rented = Instance::where('rented', '=', 1)->where('equipment', '=', $this->attributes['id'])->count();
        return $rented;
    }

}
