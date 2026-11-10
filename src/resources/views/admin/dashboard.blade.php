@extends('layouts.admin')
@section('title', 'Tableau de bord')
@section('content')
<h1>📊 Tableau de bord</h1>
<p class="muted">{{ now()->format('d/m/Y') }} · indicateurs de The Cut Studio</p>

<div class="grid c4">
    <div class="stat"><div class="l">Clients avec réservation aujourd'hui</div><div class="v">{{ $clientsToday }}</div></div>
    <div class="stat"><div class="l">Chiffre d'affaires du jour</div><div class="v">@fc($revenueToday)</div></div>
    <div class="stat"><div class="l">Dépenses du jour</div><div class="v">@fc($expensesToday)</div></div>
    <div class="stat {{ $profitToday >= 0 ? 'green' : 'red' }}"><div class="l">Bénéfice du jour</div><div class="v">@fc($profitToday)</div></div>
</div>
<div class="grid c4" style="margin-top:1rem">
    <div class="stat"><div class="l">Chiffre d'affaires du mois</div><div class="v">@fc($revenueMonth)</div></div>
    <div class="stat"><div class="l">Dépenses du mois</div><div class="v">@fc($expensesMonth)</div></div>
    <div class="stat {{ $profitMonth >= 0 ? 'green' : 'red' }}"><div class="l">Bénéfice du mois</div><div class="v">@fc($profitMonth)</div></div>
    <div class="stat"><div class="l">Nouveaux clients (mois)</div><div class="v">{{ $newClients }}</div></div>
</div>
<div class="grid c4" style="margin-top:1rem">
    <div class="stat"><div class="l">Réservations à confirmer</div><div class="v">{{ $pendingRes }}</div></div>
    <div class="stat"><div class="l">Commandes en cours</div><div class="v">{{ $pendingOrders }}</div></div>
    <div class="stat"><div class="l">Taux d'utilisation (30 j)</div><div class="v">{{ $usage }} %</div></div>
    <div class="stat {{ $openIncidents ? 'red' : '' }}"><div class="l">Pannes en cours / ce mois</div><div class="v">{{ $openIncidents }} / {{ $incidentsMonth }}</div></div>
</div>

<div class="grid c2 section">
    <div class="card">
        <h3>Recettes des 7 derniers jours</h3>
        <div class="bars">
            @foreach($days as $d)
                <div class="bar-col">
                    <span>{{ $d['value'] > 0 ? number_format($d['value'] / 1000, 0, ',', ' ') . 'k' : '' }}</span>
                    <div class="bar" @style(['height' => max(2, round($d['value'] / $maxDay * 100)) . '%'])></div>
                    <span>{{ $d['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
    <div class="card">
        <h3>Sessions du jour</h3>
        @forelse($todayRes as $r)
            <div class="row between" style="border-bottom:1px solid var(--line);padding:.4rem 0">
                <span>{{ $r->starts_at->format('H\h') }}–{{ $r->ends_at->format('H\h') }} · {{ $r->equipment->name }} · {{ $r->user->name }}</span>
                @badge($r->status)
            </div>
        @empty
            <p class="muted">Aucune session prévue aujourd'hui.</p>
        @endforelse
        <p style="margin-top:.8rem"><a href="{{ route('admin.reservations.index') }}">Toutes les réservations →</a></p>
    </div>
</div>
@endsection
