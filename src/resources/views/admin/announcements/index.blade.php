@extends('layouts.admin')
@section('title', 'Annonces')
@section('content')
<h1>📣 Annonces et offres</h1>
<p class="muted">Les annonces actives s'affichent sur la page d'accueil. Le bouton WhatsApp ouvre WhatsApp avec le message prêt à envoyer à vos contacts ou à votre groupe.</p>
<form method="POST" action="{{ route('admin.announcements.store') }}" class="card" style="margin-bottom:1rem;max-width:640px">@csrf
    <div class="field"><label>Titre</label><input type="text" name="title" required></div>
    <div class="field"><label>Message</label><textarea name="body" rows="3" required></textarea></div>
    <button class="btn" type="submit">Publier l'annonce</button>
</form>
@foreach($announcements as $a)
    <div class="card" style="margin-bottom:.8rem">
        <div class="row between"><h3 style="margin:0">{{ $a->title }}</h3>@if($a->is_active)<span class="badge ok">Visible</span>@else<span class="badge warn">Masquée</span>@endif</div>
        <p>{{ $a->body }}</p>
        <div class="actions">
            <a class="btn sm ok" target="_blank" rel="noopener" href="https://wa.me/?text={{ urlencode('*' . $a->title . "*\n" . $a->body . "\n\nThe Cut Studio – " . \App\Support\Studio::setting('address')) }}">Partager sur WhatsApp</a>
            <form method="POST" action="{{ route('admin.announcements.update', $a) }}">@csrf @method('PUT')<button class="btn sm outline" type="submit">{{ $a->is_active ? 'Masquer' : 'Afficher' }}</button></form>
            <form method="POST" action="{{ route('admin.announcements.destroy', $a) }}" onsubmit="return confirm('Supprimer ?')">@csrf @method('DELETE')<button class="btn sm danger" type="submit">Supprimer</button></form>
        </div>
    </div>
@endforeach
@endsection
