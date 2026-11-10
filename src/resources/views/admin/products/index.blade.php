@extends('layouts.admin')
@section('title', 'Catalogue')
@section('content')
<div class="row between"><h1>🛍️ Catalogue</h1><a class="btn" href="{{ route('admin.products.create') }}">+ Nouvel article</a></div>
<form method="GET" class="card inline-form" style="margin-bottom:1rem">
    <div class="field"><label>Recherche</label><input type="text" name="q" value="{{ request('q') }}"></div>
    <div class="field"><label>Catégorie</label>
        <select name="category"><option value="">Toutes</option>
            @foreach(\App\Models\Product::CATEGORIES as $k => $v)<option value="{{ $k }}" @selected(request('category') === $k)>{{ $v }}</option>@endforeach
        </select></div>
    <button class="btn" type="submit">Filtrer</button>
</form>
<div class="table-wrap"><table>
    <thead><tr><th>Article</th><th>Catégorie</th><th class="num">Prix ($)</th><th class="num">Prix (FC)</th><th class="num">Stock</th><th>Actif</th><th></th></tr></thead>
    <tbody>
    @foreach($products as $p)
        <tr>
            <td>{{ $p->name }}</td><td>{{ $p->category_label }}</td>
            <td class="num">{{ number_format($p->price_usd, 0, ',', ' ') }} $</td><td class="num">@fc($p->price_fc)</td>
            <td class="num">{{ $p->stock }}</td>
            <td>{!! $p->is_active ? '<span class="badge ok">Oui</span>' : '<span class="badge bad">Non</span>' !!}</td>
            <td class="actions">
                <a class="btn sm outline" href="{{ route('admin.products.edit', $p) }}">Modifier</a>
                <form method="POST" action="{{ route('admin.products.destroy', $p) }}" onsubmit="return confirm('Supprimer cet article ?')">@csrf @method('DELETE')
                    <button class="btn sm danger" type="submit">Supprimer</button></form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table></div>
{{ $products->links('partials.pagination') }}
@endsection
