<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    public const CATEGORIES = ['Loyer', 'Électricité', 'Internet', 'Entretien / réparation',
                               'Accessoires', 'Jeux et licences', 'Salaires', 'Publicité', 'Autre'];

    protected $fillable = ['label', 'category', 'amount_fc', 'spent_on'];
    protected $casts = ['spent_on' => 'date'];
}
