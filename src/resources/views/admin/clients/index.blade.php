@extends('layouts.admin')
@section('title', 'Clients')
@section('content')
<h1>👥 Clients</h1>
<form method="GET" class="card inline-form" style="margin-bottom:1rem">
    <div class="field"><label>Recherche (nom ou téléphone)</label><input type="text" name="q" value="{{ request('q') }}"></div>
    <button class="btn" type="submit">Rechercher</button>
</form>
<div class="table-wrap"><table>
    <thead><tr><th>Nom</th><th>Téléphone</th><th>Inscrit le</th><th class="num">Réservations</th><th class="num">Commandes</th><th class="num">Total payé</th><th></th></tr></thead>
    <tbody>
    @forelse($clients as $c)
        <tr><td>{{ $c->name }}</td><td>{{ $c->phone }}</td><td>{{ $c->created_at->format('d/m/Y') }}</td>
            <td class="num">{{ $c->reservations_count }}</td><td class="num">{{ $c->orders_count }}</td><td class="num">@fc($c->spent ?? 0)</td>
            <td><a class="btn sm outline" href="{{ route('admin.clients.show', $c) }}">Fiche</a></td></tr>
    @empty
        <tr><td colspan="7" class="center muted">Aucun client.</td></tr>
    @endforelse
    </tbody>
</table></div>
{{ $clients->links('partials.pagination') }}
@endsection
