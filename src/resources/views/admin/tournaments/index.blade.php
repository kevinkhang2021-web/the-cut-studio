@extends('layouts.admin')
@section('title', 'Tournois')
@section('content')
<div class="row between"><h1>🏆 Tournois</h1><a class="btn" href="{{ route('admin.tournaments.create') }}">+ Nouveau tournoi</a></div>
<div class="table-wrap"><table>
    <thead><tr><th>Tournoi</th><th>Jeu</th><th>Date</th><th class="num">Frais</th><th>Joueurs</th><th>Statut</th><th></th></tr></thead>
    <tbody>
    @forelse($tournaments as $t)
        <tr>
            <td>{{ $t->title }}</td><td>{{ $t->game }}</td><td>{{ $t->starts_at->format('d/m/Y H\hi') }}</td>
            <td class="num">@fc($t->fee_fc)</td><td>{{ $t->players }} / {{ $t->max_players }}</td><td>@badge($t->status)</td>
            <td class="actions">
                <a class="btn sm outline" href="{{ route('admin.tournaments.results', $t) }}">Joueurs et résultats</a>
                <a class="btn sm outline" href="{{ route('admin.tournaments.edit', $t) }}">Modifier</a>
                <form method="POST" action="{{ route('admin.tournaments.destroy', $t) }}" onsubmit="return confirm('Supprimer ce tournoi ?')">@csrf @method('DELETE')<button class="btn sm danger" type="submit">Supprimer</button></form>
            </td>
        </tr>
    @empty
        <tr><td colspan="7" class="center muted">Aucun tournoi.</td></tr>
    @endforelse
    </tbody>
</table></div>
{{ $tournaments->links('partials.pagination') }}
@endsection
