<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $reservations = Reservation::with('user', 'equipment')
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->date, fn ($q, $d) => $q->whereDate('starts_at', $d))
            ->orderByDesc('starts_at')->paginate(20)->withQueryString();
        return view('admin.reservations.index', compact('reservations'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $data = $request->validate(['status' => 'required|in:confirmed,refused,cancelled,done']);
        $reservation->update($data);
        return back()->with('success', 'Réservation #' . $reservation->id . ' mise à jour.');
    }

    /** Encaissement en espèces sur place. */
    public function cash(Reservation $reservation, PaymentService $service)
    {
        if ($reservation->paid) return back()->with('error', 'Déjà payée.');
        $service->pay($reservation, 'cash');
        return back()->with('success', 'Paiement en espèces enregistré.');
    }
}
