<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Service de paiement.
 *
 * ATTENTION : le paiement mobile est ici SIMULÉ (il réussit toujours), ce qui permet
 * de tester toute la chaîne (réservation -> paiement -> reçu) sans prestataire.
 * Pour brancher un vrai prestataire (M-Pesa, Airtel Money, Orange Money...), il suffit
 * de remplacer le bloc "// >>> APPEL PRESTATAIRE" par l'appel à son API et de
 * ne créer le paiement "success" que si le prestataire confirme la transaction.
 */
class PaymentService
{
    public function pay(Model $payable, string $method, ?string $phone = null): Payment
    {
        return DB::transaction(function () use ($payable, $method, $phone) {
            // >>> APPEL PRESTATAIRE (simulation : toujours accepté)
            $accepted = true;
            // <<< FIN

            $payment = Payment::create([
                'user_id'      => $payable->user_id,
                'payable_type' => $payable->getMorphClass(),
                'payable_id'   => $payable->getKey(),
                'method'       => $method,
                'phone'        => $phone,
                'amount_fc'    => $payable->amountDue(),
                'reference'    => 'PAY-' . strtoupper(Str::random(8)),
                'status'       => $accepted ? 'success' : 'failed',
            ]);

            if ($accepted) {
                $payable->markPaid();
            }

            return $payment;
        });
    }
}
