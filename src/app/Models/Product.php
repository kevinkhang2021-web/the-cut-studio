<?php

namespace App\Models;

use App\Support\Studio;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public const CATEGORIES = ['console' => 'Consoles', 'jeu' => 'Jeux PS4', 'accessoire' => 'Accessoires'];

    protected $fillable = ['name', 'category', 'platform', 'description', 'price_usd', 'stock', 'image', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function getPriceFcAttribute(): int
    {
        return (int) round($this->price_usd * Studio::rate());
    }

    public function getGammeAttribute(): string
    {
        $fc = $this->price_fc;
        if ($fc <= 92000) return 'petits';
        if ($fc <= 138000) return 'moyens';
        return 'eleves';
    }

    public function getGammeLabelAttribute(): string
    {
        return ['petits' => 'Petit prix', 'moyens' => 'Prix moyen', 'eleves' => 'Prix élevé'][$this->gamme];
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset($this->image) : null;
    }

    public function scopeActive($q) { return $q->where('is_active', true); }
}
