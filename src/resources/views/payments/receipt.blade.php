@extends('layouts.app')
@section('title', 'Reçu de paiement')
@section('content')
<div class="receipt">
    <h2>🎮 The Cut Studio</h2>
    <p class="muted">{{ \App\Support\Studio::setting('address') }}</p>
    <h3>Reçu de paiement</h3>
    <table>
        <tr><td class="muted">Référence</td><td><strong>{{ $payment->reference }}</strong></td></tr>
        <tr><td class="muted">Date</td><td>{{ $payment->created_at->format('d/m/Y à H\hi') }}</td></tr>
        <tr><td class="muted">Client</td><td>{{ $payment->user->name }}</td></tr>
        <tr><td class="muted">Objet</td><td>{{ $payment->payable ? $payment->payable->summaryLabel() : '—' }}</td></tr>
        <tr><td class="muted">Moyen de paiement</td><td>{{ \App\Support\Studio::METHODS[$payment->method] ?? $payment->method }} {{ $payment->phone }}</td></tr>
        <tr><td class="muted">Statut</td><td>@badge($payment->status)</td></tr>
        <tr><td class="muted">Montant payé</td><td><strong>@fc($payment->amount_fc)</strong></td></tr>
    </table>
    <p class="muted small" style="margin-top:1rem">Merci de votre confiance ! Conservez ce reçu.</p>
    <div class="row no-print">
        <button class="btn" onclick="window.print()">🖨️ Imprimer</button>
        <a class="btn outline" href="{{ route('account') }}" style="color:#111">Mon compte</a>
    </div>
</div>
@endsection
