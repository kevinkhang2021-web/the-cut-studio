@extends('layouts.admin')
@section('title', 'Résultats du tournoi')
@section('content')
<p><a href="{{ route('admin.tournaments.index') }}">← Tournois</a></p>
<h1>{{ $tournament->title }} – joueurs et résultats</h1>
<form method="POST" action="{{ route('admin.tournaments.results.save', $tournament) }}">@csrf
<div class="table-wrap"><table>
    <thead><tr><th>Pseudo</th><th>Client</th><th>Inscription</th><th>Paiement</th><th style="width:120px">Rang</th></tr></thead>
    <tbody>
    @forelse($registrations as $r)
        <tr>
            <td>{{ $r->gamer_tag }}</td>
            <td>{{ $r->user->name }}<br><span class="muted small">{{ $r->user->phone }}</span></td>
            <td>@badge($r->status)</td>
            <td>{!! $r->paid ? '<span class="badge ok">Payé</span>' : '<span class="badge warn">Non payé</span>' !!}</td>
            <td><input type="number" min="1" name="rank[{{ $r->id }}]" value="{{ $r->rank }}"></td>
        </tr>
    @empty
        <tr><td colspan="5" class="center muted">Aucun inscrit.</td></tr>
    @endforelse
    </tbody>
</table></div>
@if($registrations->count())
    <div class="field" style="margin-top:1rem"><label class="check"><input type="checkbox" name="finish" value="1"> Marquer le tournoi comme terminé</label></div>
    <button class="btn" type="submit">Enregistrer les résultats</button>
@endif
</form>
@endsection
