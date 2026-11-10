@extends('layouts.app')
@section('title', 'Inscription')
@section('content')
<div class="card form-narrow">
    <h2>Créer un compte</h2>
    <form method="POST" action="{{ route('register.post') }}">@csrf
        <div class="field"><label for="name">Nom complet</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus></div>
        <div class="field"><label for="phone">Téléphone (ex. 0990000000)</label>
            <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" required></div>
        <div class="field"><label for="email">Email (facultatif)</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"></div>
        <div class="field"><label for="password">Mot de passe (6 caractères minimum)</label>
            <input id="password" type="password" name="password" required></div>
        <div class="field"><label for="password_confirmation">Confirmer le mot de passe</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required></div>
        <button class="btn block" type="submit">Créer mon compte</button>
    </form>
    <p class="center muted small" style="margin-top:1rem">Vos informations ne servent qu'à gérer vos réservations, commandes et paiements.</p>
    <p class="center muted">Déjà inscrit ? <a href="{{ route('login') }}">Connexion</a></p>
</div>
@endsection
