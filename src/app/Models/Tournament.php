<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    protected $fillable = ['title', 'game', 'starts_at', 'fee_fc', 'max_players', 'description', 'status'];
    protected $casts = ['starts_at' => 'datetime'];

    public function registrations() { return $this->hasMany(TournamentRegistration::class); }

    public function activeRegistrations()
    {
        return $this->registrations()->where('status', '!=', 'cancelled');
    }

    public function spotsLeft(): int
    {
        return max(0, $this->max_players - $this->activeRegistrations()->count());
    }
}
