<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Administration') – The Cut Studio</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<input type="checkbox" id="admin-toggle" style="display:none">
<div class="admin-top">
    <strong>⚙️ Administration</strong>
    <label for="admin-toggle" class="burger" style="display:block">☰</label>
</div>
<div class="admin-shell">
    <aside class="sidebar">
        <a class="brand" href="{{ route('admin.dashboard') }}">🎮 The Cut <span>Studio</span></a>
        @php $nav = [
            ['admin.dashboard', '📊 Tableau de bord', 'admin.dashboard'],
            ['admin.reservations.index', '📅 Réservations', 'admin.reservations.*'],
            ['admin.orders.index', '📦 Commandes', 'admin.orders.*'],
            ['admin.products.index', '🛍️ Catalogue', 'admin.products.*'],
            ['admin.tournaments.index', '🏆 Tournois', 'admin.tournaments.*'],
            ['admin.equipment.index', '🕹️ Postes et pannes', 'admin.equipment.*'],
            ['admin.expenses.index', '💸 Dépenses', 'admin.expenses.*'],
            ['admin.clients.index', '👥 Clients', 'admin.clients.*'],
            ['admin.reviews.index', '⭐ Avis et réclamations', 'admin.reviews.*'],
            ['admin.announcements.index', '📣 Annonces', 'admin.announcements.*'],
            ['admin.settings.edit', '🔧 Paramètres', 'admin.settings.*'],
        ]; @endphp
        @foreach($nav as [$route, $label, $pattern])
            <a class="item {{ request()->routeIs($pattern) ? 'active' : '' }}" href="{{ route($route) }}">{{ $label }}</a>
        @endforeach
        <hr style="border-color:var(--line);margin:1rem 0">
        <a class="item" href="{{ route('home') }}">↩ Voir le site</a>
        <form method="POST" action="{{ route('logout') }}">@csrf
            <button class="btn outline sm block" type="submit">Déconnexion</button>
        </form>
    </aside>
    <div class="admin-main">
        @include('partials.flash')
        @yield('content')
    </div>
</div>
</body>
</html>
