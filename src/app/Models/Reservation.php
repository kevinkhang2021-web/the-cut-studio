<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = ['user_id', 'equipment_id', 'starts_at', 'ends_at', 'duration_hours',
                           'status', 'total_fc', 'paid', 'note'];
    protected $casts = ['starts_at' => 'datetime', 'ends_at' => 'datetime', 'paid' => 'boolean'];

    public function user() { return $this->belongsTo(User::class); }
    public function equipment() { return $this->belongsTo(Equipment::class); }
    public function payments() { return $this->morphMany(Payment::class, 'payable'); }

    /** Réservations qui bloquent un créneau. */
    public function scopeBlocking($q) { return $q->whereIn('status', ['pending', 'confirmed']); }

    public function isPayable(): bool { return in_array($this->status, ['pending', 'confirmed']); }
    public function amountDue(): int { return (int) $this->total_fc; }
    public function markPaid(): void { $this->update(['paid' => true]); }

    public function summaryLabel(): string
    {
        return 'Réservation #' . $this->id . ' – ' . $this->equipment->name . ' – '
            . $this->starts_at->format('d/m/Y H\h') . ' à ' . $this->ends_at->format('H\h');
    }
}
