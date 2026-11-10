<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\Incident;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index()
    {
        return view('admin.equipment.index', [
            'equipments' => Equipment::with(['incidents' => fn ($q) => $q->latest()->take(5)])
                ->withCount(['incidents as open_incidents' => fn ($q) => $q->where('resolved', false)])
                ->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:80', 'type' => 'required|in:PS5,PS4']);
        Equipment::create($data + ['is_active' => true]);
        return back()->with('success', 'Poste ajouté.');
    }

    public function update(Request $request, Equipment $equipment)
    {
        $equipment->update(['is_active' => !$equipment->is_active]);
        return back()->with('success', $equipment->is_active ? 'Poste activé.' : 'Poste désactivé.');
    }

    public function destroy(Equipment $equipment)
    {
        $equipment->delete();
        return back()->with('success', 'Poste supprimé.');
    }

    public function reportIncident(Request $request, Equipment $equipment)
    {
        $data = $request->validate(['description' => 'required|string|max:200']);
        $equipment->incidents()->create($data + ['reported_on' => today(), 'resolved' => false]);
        return back()->with('success', 'Panne enregistrée : le poste n\'est plus proposé à la réservation.');
    }

    public function resolveIncident(Incident $incident)
    {
        $incident->update(['resolved' => true]);
        return back()->with('success', 'Panne marquée comme réparée.');
    }
}
