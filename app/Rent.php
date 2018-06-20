<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Rent
 *
 * @mixin \Eloquent
 */
class Rent extends Model
{
    protected $fillable = ['users', 'instances', 'stop'];
    protected $appends = ['equipment', 'instance', 'user'];


    protected function getEquipmentAttribute() {
        return Equipment::where('id', $this->instance()->attributes['equipment'])->first();
    }


    protected function getInstanceAttribute() {
        return $this->instance();
    }

    private function instance() {
        $instance = Instance::where('id', $this->attributes['instances'])->first();
        return $instance;
    }

    protected function getUserAttribute() {
        return User::where('id', $this->attributes['users'])->first();
    }

}
