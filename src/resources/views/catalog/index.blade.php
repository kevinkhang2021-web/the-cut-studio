@extends('layouts.app')
@section('title', 'Catalogue')
@section('content')
<h1>Catalogue</h1>
<p class="muted">Consoles PS5 et jeux PS4 avec leurs prix en francs congolais (taux : 1 $ = {{ number_format(\App\Support\Studio::rate(), 0, ',', ' ') }} FC).</p>

<form method="GET" action="{{ route('catalog') }}" class="card inline-form" style="margin-bottom:1.2rem">
    <div class="field"><label>Recherche</label><input type="text" name="q" value="{{ request('q') }}" placeholder="Nom du jeu ou de la console"></div>
    <div class="field"><label>Catégorie</label>
        <select name="category"><option value="">Toutes</option>
            @foreach(\App\Models\Product::CATEGORIES as $k => $v)<option value="{{ $k }}" @selected(request('category') === $k)>{{ $v }}</option>@endforeach
        </select></div>
    <div class="field"><label>Gamme de prix</label>
        <select name="gamme"><option value="">Toutes</option>
            <option value="petits" @selected(request('gamme') === 'petits')>Petits prix (≤ 92 000 FC)</option>
            <option value="moyens" @selected(request('gamme') === 'moyens')>Prix moyens (≤ 138 000 FC)</option>
            <option value="eleves" @selected(request('gamme') === 'eleves')>Prix élevés (&gt; 138 000 FC)</option>
        </select></div>
    <div class="field"><label>Tri</label>
        <select name="sort">
            <option value="price_asc" @selected(request('sort', 'price_asc') === 'price_asc')>Prix croissant</option>
            <option value="price_desc" @selected(request('sort') === 'price_desc')>Prix décroissant</option>
            <option value="name" @selected(request('sort') === 'name')>Nom (A-Z)</option>
        </select></div>
    <button class="btn" type="submit">Filtrer</button>
</form>

@if($products->count())
    <div class="grid c4">@foreach($products as $product) @include('partials.product-card') @endforeach</div>
    {{ $products->links('partials.pagination') }}
@else
    <div class="card center muted">Aucun article ne correspond à votre recherche.</div>
@endif
@endsection
