@extends('layouts.admin')
@section('title', 'Tournoi')
@section('content')
<h1>{{ $tournament->exists ? 'Modifier le tournoi' : 'Nouveau tournoi' }}</h1>
<form method="POST" class="card" style="max-width:640px"
      action="{{ $tournament->exists ? route('admin.tournaments.update', $tournament) : route('admin.tournaments.store') }}">
    @csrf
    @if($tournament->exists) @method('PUT') @endif
    <div class="field"><label>Titre</label><input type="text" name="title" value="{{ old('title', $tournament->title) }}" required></div>
    <div class="field"><label>Jeu</label><input type="text" name="game" value="{{ old('game', $tournament->game) }}" required></div>
    <div class="grid c2">
        <div class="field"><label>Date et heure</label>
            <input type="datetime-local" name="starts_at" value="{{ old('starts_at', optional($tournament->starts_at)->format('Y-m-d\TH:i')) }}" required></div>
        <div class="field"><label>Frais d'inscription (FC, 0 = gratuit)</label><input type="number" min="0" name="fee_fc" value="{{ old('fee_fc', $tournament->fee_fc) }}" required></div>
        <div class="field"><label>Nombre de places</label><input type="number" min="2" name="max_players" value="{{ old('max_players', $tournament->max_players) }}" required></div>
        <div class="field"><label>Statut</label>
            <select name="status">@foreach(['open', 'closed', 'finished'] as $s)<option value="{{ $s }}" @selected(old('status', $tournament->status) === $s)>{{ \App\Support\Studio::label($s) }}</option>@endforeach</select></div>
    </div>
    <div class="field"><label>Description / règlement</label><textarea name="description" rows="4">{{ old('description', $tournament->description) }}</textarea></div>
    <div class="row"><button class="btn" type="submit">Enregistrer</button><a class="btn outline" href="{{ route('admin.tournaments.index') }}">Annuler</a></div>
</form>
@endsection
