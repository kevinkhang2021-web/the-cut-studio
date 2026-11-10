<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['user_id', 'type', 'rating', 'message', 'response'];

    public function user() { return $this->belongsTo(User::class); }
}
