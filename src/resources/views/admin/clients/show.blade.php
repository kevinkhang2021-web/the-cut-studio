@extends('layouts.admin')
@section('title', 'Fiche client')
@section('content')
<p><a href="{{ route('admin.clients.index') }}">← Clients</a></p>
<h1>{{ $client->name }}</h1>
<p class="muted">📞 {{ $client->phone }}@if($client->email) · ✉️ {{ $client->email }}@endif · inscrit le {{ $client->created_at->format('d/m/Y') }}</p>

<h2 class="section">Réservations ({{ $reservations->count() }})</h2>
<div class="table-wrap"><table>
    <thead><tr><th>N°</th><th>Poste</th><th>Date</th><th class="num">Montant</th><th>Statut</th><th>Paiement</th></tr></thead>
    <tbody>@forelse($reservations as $r)
        <tr><td>#{{ $r->id }}</td><td>{{ $r->equipment->name }}</td><td>{{ $r->starts_at->format('d/m/Y H\h') }}</td><td class="num">@fc($r->total_fc)</td><td>@badge($r->status)</td><td>{{ $r->paid ? 'Payé' : 'À payer' }}</td></tr>
    @empty<tr><td colspan="6" class="muted center">Aucune réservation.</td></tr>@endforelse</tbody>
</table></div>

<h2 class="section">Commandes ({{ $orders->count() }})</h2>
<div class="table-wrap"><table>
    <thead><tr><th>N°</th><th>Date</th><th class="num">Total</th><th>Statut</th></tr></thead>
    <tbody>@forelse($orders as $o)
        <tr><td>#{{ $o->id }}</td><td>{{ $o->created_at->format('d/m/Y') }}</td><td class="num">@fc($o->total_fc)</td><td>@badge($o->status)</td></tr>
    @empty<tr><td colspan="4" class="muted center">Aucune commande.</td></tr>@endforelse</tbody>
</table></div>

<h2 class="section">Tournois ({{ $registrations->count() }})</h2>
<div class="table-wrap"><table>
    <thead><tr><th>Tournoi</th><th>Pseudo</th><th>Statut</th><th>Rang</th></tr></thead>
    <tbody>@forelse($registrations as $g)
        <tr><td>{{ $g->tournament->title }}</td><td>{{ $g->gamer_tag }}</td><td>@badge($g->status)</td><td>{{ $g->rank ?: '—' }}</td></tr>
    @empty<tr><td colspan="4" class="muted center">Aucune inscription.</td></tr>@endforelse</tbody>
</table></div>

<h2 class="section">Paiements ({{ $payments->count() }})</h2>
<div class="table-wrap"><table>
    <thead><tr><th>Référence</th><th>Date</th><th>Moyen</th><th class="num">Montant</th><th></th></tr></thead>
    <tbody>@forelse($payments as $p)
        <tr><td>{{ $p->reference }}</td><td>{{ $p->created_at->format('d/m/Y H:i') }}</td><td>{{ \App\Support\Studio::METHODS[$p->method] ?? $p->method }}</td><td class="num">@fc($p->amount_fc)</td><td><a href="{{ route('payments.receipt', $p) }}">Reçu</a></td></tr>
    @empty<tr><td colspan="5" class="muted center">Aucun paiement.</td></tr>@endforelse</tbody>
</table></div>

@if($messages->count())
<h2 class="section">Avis et réclamations</h2>
@foreach($messages as $m)<div class="card" style="margin-bottom:.6rem">@badge($m->type) {{ $m->message }}</div>@endforeach
@endif
@endsection
