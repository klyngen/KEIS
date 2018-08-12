<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\User;
use App\stdClass;

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
    protected $appends = ['hours', 'user'];

    protected function getHoursAttribute() {
        $start = Carbon::parse($this->created_at);
        $stop = Carbon::parse($this->stop);

        return $start->diffInHours($stop);
    }

    protected function getUserAttribute() {
        $user = User::whereNotNull('rfid')->where('rfid', $this->rfid)->first();
        if ($user == null) {
            $user = (object) ["name"=>"anonymous", "studentNumber"=>"√-1"];
        }
        return $user;
    }
}
