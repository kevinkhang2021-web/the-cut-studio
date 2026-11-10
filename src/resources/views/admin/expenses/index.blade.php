@extends('layouts.admin')
@section('title', 'Dépenses')
@section('content')
<h1>💸 Dépenses</h1>
<form method="POST" action="{{ route('admin.expenses.store') }}" class="card inline-form" style="margin-bottom:1rem">@csrf
    <div class="field"><label>Libellé</label><input type="text" name="label" placeholder="Ex. Facture d'électricité" required></div>
    <div class="field"><label>Catégorie</label>
        <select name="category">@foreach(\App\Models\Expense::CATEGORIES as $c)<option>{{ $c }}</option>@endforeach</select></div>
    <div class="field"><label>Montant (FC)</label><input type="number" min="1" name="amount_fc" required></div>
    <div class="field"><label>Date</label><input type="date" name="spent_on" value="{{ today()->toDateString() }}" required></div>
    <button class="btn" type="submit">+ Ajouter</button>
</form>

<form method="GET" class="inline-form" style="margin-bottom:1rem">
    <div class="field" style="max-width:200px"><label>Mois affiché</label><input type="month" name="month" value="{{ $month }}"></div>
    <button class="btn outline" type="submit">Afficher</button>
</form>

<div class="table-wrap"><table>
    <thead><tr><th>Date</th><th>Libellé</th><th>Catégorie</th><th class="num">Montant</th><th></th></tr></thead>
    <tbody>
    @forelse($expenses as $e)
        <tr><td>{{ $e->spent_on->format('d/m/Y') }}</td><td>{{ $e->label }}</td><td>{{ $e->category }}</td><td class="num">@fc($e->amount_fc)</td>
            <td><form method="POST" action="{{ route('admin.expenses.destroy', $e) }}" onsubmit="return confirm('Supprimer cette dépense ?')">@csrf @method('DELETE')<button class="linklike" type="submit">Supprimer</button></form></td></tr>
    @empty
        <tr><td colspan="5" class="center muted">Aucune dépense ce mois-ci.</td></tr>
    @endforelse
    </tbody>
    <tfoot><tr><th colspan="3" class="num">Total du mois</th><th class="num">@fc($expenses->sum('amount_fc'))</th><th></th></tr></tfoot>
</table></div>
@endsection
