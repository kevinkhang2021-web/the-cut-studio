<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\Expense;
use App\Models\Incident;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\User;
use App\Support\Studio;

class DashboardController extends Controller
{
    public function index()
    {
        $today = today();
        $monthStart = $today->copy()->startOfMonth();
        $paid = fn () => Payment::where('status', 'success');

        $revenueToday = (int) $paid()->whereDate('created_at', $today)->sum('amount_fc');
        $revenueMonth = (int) $paid()->where('created_at', '>=', $monthStart)->sum('amount_fc');
        $expensesToday = (int) Expense::whereDate('spent_on', $today)->sum('amount_fc');
        $expensesMonth = (int) Expense::where('spent_on', '>=', $monthStart)->sum('amount_fc');

        // Taux d'utilisation sur les 30 derniers jours (heures réservées / heures disponibles)
        $open = (int) Studio::setting('open_hour');
        $close = (int) Studio::setting('close_hour');
        $equipmentCount = Equipment::where('is_active', true)->count();
        $bookedHours = (int) Reservation::whereIn('status', ['confirmed', 'done'])
            ->where('starts_at', '>=', $today->copy()->subDays(30))->where('starts_at', '<', now())
            ->sum('duration_hours');
        $capacity = max(1, $equipmentCount * max(1, $close - $open) * 30);

        // Recettes des 7 derniers jours
        $days = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = $today->copy()->subDays($i);
            $days[] = ['label' => $d->format('d/m'), 'value' => (int) $paid()->whereDate('created_at', $d)->sum('amount_fc')];
        }

        return view('admin.dashboard', [
            'clientsToday'     => Reservation::whereDate('starts_at', $today)->whereIn('status', ['pending', 'confirmed', 'done'])->distinct()->count('user_id'),
            'revenueToday'     => $revenueToday,
            'expensesToday'    => $expensesToday,
            'profitToday'      => $revenueToday - $expensesToday,
            'revenueMonth'     => $revenueMonth,
            'expensesMonth'    => $expensesMonth,
            'profitMonth'      => $revenueMonth - $expensesMonth,
            'newClients'       => User::where('is_admin', false)->where('created_at', '>=', $monthStart)->count(),
            'pendingRes'       => Reservation::where('status', 'pending')->count(),
            'todayRes'         => Reservation::with('user', 'equipment')->whereDate('starts_at', $today)
                                    ->whereIn('status', ['pending', 'confirmed'])->orderBy('starts_at')->get(),
            'pendingOrders'    => Order::whereIn('status', ['pending', 'paid', 'prepared'])->count(),
            'usage'            => round($bookedHours / $capacity * 100),
            'openIncidents'    => Incident::where('resolved', false)->count(),
            'incidentsMonth'   => Incident::where('reported_on', '>=', $monthStart)->count(),
            'days'             => $days,
            'maxDay'           => max(1, max(array_column($days, 'value'))),
        ]);
    }
}
