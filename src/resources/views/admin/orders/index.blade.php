@extends('layouts.admin')
@section('title', 'Commandes')
@section('content')
<h1>📦 Commandes</h1>
<form method="GET" class="card inline-form" style="margin-bottom:1rem">
    <div class="field"><label>Statut</label>
        <select name="status"><option value="">Tous</option>
            @foreach(['pending', 'paid', 'prepared', 'delivered', 'cancelled'] as $s)<option value="{{ $s }}" @selected(request('status') === $s)>{{ \App\Support\Studio::label($s) }}</option>@endforeach
        </select></div>
    <button class="btn" type="submit">Filtrer</button>
</form>
<div class="table-wrap"><table>
    <thead><tr><th>N°</th><th>Client</th><th>Articles</th><th>Réception</th><th class="num">Total</th><th>Statut</th><th>Actions</th></tr></thead>
    <tbody>
    @forelse($orders as $o)
        <tr>
            <td>#{{ $o->id }}<br><span class="muted small">{{ $o->created_at->format('d/m/Y') }}</span></td>
            <td>{{ $o->user->name }}<br><span class="muted small">{{ $o->phone }}</span></td>
            <td>@foreach($o->items as $i)<div class="small">{{ $i->quantity }} × {{ $i->name }}</div>@endforeach</td>
            <td>{{ \App\Support\Studio::label($o->delivery_method) }}@if($o->address)<br><span class="muted small">{{ $o->address }}</span>@endif</td>
            <td class="num">@fc($o->total_fc)<br>{!! $o->paid ? '<span class="badge ok">Payé</span>' : '<span class="badge warn">À payer</span>' !!}</td>
            <td>@badge($o->status)</td>
            <td class="actions">
                @if(!$o->paid && $o->status !== 'cancelled')
                    <form method="POST" action="{{ route('admin.orders.cash', $o) }}">@csrf<button class="btn sm cyan" type="submit">Encaisser (espèces)</button></form>
                @endif
                @if(in_array($o->status, ['paid']))
                    <form method="POST" action="{{ route('admin.orders.update', $o) }}">@csrf @method('PUT')<input type="hidden" name="status" value="prepared"><button class="btn sm ok" type="submit">Préparée</button></form>
                @endif
                @if(in_array($o->status, ['paid', 'prepared']))
                    <form method="POST" action="{{ route('admin.orders.update', $o) }}">@csrf @method('PUT')<input type="hidden" name="status" value="delivered"><button class="btn sm ok" type="submit">Livrée / remise</button></form>
                @endif
                @if(!in_array($o->status, ['delivered', 'cancelled']))
                    <form method="POST" action="{{ route('admin.orders.update', $o) }}" onsubmit="return confirm('Annuler la commande ? Le stock sera remis.')">@csrf @method('PUT')<input type="hidden" name="status" value="cancelled"><button class="btn sm danger" type="submit">Annuler</button></form>
                @endif
            </td>
        </tr>
    @empty
        <tr><td colspan="7" class="center muted">Aucune commande.</td></tr>
    @endforelse
    </tbody>
</table></div>
{{ $orders->links('partials.pagination') }}
@endsection
