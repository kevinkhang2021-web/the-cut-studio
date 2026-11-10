@extends('layouts.app')
@section('title', 'Mon compte')
@section('content')
<h1>Mon compte</h1>
<div class="grid c2">
    <form method="POST" action="{{ route('account.update') }}" class="card">@csrf @method('PUT')
        <h3>Mes informations</h3>
        <div class="field"><label>Nom</label><input type="text" name="name" value="{{ old('name', $user->name) }}" required></div>
        <div class="field"><label>Téléphone</label><input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" required></div>
        <div class="field"><label>Email</label><input type="email" name="email" value="{{ old('email', $user->email) }}"></div>
        <div class="field"><label>Nouveau mot de passe (laisser vide pour ne pas changer)</label><input type="password" name="password"></div>
        <div class="field"><label>Confirmer le mot de passe</label><input type="password" name="password_confirmation"></div>
        <button class="btn" type="submit">Enregistrer</button>
    </form>
    <div class="card">
        <h3>Donner mon avis ou faire une réclamation</h3>
        <form method="POST" action="{{ route('reviews.store') }}">@csrf
            <div class="field"><label>Type</label>
                <select name="type"><option value="review">Avis (public)</option><option value="complaint">Réclamation (privée)</option></select></div>
            <div class="field"><label>Note (pour un avis)</label>
                <select name="rating">@foreach([5, 4, 3, 2, 1] as $n)<option value="{{ $n }}">{{ str_repeat('★', $n) }} ({{ $n }}/5)</option>@endforeach</select></div>
            <div class="field"><label>Message</label><textarea name="message" rows="3" required></textarea></div>
            <button class="btn" type="submit">Envoyer</button>
        </form>
    </div>
</div>

<h2 class="section">Mes réservations</h2>
@if($reservations->count())
<div class="table-wrap"><table>
    <thead><tr><th>N°</th><th>Poste</th><th>Date</th><th class="num">Montant</th><th>Statut</th><th>Paiement</th></tr></thead>
    <tbody>@foreach($reservations as $r)
        <tr><td>#{{ $r->id }}</td><td>{{ $r->equipment->name }}</td><td>{{ $r->starts_at->format('d/m/Y H\h') }}</td>
            <td class="num">@fc($r->total_fc)</td><td>@badge($r->status)</td><td>{{ $r->paid ? 'Payé' : 'À payer' }}</td></tr>
    @endforeach</tbody>
</table></div>
<p><a href="{{ route('reservations.index') }}">Gérer mes réservations →</a></p>
@else<p class="muted">Aucune réservation.</p>@endif

<h2 class="section">Mes commandes</h2>
@if($orders->count())
<div class="table-wrap"><table>
    <thead><tr><th>N°</th><th>Date</th><th>Articles</th><th>Réception</th><th class="num">Total</th><th>Statut</th><th></th></tr></thead>
    <tbody>@foreach($orders as $o)
        <tr><td>#{{ $o->id }}</td><td>{{ $o->created_at->format('d/m/Y') }}</td><td>{{ $o->items_count }}</td>
            <td>{{ \App\Support\Studio::label($o->delivery_method) }}</td><td class="num">@fc($o->total_fc)</td><td>@badge($o->status)</td>
            <td>@if(!$o->paid && $o->status === 'pending')<a class="btn sm cyan" href="{{ route('payments.show', ['type' => 'order', 'id' => $o->id]) }}">Payer</a>@endif</td></tr>
    @endforeach</tbody>
</table></div>
@else<p class="muted">Aucune commande.</p>@endif

<h2 class="section">Mes tournois</h2>
@if($registrations->count())
<div class="table-wrap"><table>
    <thead><tr><th>Tournoi</th><th>Pseudo</th><th>Date</th><th>Statut</th><th>Rang</th></tr></thead>
    <tbody>@foreach($registrations as $g)
        <tr><td><a href="{{ route('tournaments.show', $g->tournament) }}">{{ $g->tournament->title }}</a></td><td>{{ $g->gamer_tag }}</td>
            <td>{{ $g->tournament->starts_at->format('d/m/Y') }}</td><td>@badge($g->status)</td><td>{{ $g->rank ?: '—' }}</td></tr>
    @endforeach</tbody>
</table></div>
@else<p class="muted">Aucune inscription.</p>@endif

<h2 class="section">Mes paiements</h2>
@if($payments->count())
<div class="table-wrap"><table>
    <thead><tr><th>Référence</th><th>Date</th><th>Moyen</th><th class="num">Montant</th><th></th></tr></thead>
    <tbody>@foreach($payments as $p)
        <tr><td>{{ $p->reference }}</td><td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
            <td>{{ \App\Support\Studio::METHODS[$p->method] ?? $p->method }}</td><td class="num">@fc($p->amount_fc)</td>
            <td><a href="{{ route('payments.receipt', $p) }}">Reçu</a></td></tr>
    @endforeach</tbody>
</table></div>
@else<p class="muted">Aucun paiement.</p>@endif

@if($messages->count())
<h2 class="section">Mes avis et réclamations</h2>
@foreach($messages as $m)
    <div class="card" style="margin-bottom:.8rem">
        <p>@badge($m->type) <span class="muted small">{{ $m->created_at->format('d/m/Y') }}</span></p>
        <p>{{ $m->message }}</p>
        @if($m->response)<div class="alert info" style="margin:0"><strong>Réponse du gérant :</strong> {{ $m->response }}</div>@endif
    </div>
@endforeach
@endif
@endsection
