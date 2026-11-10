<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    private function rules(): array
    {
        return [
            'title'       => 'required|string|max:150',
            'game'        => 'required|string|max:100',
            'starts_at'   => 'required|date',
            'fee_fc'      => 'required|integer|min:0',
            'max_players' => 'required|integer|min:2|max:512',
            'description' => 'nullable|string|max:2000',
            'status'      => 'required|in:open,closed,finished',
        ];
    }

    public function index()
    {
        return view('admin.tournaments.index', [
            'tournaments' => Tournament::withCount(['registrations as players' => fn ($q) => $q->where('status', 'confirmed')])
                ->orderByDesc('starts_at')->paginate(15),
        ]);
    }

    public function create() { return view('admin.tournaments.form', ['tournament' => new Tournament(['status' => 'open', 'max_players' => 16, 'fee_fc' => 0])]); }

    public function store(Request $request)
    {
        Tournament::create($request->validate($this->rules()));
        return redirect()->route('admin.tournaments.index')->with('success', 'Tournoi créé.');
    }

    public function edit(Tournament $tournament) { return view('admin.tournaments.form', compact('tournament')); }

    public function update(Request $request, Tournament $tournament)
    {
        $tournament->update($request->validate($this->rules()));
        return redirect()->route('admin.tournaments.index')->with('success', 'Tournoi modifié.');
    }

    public function destroy(Tournament $tournament)
    {
        $tournament->delete();
        return back()->with('success', 'Tournoi supprimé.');
    }

    public function results(Tournament $tournament)
    {
        $registrations = $tournament->registrations()->with('user')->where('status', '!=', 'cancelled')->get();
        return view('admin.tournaments.results', compact('tournament', 'registrations'));
    }

    public function saveResults(Request $request, Tournament $tournament)
    {
        $ranks = $request->validate(['rank' => 'array', 'rank.*' => 'nullable|integer|min:1|max:512'])['rank'] ?? [];
        foreach ($tournament->registrations as $reg) {
            $reg->update(['rank' => $ranks[$reg->id] ?? null]);
        }
        if ($request->boolean('finish')) $tournament->update(['status' => 'finished']);
        return redirect()->route('admin.tournaments.index')->with('success', 'Résultats enregistrés.');
    }
}
