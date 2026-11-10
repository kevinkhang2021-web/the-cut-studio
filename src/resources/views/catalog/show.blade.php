@extends('layouts.app')
@section('title', $product->name)
@section('content')
<p><a href="{{ route('catalog') }}">← Retour au catalogue</a></p>
<div class="grid c2">
    <div class="card" style="padding:0;overflow:hidden">
        @if($product->image_url)
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width:100%;background:#fff">
        @else
            <div class="product"><div class="thumb" style="font-size:6rem">{{ ['console' => '🎮', 'jeu' => '💿', 'accessoire' => '🎧'][$product->category] ?? '🎮' }}</div></div>
        @endif
    </div>
    <div class="card">
        <span class="tag">{{ $product->category_label }}</span> <span class="tag">{{ $product->gamme_label }}</span>
        <h1 style="margin-top:.6rem">{{ $product->name }}</h1>
        <div class="price" style="font-size:1.8rem">@fc($product->price_fc) <small>(≈ {{ number_format($product->price_usd, 0, ',', ' ') }} $)</small></div>
        <p class="muted">{{ $product->description }}</p>
        <p>@if($product->stock > 0)<span class="badge ok">En stock : {{ $product->stock }}</span>@else<span class="badge bad">Rupture de stock</span>@endif</p>
        @if($product->stock > 0)
            <form method="POST" action="{{ route('cart.add', $product) }}" class="inline-form">@csrf
                <div class="field" style="max-width:100px"><label>Quantité</label><input type="number" name="qty" value="1" min="1" max="{{ $product->stock }}"></div>
                <button class="btn" type="submit">🛒 Ajouter au panier</button>
            </form>
        @endif
    </div>
</div>
@endsection
