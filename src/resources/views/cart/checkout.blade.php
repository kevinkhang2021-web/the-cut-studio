@extends('layouts.app')
@section('title', 'Commande')
@section('content')
<h1>Finaliser la commande</h1>
<div class="grid c2">
    <form method="POST" action="{{ route('checkout.place') }}" class="card">@csrf
        <h3>Réception</h3>
        <label class="radio-card"><input type="radio" name="delivery_method" value="pickup" @checked(old('delivery_method', 'pickup') === 'pickup')> Retrait en salle (Kasapa)</label>
        <label class="radio-card"><input type="radio" name="delivery_method" value="delivery" @checked(old('delivery_method') === 'delivery')> Livraison</label>
        <div class="field" id="address-box" style="display:none">
            <label for="address">Adresse de livraison</label>
            <textarea id="address" name="address" rows="2">{{ old('address') }}</textarea>
        </div>
        <div class="field"><label for="phone">Téléphone de contact</label>
            <input id="phone" type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required></div>
        <button class="btn block" type="submit">Valider et payer</button>
    </form>
    <div class="card">
        <h3>Récapitulatif</h3>
        @foreach($lines as $l)
            <div class="row between" style="margin-bottom:.4rem"><span>{{ $l['qty'] }} × {{ $l['product']->name }}</span><strong>@fc($l['total'])</strong></div>
        @endforeach
        <hr style="border-color:var(--line)">
        <div class="row between"><strong>Total</strong><span class="price">@fc($total)</span></div>
    </div>
</div>
@endsection
