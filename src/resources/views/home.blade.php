@extends('layouts.app')
@section('title', 'Accueil')
@section('content')
<section class="hero">
    <h1>Jouez, réservez et gagnez à The Cut Studio</h1>
    <p>La salle de jeux vidéo de Kasapa, à Lubumbashi. Réservez votre poste PS5 ou PS4 depuis votre téléphone, inscrivez-vous aux tournois et commandez vos jeux en ligne.</p>
    <div class="row">
        <a href="{{ route('reservations.create') }}" class="btn cyan">📅 Réserver une session</a>
        <a href="{{ route('catalog') }}" class="btn outline">Voir le catalogue</a>
    </div>
</section>

@foreach($announcements as $a)
    <div class="alert info"><strong>📣 {{ $a->title }}</strong><br>{{ $a->body }}</div>
@endforeach

<section class="grid c3 section">
    <div class="card"><span class="step-num">1</span><h3>Choisissez</h3><p class="muted">Consultez les postes libres, les jeux et les tarifs avant de vous déplacer.</p></div>
    <div class="card"><span class="step-num">2</span><h3>Réservez et payez</h3><p class="muted">Choisissez votre créneau et payez par mobile money, ou sur place en espèces.</p></div>
    <div class="card"><span class="step-num">3</span><h3>Jouez</h3><p class="muted">Présentez-vous à l'heure choisie : votre poste vous attend.</p></div>
</section>

<section class="section">
    <div class="card row between">
        <div>
            <h3>🕹️ Session de jeu : @fc(\App\Support\Studio::hourRate()) / heure</h3>
            <p class="muted" style="margin:0">Ouvert de {{ \App\Support\Studio::setting('open_hour') }}h à {{ \App\Support\Studio::setting('close_hour') }}h · de 1 à 4 heures par réservation</p>
        </div>
        <a class="btn" href="{{ route('reservations.create') }}">Voir les postes libres</a>
    </div>
</section>

<section class="section">
    <div class="section-title"><h2>Consoles PlayStation 5</h2><a href="{{ route('catalog', ['category' => 'console']) }}">Tout voir →</a></div>
    <div class="grid c4">@foreach($consoles as $product) @include('partials.product-card') @endforeach</div>
</section>

<section class="section">
    <div class="section-title"><h2>Jeux PS4 à la une</h2><a href="{{ route('catalog', ['category' => 'jeu']) }}">Tout voir →</a></div>
    <div class="grid c3">@foreach($games as $product) @include('partials.product-card') @endforeach</div>
</section>

@if($tournaments->count())
<section class="section">
    <div class="section-title"><h2>🏆 Prochains tournois</h2><a href="{{ route('tournaments.index') }}">Tous les tournois →</a></div>
    <div class="grid c3">
        @foreach($tournaments as $t)
            <div class="card">
                <h3>{{ $t->title }}</h3>
                <p class="muted">{{ $t->game }} · {{ $t->starts_at->format('d/m/Y à H\hi') }}</p>
                <p><strong>{{ $t->fee_fc > 0 ? \App\Support\Studio::fc($t->fee_fc) : 'Gratuit' }}</strong> · {{ $t->spotsLeft() }} place(s) restante(s)</p>
                <a class="btn sm" href="{{ route('tournaments.show', $t) }}">S'inscrire</a>
            </div>
        @endforeach
    </div>
</section>
@endif

@if($reviews->count())
<section class="section">
    <div class="section-title"><h2>Ils ont joué chez nous</h2><a href="{{ route('reviews.index') }}">Tous les avis →</a></div>
    <div class="grid c3">
        @foreach($reviews as $r)
            <div class="card">
                <div class="stars">{{ str_repeat('★', (int) $r->rating) }}{{ str_repeat('☆', 5 - (int) $r->rating) }}</div>
                <p>« {{ $r->message }} »</p>
                <p class="muted small">{{ $r->user->name }}</p>
            </div>
        @endforeach
    </div>
</section>
@endif
@endsection
