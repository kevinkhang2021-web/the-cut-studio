<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $clients = User::where('is_admin', false)
            ->when($request->q, fn ($q, $s) => $q->where(fn ($w) => $w->where('name', 'like', "%$s%")->orWhere('phone', 'like', "%$s%")))
            ->withCount('reservations', 'orders')
            ->withSum(['payments as spent' => fn ($q) => $q->where('status', 'success')], 'amount_fc')
            ->latest()->paginate(20)->withQueryString();
        return view('admin.clients.index', compact('clients'));
    }

    public function show(User $client)
    {
        abort_if($client->is_admin, 404);
        return view('admin.clients.show', [
            'client'        => $client,
            'reservations'  => $client->reservations()->with('equipment')->latest('starts_at')->get(),
            'orders'        => $client->orders()->latest()->get(),
            'payments'      => $client->payments()->latest()->get(),
            'registrations' => $client->registrations()->with('tournament')->latest()->get(),
            'messages'      => $client->reviews()->latest()->get(),
        ]);
    }
}
