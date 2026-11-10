<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    protected $fillable = ['equipment_id', 'description', 'reported_on', 'resolved'];
    protected $casts = ['reported_on' => 'date', 'resolved' => 'boolean'];

    public function equipment() { return $this->belongsTo(Equipment::class); }
}
