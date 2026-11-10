@extends('layouts.admin')
@section('title', 'Postes et pannes')
@section('content')
<h1>🕹️ Postes de jeu et pannes</h1>
<form method="POST" action="{{ route('admin.equipment.store') }}" class="card inline-form" style="margin-bottom:1rem">@csrf
    <div class="field"><label>Nom du poste</label><input type="text" name="name" placeholder="Ex. Poste PS5 4" required></div>
    <div class="field"><label>Console</label><select name="type"><option>PS5</option><option>PS4</option></select></div>
    <button class="btn" type="submit">+ Ajouter</button>
</form>

<div class="grid c2">
@foreach($equipments as $eq)
    <div class="card">
        <div class="row between">
            <h3 style="margin:0">{{ $eq->name }} <span class="tag">{{ $eq->type }}</span></h3>
            @if(!$eq->is_active)<span class="badge bad">Désactivé</span>
            @elseif($eq->open_incidents)<span class="badge bad">En panne</span>
            @else<span class="badge ok">Disponible</span>@endif
        </div>
        <div class="actions" style="margin:.7rem 0">
            <form method="POST" action="{{ route('admin.equipment.update', $eq) }}">@csrf @method('PUT')
                <button class="btn sm outline" type="submit">{{ $eq->is_active ? 'Désactiver' : 'Activer' }}</button></form>
            <form method="POST" action="{{ route('admin.equipment.destroy', $eq) }}" onsubmit="return confirm('Supprimer ce poste et ses réservations ?')">@csrf @method('DELETE')
                <button class="btn sm danger" type="submit">Supprimer</button></form>
        </div>
        <form method="POST" action="{{ route('admin.incidents.store', $eq) }}" class="inline-form">@csrf
            <div class="field"><input type="text" name="description" placeholder="Décrire la panne" required></div>
            <button class="btn sm danger" type="submit">Signaler une panne</button>
        </form>
        @foreach($eq->incidents as $i)
            <div class="row between" style="border-top:1px solid var(--line);margin-top:.6rem;padding-top:.5rem">
                <span class="small">{{ $i->reported_on->format('d/m/Y') }} – {{ $i->description }}</span>
                @if($i->resolved)<span class="badge ok">Réparé</span>
                @else<form method="POST" action="{{ route('admin.incidents.resolve', $i) }}">@csrf @method('PUT')<button class="btn sm ok" type="submit">Marquer réparé</button></form>@endif
            </div>
        @endforeach
    </div>
@endforeach
</div>
@endsection
