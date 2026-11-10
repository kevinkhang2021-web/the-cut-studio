<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\TournamentRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TournamentController extends Controller
{
    public function index()
    {
        return view('tournaments.index', [
            'upcoming' => Tournament::where('status', 'open')->where('starts_at', '>=', now())->orderBy('starts_at')->get(),
            'past'     => Tournament::where(fn ($q) => $q->where('status', '!=', 'open')->orWhere('starts_at', '<', now()))
                                    ->orderByDesc('starts_at')->take(10)->get(),
        ]);
    }

    public function show(Request $request, Tournament $tournament)
    {
        $ranking = $tournament->registrations()->with('user')->whereNotNull('rank')->orderBy('rank')->get();
        $players = $tournament->registrations()->with('user')->where('status', 'confirmed')->get();
        $mine = $request->user()
            ? $tournament->registrations()->where('user_id', $request->user()->id)->first() : null;
        return view('tournaments.show', compact('tournament', 'ranking', 'players', 'mine'));
    }

    public function register(Request $request, Tournament $tournament)
    {
        $data = $request->validate(['gamer_tag' => 'required|string|max:40']);

        if ($tournament->status !== 'open' || $tournament->starts_at->lte(now())) {
            return back()->with('error', 'Les inscriptions à ce tournoi sont closes.');
        }

        $registration = DB::transaction(function () use ($request, $tournament, $data) {
            $t = Tournament::lockForUpdate()->find($tournament->id);
            $existing = $t->registrations()->where('user_id', $request->user()->id)->first();
            if ($existing) return $existing;
            if ($t->spotsLeft() <= 0) return null;

            return TournamentRegistration::create([
                'tournament_id' => $t->id,
                'user_id'       => $request->user()->id,
                'gamer_tag'     => $data['gamer_tag'],
                'status'        => $t->fee_fc > 0 ? 'pending' : 'confirmed',
                'paid'          => $t->fee_fc == 0,
            ]);
        });

        if (!$registration) return back()->with('error', 'Le tournoi est complet.');
        if (!$registration->paid) {
            return redirect()->route('payments.show', ['type' => 'tournament', 'id' => $registration->id])
                ->with('success', 'Inscription enregistrée. Payez les frais pour la confirmer.');
        }
        return redirect()->route('tournaments.show', $tournament)->with('success', 'Vous êtes inscrit au tournoi !');
    }
}
