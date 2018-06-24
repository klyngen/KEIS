<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
    protected $appends = ['hours'];

    protected function getHoursAttribute() {
        $start = Carbon::parse($this->created_at);
        $stop = Carbon::parse($this->stop);

        return $start->diffInHours($stop);
    }
}
