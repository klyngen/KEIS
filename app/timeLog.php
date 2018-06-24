<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\timeLog
 *
 * @mixin \Eloquent
 */
class timeLog extends Model
{
    //
    protected $fillable = ['rfid', 'stop'];
    protected $table = 'timeLogs';
}
