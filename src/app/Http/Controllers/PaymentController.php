<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\TournamentRegistration;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    private const TYPES = [
        'reservation' => Reservation::class,
        'order'       => Order::class,
        'tournament'  => TournamentRegistration::class,
    ];

    private function resolve(Request $request, string $type, int $id)
    {
        abort_unless(isset(self::TYPES[$type]), 404);
        $payable = self::TYPES[$type]::findOrFail($id);
        abort_unless($payable->user_id === $request->user()->id, 403);
        return $payable;
    }

    public function show(Request $request, string $type, int $id)
    {
        $payable = $this->resolve($request, $type, $id);
        if ($payable->paid) {
            $payment = $payable->payments()->where('status', 'success')->latest()->first();
            if ($payment) return redirect()->route('payments.receipt', $payment);
        }
        if (!$payable->isPayable()) {
            return redirect()->route('account')->with('error', 'Ce paiement n\'est plus possible (élément annulé ou refusé).');
        }
        return view('payments.form', ['payable' => $payable, 'type' => $type]);
    }

    public function process(Request $request, PaymentService $service, string $type, int $id)
    {
        $payable = $this->resolve($request, $type, $id);
        abort_if($payable->paid, 422, 'Déjà payé.');
        abort_unless($payable->isPayable(), 422, 'Paiement impossible pour cet élément.');

        $data = $request->validate([
            'method' => 'required|in:mpesa,airtel,orange',
            'phone'  => 'required|regex:/^\+?[0-9 ]{9,16}$/',
        ]);
        $payment = $service->pay($payable, $data['method'], preg_replace('/\s+/', '', $data['phone']));

        return redirect()->route('payments.receipt', $payment)->with('success', 'Paiement effectué avec succès.');
    }

    public function receipt(Request $request, Payment $payment)
    {
        abort_unless($payment->user_id === $request->user()->id || $request->user()->is_admin, 403);
        $payment->load('payable', 'user');
        return view('payments.receipt', compact('payment'));
    }
}
