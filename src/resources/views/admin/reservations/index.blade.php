@extends('layouts.admin')
@section('title', 'Réservations')
@section('content')
<h1>📅 Réservations</h1>
<form method="GET" class="card inline-form" style="margin-bottom:1rem">
    <div class="field"><label>Statut</label>
        <select name="status"><option value="">Tous</option>
            @foreach(['pending', 'confirmed', 'refused', 'cancelled', 'done'] as $s)<option value="{{ $s }}" @selected(request('status') === $s)>{{ \App\Support\Studio::label($s) }}</option>@endforeach
        </select></div>
    <div class="field"><label>Date</label><input type="date" name="date" value="{{ request('date') }}"></div>
    <button class="btn" type="submit">Filtrer</button>
    <a class="btn outline" href="{{ route('admin.reservations.index') }}">Réinitialiser</a>
</form>
<div class="table-wrap"><table>
    <thead><tr><th>N°</th><th>Client</th><th>Poste</th><th>Date et heure</th><th class="num">Montant</th><th>Statut</th><th>Paiement</th><th>Actions</th></tr></thead>
    <tbody>
    @forelse($reservations as $r)
        <tr>
            <td>#{{ $r->id }}</td>
            <td><a href="{{ route('admin.clients.show', $r->user_id) }}">{{ $r->user->name }}</a><br><span class="muted small">{{ $r->user->phone }}</span></td>
            <td>{{ $r->equipment->name }}</td>
            <td>{{ $r->starts_at->format('d/m/Y H\h') }}–{{ $r->ends_at->format('H\h') }}</td>
            <td class="num">@fc($r->total_fc)</td>
            <td>@badge($r->status)</td>
            <td>@if($r->paid)<span class="badge ok">Payé</span>@else<span class="badge warn">À payer</span>@endif</td>
            <td class="actions">
                @foreach(['confirmed' => ['Confirmer', 'ok'], 'refused' => ['Refuser', 'danger'], 'done' => ['Terminer', 'outline'], 'cancelled' => ['Annuler', 'outline']] as $s => [$label, $cls])
                    @if($r->status !== $s && !in_array($r->status, ['refused', 'cancelled', 'done']))
                        <form method="POST" action="{{ route('admin.reservations.update', $r) }}">@csrf @method('PUT')
                            <input type="hidden" name="status" value="{{ $s }}"><button class="btn sm {{ $cls }}" type="submit">{{ $label }}</button></form>
                    @endif
                @endforeach
                @if(!$r->paid && in_array($r->status, ['pending', 'confirmed', 'done']))
                    <form method="POST" action="{{ route('admin.reservations.cash', $r) }}">@csrf<button class="btn sm cyan" type="submit">Encaisser (espèces)</button></form>
                @endif
            </td>
        </tr>
    @empty
        <tr><td colspan="8" class="center muted">Aucune réservation.</td></tr>
    @endforelse
    </tbody>
</table></div>
{{ $reservations->links('partials.pagination') }}
@endsection
