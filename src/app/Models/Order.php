<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'status', 'delivery_method', 'address', 'phone', 'total_fc', 'paid'];
    protected $casts = ['paid' => 'boolean'];

    public function user() { return $this->belongsTo(User::class); }
    public function items() { return $this->hasMany(OrderItem::class); }
    public function payments() { return $this->morphMany(Payment::class, 'payable'); }

    public function isPayable(): bool { return $this->status === 'pending'; }
    public function amountDue(): int { return (int) $this->total_fc; }
    public function markPaid(): void { $this->update(['paid' => true, 'status' => 'paid']); }

    public function summaryLabel(): string { return 'Commande #' . $this->id; }
}
