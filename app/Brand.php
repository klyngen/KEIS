<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Brand extends Model
{
    protected $visible = ['id', 'name'];
    protected $fillable = ['name'];
}
