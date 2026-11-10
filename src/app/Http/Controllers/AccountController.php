<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $u = $request->user();
        return view('account.index', [
            'user'          => $u,
            'reservations'  => $u->reservations()->with('equipment')->latest('starts_at')->take(10)->get(),
            'orders'        => $u->orders()->withCount('items')->latest()->take(10)->get(),
            'payments'      => $u->payments()->latest()->take(10)->get(),
            'registrations' => $u->registrations()->with('tournament')->latest()->take(10)->get(),
            'messages'      => $u->reviews()->latest()->take(10)->get(),
        ]);
    }

    public function update(Request $request)
    {
        $u = $request->user();
        $request->merge(['phone' => preg_replace('/\s+/', '', (string) $request->input('phone'))]);
        $data = $request->validate([
            'name'     => 'required|string|max:120',
            'phone'    => ['required', 'regex:/^\+?[0-9]{9,14}$/', Rule::unique('users', 'phone')->ignore($u->id)],
            'email'    => ['nullable', 'email', Rule::unique('users', 'email')->ignore($u->id)],
            'password' => 'nullable|string|min:6|confirmed',
        ]);
        if (empty($data['password'])) unset($data['password']);
        $u->update($data);
        return back()->with('success', 'Profil mis à jour.');
    }
}
