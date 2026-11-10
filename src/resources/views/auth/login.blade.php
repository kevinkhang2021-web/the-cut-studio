@extends('layouts.app')
@section('title', 'Connexion')
@section('content')
<div class="card form-narrow">
    <h2>Connexion</h2>
    <form method="POST" action="{{ route('login.post') }}">@csrf
        <div class="field"><label for="identifier">Téléphone ou email</label>
            <input id="identifier" type="text" name="identifier" value="{{ old('identifier') }}" required autofocus></div>
        <div class="field"><label for="password">Mot de passe</label>
            <input id="password" type="password" name="password" required></div>
        <div class="field"><label class="check"><input type="checkbox" name="remember" value="1"> Rester connecté</label></div>
        <button class="btn block" type="submit">Se connecter</button>
    </form>
    <p class="center muted" style="margin-top:1rem">Pas encore de compte ? <a href="{{ route('register') }}">Inscrivez-vous</a></p>
</div>
@endsection
