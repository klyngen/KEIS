<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Equipment extends Model
{
    protected $fillTable = ["brand", "type", "model", "description"];

}