<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Equipment extends Model
{
    protected $fillTable = ["brands", "types", "model", "description"];
    protected $fillable = ["brands", "types", "model", "description"];

    public function instances() {
        return $this->hasMany('App\Instance');
    }

    public function brand() {
        return $this->hasOne('App\Brand');
    }

    public function type() {
        return $this->hasOne('App\Type');
    }

}
