@extends('layouts.admin')
@section('title', 'Avis et réclamations')
@section('content')
<h1>⭐ Avis et réclamations</h1>
<form method="GET" class="card inline-form" style="margin-bottom:1rem">
    <div class="field"><label>Type</label>
        <select name="type"><option value="">Tous</option><option value="review" @selected(request('type') === 'review')>Avis</option><option value="complaint" @selected(request('type') === 'complaint')>Réclamations</option></select></div>
    <button class="btn" type="submit">Filtrer</button>
</form>
@forelse($reviews as $r)
    <div class="card" style="margin-bottom:.8rem">
        <div class="row between">
            <span>@badge($r->type) <strong>{{ $r->user->name }}</strong> <span class="muted small">{{ $r->user->phone }} · {{ $r->created_at->format('d/m/Y') }}</span></span>
            @if($r->rating)<span class="stars">{{ str_repeat('★', $r->rating) }}</span>@endif
        </div>
        <p style="margin-top:.6rem">{{ $r->message }}</p>
        <form method="POST" action="{{ route('admin.reviews.update', $r) }}" class="inline-form">@csrf @method('PUT')
            <div class="field"><label>Réponse du gérant</label><input type="text" name="response" value="{{ $r->response }}"></div>
            <button class="btn sm" type="submit">Répondre</button>
        </form>
        <form method="POST" action="{{ route('admin.reviews.destroy', $r) }}" onsubmit="return confirm('Supprimer ce message ?')" style="margin-top:.5rem">@csrf @method('DELETE')
            <button class="linklike" type="submit">Supprimer</button></form>
    </div>
@empty
    <div class="card center muted">Aucun message.</div>
@endforelse
{{ $reviews->links('partials.pagination') }}
@endsection
