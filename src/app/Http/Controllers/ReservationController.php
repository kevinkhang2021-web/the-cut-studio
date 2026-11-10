<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Reservation;
use App\Support\Studio;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $reservations = $request->user()->reservations()->with('equipment')->latest('starts_at')->paginate(10);
        return view('reservations.index', compact('reservations'));
    }

    public function create(Request $request)
    {
        $open = (int) Studio::setting('open_hour');
        $close = (int) Studio::setting('close_hour');

        try {
            $date = Carbon::parse($request->query('date', today()->toDateString()))->startOfDay();
        } catch (\Throwable $e) {
            $date = today();
        }
        if ($date->lt(today())) $date = today();
        if ($date->gt(today()->addDays(30))) $date = today()->addDays(30);

        $type = in_array($request->query('type'), ['PS5', 'PS4']) ? $request->query('type') : null;
        $equipments = Equipment::available()->when($type, fn ($q) => $q->where('type', $type))->orderBy('name')->get();

        // Créneaux déjà pris : [equipment_id][heure] = true
        $taken = [];
        $reservations = Reservation::blocking()
            ->whereIn('equipment_id', $equipments->pluck('id'))
            ->where('starts_at', '<', $date->copy()->addDay())
            ->where('ends_at', '>', $date)
            ->get();
        foreach ($reservations as $r) {
            for ($h = $r->starts_at->copy(); $h->lt($r->ends_at); $h->addHour()) {
                if ($h->isSameDay($date)) $taken[$r->equipment_id][$h->hour] = true;
            }
        }

        $selected = [
            'equipment_id' => (int) $request->query('equipment'),
            'hour'         => $request->query('hour') !== null ? (int) $request->query('hour') : null,
        ];

        return view('reservations.create', [
            'date' => $date, 'type' => $type, 'equipments' => $equipments, 'taken' => $taken,
            'hours' => range($open, max($open, $close - 1)), 'open' => $open, 'close' => $close,
            'selected' => $selected, 'hourRate' => Studio::hourRate(),
            'selectedEquipment' => $equipments->firstWhere('id', $selected['equipment_id']),
        ]);
    }

    public function store(Request $request)
    {
        $open = (int) Studio::setting('open_hour');
        $close = (int) Studio::setting('close_hour');

        $data = $request->validate([
            'date'         => 'required|date|after_or_equal:today',
            'equipment_id' => 'required|exists:equipment,id',
            'start_hour'   => 'required|integer|between:' . $open . ',' . max($open, $close - 1),
            'duration'     => 'required|integer|between:1,4',
            'note'         => 'nullable|string|max:200',
        ]);

        $start = Carbon::parse($data['date'])->setTime((int) $data['start_hour'], 0);
        $end = $start->copy()->addHours((int) $data['duration']);

        if ($start->lte(now())) return back()->withInput()->with('error', 'Ce créneau est déjà passé.');
        if ($end->gt($start->copy()->setTime($close, 0))) {
            return back()->withInput()->with('error', 'La session dépasse l\'heure de fermeture (' . $close . 'h).');
        }

        $reservation = DB::transaction(function () use ($request, $data, $start, $end) {
            // Verrou sur l'équipement : deux clients ne peuvent pas réserver le même créneau
            $equipment = Equipment::lockForUpdate()->find($data['equipment_id']);
            if (!$equipment->is_active || $equipment->isDown()) return null;

            $conflict = Reservation::blocking()
                ->where('equipment_id', $equipment->id)
                ->where('starts_at', '<', $end)
                ->where('ends_at', '>', $start)
                ->exists();
            if ($conflict) return null;

            return Reservation::create([
                'user_id'        => $request->user()->id,
                'equipment_id'   => $equipment->id,
                'starts_at'      => $start,
                'ends_at'        => $end,
                'duration_hours' => (int) $data['duration'],
                'total_fc'       => (int) $data['duration'] * Studio::hourRate(),
                'note'           => $data['note'] ?? null,
            ]);
        });

        if (!$reservation) {
            return back()->withInput()->with('error', 'Ce créneau n\'est plus disponible. Choisissez-en un autre.');
        }

        return redirect()->route('reservations.index')
            ->with('success', 'Réservation envoyée ! Le gérant va la confirmer. Vous pouvez payer en ligne ou sur place.');
    }

    public function cancel(Request $request, Reservation $reservation)
    {
        abort_unless($reservation->user_id === $request->user()->id, 403);
        if (!in_array($reservation->status, ['pending', 'confirmed']) || $reservation->starts_at->lte(now())) {
            return back()->with('error', 'Cette réservation ne peut plus être annulée.');
        }
        $reservation->update(['status' => 'cancelled']);
        $msg = $reservation->paid
            ? 'Réservation annulée. Le remboursement du paiement est à régler avec le gérant.'
            : 'Réservation annulée.';
        return back()->with('success', $msg);
    }
}
