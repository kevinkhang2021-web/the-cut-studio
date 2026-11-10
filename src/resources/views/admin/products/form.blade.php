@extends('layouts.admin')
@section('title', $product->exists ? 'Modifier un article' : 'Nouvel article')
@section('content')
<h1>{{ $product->exists ? 'Modifier l\'article' : 'Nouvel article' }}</h1>
<form method="POST" enctype="multipart/form-data" class="card" style="max-width:640px"
      action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}">
    @csrf
    @if($product->exists) @method('PUT') @endif
    <div class="field"><label>Nom</label><input type="text" name="name" value="{{ old('name', $product->name) }}" required></div>
    <div class="grid c2">
        <div class="field"><label>Catégorie</label>
            <select name="category">@foreach(\App\Models\Product::CATEGORIES as $k => $v)<option value="{{ $k }}" @selected(old('category', $product->category) === $k)>{{ $v }}</option>@endforeach</select></div>
        <div class="field"><label>Plateforme (PS5, PS4…)</label><input type="text" name="platform" value="{{ old('platform', $product->platform) }}"></div>
        <div class="field"><label>Prix en dollars ($)</label><input type="number" step="0.01" min="0" name="price_usd" value="{{ old('price_usd', $product->price_usd) }}" required></div>
        <div class="field"><label>Stock</label><input type="number" min="0" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" required></div>
    </div>
    <p class="muted small">Le prix en FC est calculé automatiquement avec le taux de change (Paramètres).</p>
    <div class="field"><label>Description</label><textarea name="description" rows="3">{{ old('description', $product->description) }}</textarea></div>
    <div class="field"><label>Image (facultatif)</label><input type="file" name="image" accept="image/*">
        @if($product->image_url)<img src="{{ $product->image_url }}" alt="" style="height:80px;margin-top:.5rem;border-radius:8px">@endif</div>
    <div class="field"><label class="check"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active))> Visible dans le catalogue</label></div>
    <div class="row"><button class="btn" type="submit">Enregistrer</button><a class="btn outline" href="{{ route('admin.products.index') }}">Annuler</a></div>
</form>
@endsection
