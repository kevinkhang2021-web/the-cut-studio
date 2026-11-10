<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'phone', 'email', 'password', 'is_admin'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['is_admin' => 'boolean', 'password' => 'hashed'];

    public function reservations() { return $this->hasMany(Reservation::class); }
    public function orders() { return $this->hasMany(Order::class); }
    public function payments() { return $this->hasMany(Payment::class); }
    public function registrations() { return $this->hasMany(TournamentRegistration::class); }
    public function reviews() { return $this->hasMany(Review::class); }
}
