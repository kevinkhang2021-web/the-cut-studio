@extends('layouts.app')
@section('title', 'Tournois')
@section('content')
<h1>🏆 Tournois en ligne</h1>
<p class="muted">Inscrivez-vous et payez en ligne. Le classement est publié après chaque tournoi.</p>

<h2 class="section">À venir</h2>
@if($upcoming->count())
<div class="grid c3">
    @foreach($upcoming as $t)
    <div class="card">
        <h3>{{ $t->title }}</h3>
        <p class="muted">{{ $t->game }}<br>📅 {{ $t->starts_at->format('d/m/Y à H\hi') }}</p>
        <p><strong>{{ $t->fee_fc > 0 ? \App\Support\Studio::fc($t->fee_fc) : 'Gratuit' }}</strong> · {{ $t->spotsLeft() }}/{{ $t->max_players }} places</p>
        <a class="btn sm" href="{{ route('tournaments.show', $t) }}">Détails et inscription</a>
    </div>
    @endforeach
</div>
@else
    <div class="card muted center">Aucun tournoi programmé pour le moment. Revenez bientôt !</div>
@endif

@if($past->count())
<h2 class="section">Tournois passés</h2>
<div class="table-wrap"><table>
    <thead><tr><th>Tournoi</th><th>Jeu</th><th>Date</th><th>Statut</th><th></th></tr></thead>
    <tbody>@foreach($past as $t)
        <tr><td>{{ $t->title }}</td><td>{{ $t->game }}</td><td>{{ $t->starts_at->format('d/m/Y') }}</td><td>@badge($t->status)</td>
            <td><a href="{{ route('tournaments.show', $t) }}">Voir</a></td></tr>
    @endforeach</tbody>
</table></div>
@endif
@endsection
