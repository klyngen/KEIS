<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Instance
 *
 * @mixin \Eloquent
 */
class Instance extends Model
{
    protected $fillable = ['condition', 'purchasetime', "RFID", "equipment"];

}
