<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'The Cut Studio') – Salle de jeux vidéo, Lubumbashi</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<header class="site-header">
    <div class="container bar">
        <a class="brand" href="{{ route('home') }}">🎮 The Cut <span>Studio</span></a>
        <input type="checkbox" id="nav-toggle">
        <label for="nav-toggle" class="burger">☰</label>
        <nav class="nav">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Accueil</a>
            <a href="{{ route('catalog') }}" class="{{ request()->routeIs('catalog*') ? 'active' : '' }}">Catalogue</a>
            <a href="{{ route('reservations.create') }}" class="{{ request()->routeIs('reservations.*') ? 'active' : '' }}">Réserver</a>
            <a href="{{ route('tournaments.index') }}" class="{{ request()->routeIs('tournaments.*') ? 'active' : '' }}">Tournois</a>
            <a href="{{ route('reviews.index') }}" class="{{ request()->routeIs('reviews.*') ? 'active' : '' }}">Avis</a>
            <span class="spacer"></span>
            <a href="{{ route('cart') }}" class="cart-link">🛒 Panier @if($cartCount > 0)<span class="cart-count">{{ $cartCount }}</span>@endif</a>
            @auth
                @if(auth()->user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}">⚙️ Admin</a>
                @endif
                <a href="{{ route('account') }}">👤 {{ \Illuminate\Support\Str::words(auth()->user()->name, 1, '') }}</a>
                <form method="POST" action="{{ route('logout') }}">@csrf
                    <button class="btn outline sm" type="submit">Déconnexion</button>
                </form>
            @else
                <a href="{{ route('login') }}">Connexion</a>
                <a href="{{ route('register') }}" class="btn sm">Inscription</a>
            @endauth
        </nav>
    </div>
</header>

<main class="container">
    @include('partials.flash')
    @yield('content')
</main>

<footer class="site-footer">
    <div class="container row between">
        <div>
            <strong>The Cut Studio</strong> – salle de jeux vidéo<br>
            📍 {{ \App\Support\Studio::setting('address') }}
            @if(\App\Support\Studio::setting('phone')) · 📞 {{ \App\Support\Studio::setting('phone') }} @endif
        </div>
        <div>
            Ouvert de {{ \App\Support\Studio::setting('open_hour') }}h à {{ \App\Support\Studio::setting('close_hour') }}h<br>
            <span class="small">Projet UPL – Groupe UBUNTU 1</span>
        </div>
    </div>
</footer>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
