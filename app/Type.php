<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

/**
 * App\Type
 *
 * @mixin \Eloquent
 */
class Type extends Model
{
  protected $fillable = ['name'];
}
