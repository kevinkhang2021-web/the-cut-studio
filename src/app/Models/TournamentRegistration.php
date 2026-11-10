<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentRegistration extends Model
{
    protected $fillable = ['tournament_id', 'user_id', 'gamer_tag', 'status', 'paid', 'rank'];
    protected $casts = ['paid' => 'boolean'];

    public function tournament() { return $this->belongsTo(Tournament::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function payments() { return $this->morphMany(Payment::class, 'payable'); }

    public function isPayable(): bool { return $this->status === 'pending'; }
    public function amountDue(): int { return (int) $this->tournament->fee_fc; }
    public function markPaid(): void { $this->update(['paid' => true, 'status' => 'confirmed']); }

    public function summaryLabel(): string
    {
        return 'Inscription au tournoi « ' . $this->tournament->title . ' »';
    }
}
