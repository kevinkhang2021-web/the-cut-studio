@extends('layouts.app')
@section('title', 'Panier')
@section('content')
<h1>Mon panier</h1>
@if(count($lines))
<div class="table-wrap">
<table>
    <thead><tr><th>Article</th><th class="num">Prix</th><th>Quantité</th><th class="num">Total</th><th></th></tr></thead>
    <tbody>
    @foreach($lines as $l)
        <tr>
            <td><a href="{{ route('catalog.show', $l['product']) }}">{{ $l['product']->name }}</a></td>
            <td class="num">@fc($l['product']->price_fc)</td>
            <td>
                <form method="POST" action="{{ route('cart.update', $l['product']) }}" class="row" style="gap:.4rem">@csrf
                    <input type="number" name="qty" value="{{ $l['qty'] }}" min="0" max="{{ $l['product']->stock }}" style="width:80px">
                    <button class="btn outline sm" type="submit">OK</button>
                </form>
            </td>
            <td class="num">@fc($l['total'])</td>
            <td><form method="POST" action="{{ route('cart.remove', $l['product']) }}">@csrf<button class="linklike" type="submit">Retirer</button></form></td>
        </tr>
    @endforeach
    </tbody>
    <tfoot><tr><th colspan="3" class="num">Total</th><th class="num">@fc($total)</th><th></th></tr></tfoot>
</table>
</div>
<div class="row between" style="margin-top:1rem">
    <a href="{{ route('catalog') }}" class="btn outline">Continuer mes achats</a>
    @auth
        <a href="{{ route('checkout') }}" class="btn cyan">Passer la commande</a>
    @else
        <a href="{{ route('login') }}" class="btn cyan">Connectez-vous pour commander</a>
    @endauth
</div>
@else
    <div class="card center"><p class="muted">Votre panier est vide.</p><a class="btn" href="{{ route('catalog') }}">Voir le catalogue</a></div>
@endif
@endsection
