@extends('layouts.app')
@section('title', 'Avis')
@section('content')
<h1>⭐ Avis des joueurs</h1>
@if($avg)<p class="muted">Note moyenne : <strong class="stars">{{ number_format($avg, 1, ',', '') }}/5</strong></p>@endif

@auth
<form method="POST" action="{{ route('reviews.store') }}" class="card" style="margin-bottom:1.2rem;max-width:560px">@csrf
    <input type="hidden" name="type" value="review">
    <h3>Laisser un avis</h3>
    <div class="field"><label>Note</label>
        <select name="rating">@foreach([5, 4, 3, 2, 1] as $n)<option value="{{ $n }}">{{ str_repeat('★', $n) }} ({{ $n }}/5)</option>@endforeach</select></div>
    <div class="field"><label>Votre message</label><textarea name="message" rows="3" required></textarea></div>
    <button class="btn" type="submit">Publier</button>
</form>
@else
<div class="alert info">Connectez-vous pour laisser un avis. <a href="{{ route('login') }}">Connexion</a></div>
@endauth

@forelse($reviews as $r)
    <div class="card" style="margin-bottom:.8rem">
        <div class="stars">{{ str_repeat('★', (int) $r->rating) }}{{ str_repeat('☆', 5 - (int) $r->rating) }}</div>
        <p>{{ $r->message }}</p>
        <p class="muted small">{{ $r->user->name }} · {{ $r->created_at->format('d/m/Y') }}</p>
        @if($r->response)<div class="alert info" style="margin:0"><strong>Réponse du gérant :</strong> {{ $r->response }}</div>@endif
    </div>
@empty
    <div class="card center muted">Pas encore d'avis. Soyez le premier !</div>
@endforelse
{{ $reviews->links('partials.pagination') }}
@endsection
