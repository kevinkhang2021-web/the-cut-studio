@extends('layouts.app')
@section('title', 'Réserver')
@section('content')
<h1>Réserver une session</h1>
<p class="muted">Choisissez une date, puis cliquez sur un créneau <strong style="color:var(--ok)">libre</strong>. Tarif : @fc($hourRate) l'heure · ouvert de {{ $open }}h à {{ $close }}h.</p>

<form method="GET" action="{{ route('reservations.create') }}" class="card inline-form" style="margin-bottom:1rem">
    <div class="field"><label>Date</label>
        <input type="date" name="date" value="{{ $date->toDateString() }}" min="{{ today()->toDateString() }}" max="{{ today()->addDays(30)->toDateString() }}"></div>
    <div class="field"><label>Console</label>
        <select name="type"><option value="">Toutes</option>
            <option value="PS5" @selected($type === 'PS5')>PS5</option>
            <option value="PS4" @selected($type === 'PS4')>PS4</option>
        </select></div>
    <button class="btn" type="submit">Afficher les créneaux</button>
</form>

@if($equipments->isEmpty())
    <div class="card center muted">Aucun poste disponible pour le moment.</div>
@else
<div class="table-wrap">
<table class="slots">
    <thead><tr><th>Heure</th>@foreach($equipments as $eq)<th>{{ $eq->name }}</th>@endforeach</tr></thead>
    <tbody>
    @foreach($hours as $h)
        <tr>
            <th>{{ $h }}h – {{ $h + 1 }}h</th>
            @foreach($equipments as $eq)
                @php
                    $isTaken = isset($taken[$eq->id][$h]);
                    $isPast = $date->isToday() && $h <= now()->hour;
                    $isSel = $selected['equipment_id'] == $eq->id && $selected['hour'] !== null && $selected['hour'] == $h;
                @endphp
                <td>
                    @if($isPast)<span class="slot past">—</span>
                    @elseif($isTaken)<span class="slot busy">Occupé</span>
                    @else
                        <a class="slot {{ $isSel ? 'sel' : 'free' }}"
                           href="{{ route('reservations.create', ['date' => $date->toDateString(), 'type' => $type, 'equipment' => $eq->id, 'hour' => $h]) }}">
                            {{ $isSel ? 'Choisi' : 'Libre' }}</a>
                    @endif
                </td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
</div>

@if($selectedEquipment && $selected['hour'] !== null)
<form method="POST" action="{{ route('reservations.store') }}" class="card" style="margin-top:1.2rem;max-width:520px">@csrf
    <h3>Votre réservation</h3>
    <p><strong>{{ $selectedEquipment->name }}</strong> · {{ $date->format('d/m/Y') }} à {{ $selected['hour'] }}h</p>
    <input type="hidden" name="date" value="{{ $date->toDateString() }}">
    <input type="hidden" name="equipment_id" value="{{ $selectedEquipment->id }}">
    <input type="hidden" name="start_hour" value="{{ $selected['hour'] }}">
    <div class="field"><label for="duration">Durée</label>
        <select id="duration" name="duration">
            @foreach([1, 2, 3, 4] as $d)
                <option value="{{ $d }}" @selected((int) old('duration', 1) === $d)>{{ $d }} heure(s) – @fc($d * $hourRate)</option>
            @endforeach
        </select></div>
    <div class="field"><label for="note">Précision (facultatif)</label>
        <input id="note" type="text" name="note" value="{{ old('note') }}" placeholder="Ex. jeu souhaité, nombre de joueurs"></div>
    <button class="btn block" type="submit">Envoyer la réservation</button>
    <p class="muted small" style="margin:.6rem 0 0">Le gérant confirmera votre créneau. Le paiement se fait en ligne ou sur place.</p>
</form>
@else
<p class="muted" style="margin-top:1rem">Cliquez sur un créneau libre pour continuer.</p>
@endif
@endif
@endsection
