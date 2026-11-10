@extends('layouts.app')
@section('title', 'Mes réservations')
@section('content')
<div class="row between"><h1>Mes réservations</h1><a class="btn" href="{{ route('reservations.create') }}">+ Nouvelle réservation</a></div>
@if($reservations->count())
<div class="table-wrap">
<table>
    <thead><tr><th>N°</th><th>Poste</th><th>Date et heure</th><th>Durée</th><th class="num">Montant</th><th>Statut</th><th>Paiement</th><th></th></tr></thead>
    <tbody>
    @foreach($reservations as $r)
        <tr>
            <td>#{{ $r->id }}</td>
            <td>{{ $r->equipment->name }}</td>
            <td>{{ $r->starts_at->format('d/m/Y H\h') }} – {{ $r->ends_at->format('H\h') }}</td>
            <td>{{ $r->duration_hours }} h</td>
            <td class="num">@fc($r->total_fc)</td>
            <td>@badge($r->status)</td>
            <td>@if($r->paid)<span class="badge ok">Payé</span>@else<span class="badge warn">À payer</span>@endif</td>
            <td class="actions">
                @if(!$r->paid && in_array($r->status, ['pending', 'confirmed']))
                    <a class="btn sm cyan" href="{{ route('payments.show', ['type' => 'reservation', 'id' => $r->id]) }}">Payer</a>
                @endif
                @if(in_array($r->status, ['pending', 'confirmed']) && $r->starts_at->gt(now()))
                    <form method="POST" action="{{ route('reservations.cancel', $r) }}" onsubmit="return confirm('Annuler cette réservation ?')">@csrf
                        <button class="btn sm danger" type="submit">Annuler</button></form>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
{{ $reservations->links('partials.pagination') }}
<p class="muted small" style="margin-top:1rem">Pour modifier une réservation, annulez-la puis réservez un nouveau créneau.</p>
@else
    <div class="card center muted">Vous n'avez pas encore de réservation.</div>
@endif
@endsection
