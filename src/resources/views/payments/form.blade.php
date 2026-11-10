@extends('layouts.app')
@section('title', 'Paiement')
@section('content')
<div class="card form-narrow">
    <h2>Paiement</h2>
    <p>{{ $payable->summaryLabel() }}</p>
    <p class="price" style="font-size:1.6rem">@fc($payable->amountDue())</p>

    <form method="POST" action="{{ route('payments.process', ['type' => $type, 'id' => $payable->id]) }}">@csrf
        <label>Moyen de paiement mobile</label>
        @foreach(['mpesa' => 'M-Pesa', 'airtel' => 'Airtel Money', 'orange' => 'Orange Money'] as $k => $v)
            <label class="radio-card"><input type="radio" name="method" value="{{ $k }}" @checked(old('method', 'mpesa') === $k)> {{ $v }}</label>
        @endforeach
        <div class="field"><label for="phone">Numéro mobile money</label>
            <input id="phone" type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required></div>
        <button class="btn block" type="submit">Payer @fc($payable->amountDue())</button>
    </form>
    <div class="alert info" style="margin-top:1rem">
        Mode démonstration : le paiement mobile est simulé (aucun argent n'est débité).
        Vous pouvez aussi payer en espèces directement à la salle.
    </div>
</div>
@endsection
