<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

/**
 * App\Brand
 *
 * @mixin \Eloquent
 */
class Brand extends Model
{
    protected $visible = ['id', 'name'];
    protected $fillable = ['name'];
}
