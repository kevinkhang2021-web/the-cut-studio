@extends('layouts.app')
@section('title', $tournament->title)
@section('content')
<p><a href="{{ route('tournaments.index') }}">← Tous les tournois</a></p>
<div class="grid c2">
    <div class="card">
        <h1>{{ $tournament->title }}</h1>
        <p>@badge($tournament->status)</p>
        <p><strong>Jeu :</strong> {{ $tournament->game }}<br>
           <strong>Date :</strong> {{ $tournament->starts_at->format('d/m/Y à H\hi') }}<br>
           <strong>Frais d'inscription :</strong> {{ $tournament->fee_fc > 0 ? \App\Support\Studio::fc($tournament->fee_fc) : 'Gratuit' }}<br>
           <strong>Places :</strong> {{ $tournament->spotsLeft() }} restante(s) sur {{ $tournament->max_players }}</p>
        <p class="muted">{{ $tournament->description }}</p>
    </div>
    <div class="card">
        <h3>Inscription</h3>
        @if($mine)
            <p>Vous êtes inscrit sous le pseudo <strong>{{ $mine->gamer_tag }}</strong> · @badge($mine->status)</p>
            @if(!$mine->paid)
                <a class="btn cyan" href="{{ route('payments.show', ['type' => 'tournament', 'id' => $mine->id]) }}">Payer {{ \App\Support\Studio::fc($tournament->fee_fc) }}</a>
            @endif
        @elseif($tournament->status !== 'open' || $tournament->starts_at->lte(now()))
            <p class="muted">Les inscriptions sont closes.</p>
        @elseif($tournament->spotsLeft() <= 0)
            <p class="muted">Ce tournoi est complet.</p>
        @else
            @auth
                <form method="POST" action="{{ route('tournaments.register', $tournament) }}">@csrf
                    <div class="field"><label for="gamer_tag">Votre pseudo de joueur</label>
                        <input id="gamer_tag" type="text" name="gamer_tag" value="{{ old('gamer_tag', auth()->user()->name) }}" required></div>
                    <button class="btn block" type="submit">{{ $tournament->fee_fc > 0 ? 'S\'inscrire et payer' : 'S\'inscrire gratuitement' }}</button>
                </form>
            @else
                <p class="muted">Connectez-vous pour vous inscrire.</p>
                <a class="btn" href="{{ route('login') }}">Connexion</a>
            @endauth
        @endif
    </div>
</div>

@if($ranking->count())
<h2 class="section">Classement</h2>
<div class="table-wrap"><table>
    <thead><tr><th>Rang</th><th>Joueur</th></tr></thead>
    <tbody>@foreach($ranking as $r)
        <tr><td>{{ $r->rank === 1 ? '🥇' : ($r->rank === 2 ? '🥈' : ($r->rank === 3 ? '🥉' : $r->rank)) }}</td><td>{{ $r->gamer_tag }}</td></tr>
    @endforeach</tbody>
</table></div>
@endif

@if($players->count())
<h2 class="section">Joueurs inscrits ({{ $players->count() }})</h2>
<div class="row">@foreach($players as $p)<span class="tag">{{ $p->gamer_tag }}</span>@endforeach</div>
@endif
@endsection
