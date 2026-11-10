<?php

namespace App\Support;

use App\Models\Setting;

class Studio
{
    public const DEFAULTS = [
        'usd_rate'     => '2300',
        'hour_rate_fc' => '2000',
        'open_hour'    => '9',
        'close_hour'   => '21',
        'phone'        => '',
        'whatsapp'     => '',
        'address'      => 'Kasapa, Lubumbashi (RDC)',
    ];

    public const METHODS = [
        'mpesa'  => 'M-Pesa',
        'airtel' => 'Airtel Money',
        'orange' => 'Orange Money',
        'cash'   => 'Espèces',
    ];

    public const LABELS = [
        'pending' => 'En attente', 'confirmed' => 'Confirmé', 'refused' => 'Refusé',
        'cancelled' => 'Annulé', 'done' => 'Terminé', 'paid' => 'Payé', 'prepared' => 'Préparé',
        'delivered' => 'Livré', 'open' => 'Ouvert', 'closed' => 'Clos', 'finished' => 'Terminé',
        'success' => 'Réussi', 'failed' => 'Échoué', 'review' => 'Avis', 'complaint' => 'Réclamation',
        'pickup' => 'Retrait en salle', 'delivery' => 'Livraison',
    ];

    public static function setting(string $key)
    {
        return Setting::get($key, self::DEFAULTS[$key] ?? null);
    }

    public static function rate(): float
    {
        return max(1, (float) self::setting('usd_rate'));
    }

    public static function hourRate(): int
    {
        return (int) self::setting('hour_rate_fc');
    }

    public static function fc($amount): string
    {
        return number_format((float) $amount, 0, ',', ' ') . ' FC';
    }

    public static function label(?string $status): string
    {
        return self::LABELS[$status] ?? (string) $status;
    }

    public static function badge(?string $status): string
    {
        $class = match ($status) {
            'confirmed', 'paid', 'success', 'open', 'done', 'delivered', 'finished' => 'ok',
            'refused', 'cancelled', 'failed' => 'bad',
            'pending' => 'warn',
            default => 'info',
        };
        return '<span class="badge ' . $class . '">' . e(self::label($status)) . '</span>';
    }
}
