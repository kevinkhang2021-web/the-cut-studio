<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $table = 'equipment';
    protected $fillable = ['name', 'type', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function incidents() { return $this->hasMany(Incident::class); }
    public function reservations() { return $this->hasMany(Reservation::class); }

    public function isDown(): bool
    {
        return $this->incidents()->where('resolved', false)->exists();
    }

    /** Équipements actifs et sans panne en cours. */
    public function scopeAvailable($q)
    {
        return $q->where('is_active', true)
                 ->whereDoesntHave('incidents', fn ($i) => $i->where('resolved', false));
    }
}
