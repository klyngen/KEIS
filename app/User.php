<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Rent;

/**
 * App\User
 *
 * @property-read mixed $rented
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'studentNumber',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = ['rented'];

    public function getRentedAttribute() {
        $rented = 1;
        $rented = Instance::join('rents', 'rents.instances', '=', 'instances.id')
            ->where('rented', '=', 1)->where('users', '=', $this->attributes['id'])->count();
        return $rented;
    }
}
