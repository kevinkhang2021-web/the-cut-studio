<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TournamentController;
use App\Http\Middleware\AdminOnly;
use Illuminate\Support\Facades\Route;

// ---------- Public ----------
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalogue', [CatalogController::class, 'index'])->name('catalog');
Route::get('/catalogue/{product}', [CatalogController::class, 'show'])->name('catalog.show');
Route::get('/tournois', [TournamentController::class, 'index'])->name('tournaments.index');
Route::get('/tournois/{tournament}', [TournamentController::class, 'show'])->name('tournaments.show');
Route::get('/avis', [ReviewController::class, 'index'])->name('reviews.index');

// Panier (accessible sans compte ; la commande exige une connexion)
Route::get('/panier', [CartController::class, 'show'])->name('cart');
Route::post('/panier/ajouter/{product}', [CartController::class, 'add'])->name('cart.add');
Route::post('/panier/modifier/{product}', [CartController::class, 'update'])->name('cart.update');
Route::post('/panier/retirer/{product}', [CartController::class, 'remove'])->name('cart.remove');

// ---------- Invités ----------
Route::middleware('guest')->group(function () {
    Route::get('/connexion', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/connexion', [AuthController::class, 'login'])->name('login.post');
    Route::get('/inscription', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/inscription', [AuthController::class, 'register'])->name('register.post');
});

// ---------- Clients connectés ----------
Route::middleware('auth')->group(function () {
    Route::post('/deconnexion', [AuthController::class, 'logout'])->name('logout');

    Route::get('/mon-compte', [AccountController::class, 'index'])->name('account');
    Route::put('/mon-compte', [AccountController::class, 'update'])->name('account.update');

    Route::get('/commande', [CartController::class, 'checkout'])->name('checkout');
    Route::post('/commande', [CartController::class, 'place'])->name('checkout.place');

    Route::get('/mes-reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reserver', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reserver', [ReservationController::class, 'store'])->name('reservations.store');
    Route::post('/reservations/{reservation}/annuler', [ReservationController::class, 'cancel'])->name('reservations.cancel');

    Route::get('/paiement/{type}/{id}', [PaymentController::class, 'show'])->name('payments.show');
    Route::post('/paiement/{type}/{id}', [PaymentController::class, 'process'])->name('payments.process');
    Route::get('/recu/{payment}', [PaymentController::class, 'receipt'])->name('payments.receipt');

    Route::post('/tournois/{tournament}/inscription', [TournamentController::class, 'register'])->name('tournaments.register');
    Route::post('/avis', [ReviewController::class, 'store'])->name('reviews.store');
});

// ---------- Administration ----------
Route::prefix('admin')->name('admin.')->middleware(['auth', AdminOnly::class])->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', Admin\ProductController::class)->except('show');

    Route::get('equipment', [Admin\EquipmentController::class, 'index'])->name('equipment.index');
    Route::post('equipment', [Admin\EquipmentController::class, 'store'])->name('equipment.store');
    Route::put('equipment/{equipment}', [Admin\EquipmentController::class, 'update'])->name('equipment.update');
    Route::delete('equipment/{equipment}', [Admin\EquipmentController::class, 'destroy'])->name('equipment.destroy');
    Route::post('equipment/{equipment}/incidents', [Admin\EquipmentController::class, 'reportIncident'])->name('incidents.store');
    Route::put('incidents/{incident}', [Admin\EquipmentController::class, 'resolveIncident'])->name('incidents.resolve');

    Route::get('reservations', [Admin\ReservationController::class, 'index'])->name('reservations.index');
    Route::put('reservations/{reservation}', [Admin\ReservationController::class, 'update'])->name('reservations.update');
    Route::post('reservations/{reservation}/cash', [Admin\ReservationController::class, 'cash'])->name('reservations.cash');

    Route::get('orders', [Admin\OrderController::class, 'index'])->name('orders.index');
    Route::put('orders/{order}', [Admin\OrderController::class, 'update'])->name('orders.update');
    Route::post('orders/{order}/cash', [Admin\OrderController::class, 'cash'])->name('orders.cash');

    Route::resource('tournaments', Admin\TournamentController::class)->except('show');
    Route::get('tournaments/{tournament}/resultats', [Admin\TournamentController::class, 'results'])->name('tournaments.results');
    Route::post('tournaments/{tournament}/resultats', [Admin\TournamentController::class, 'saveResults'])->name('tournaments.results.save');

    Route::get('expenses', [Admin\ExpenseController::class, 'index'])->name('expenses.index');
    Route::post('expenses', [Admin\ExpenseController::class, 'store'])->name('expenses.store');
    Route::delete('expenses/{expense}', [Admin\ExpenseController::class, 'destroy'])->name('expenses.destroy');

    Route::get('clients', [Admin\ClientController::class, 'index'])->name('clients.index');
    Route::get('clients/{client}', [Admin\ClientController::class, 'show'])->name('clients.show');

    Route::get('reviews', [Admin\ReviewController::class, 'index'])->name('reviews.index');
    Route::put('reviews/{review}', [Admin\ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('reviews/{review}', [Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::get('announcements', [Admin\AnnouncementController::class, 'index'])->name('announcements.index');
    Route::post('announcements', [Admin\AnnouncementController::class, 'store'])->name('announcements.store');
    Route::put('announcements/{announcement}', [Admin\AnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('announcements/{announcement}', [Admin\AnnouncementController::class, 'destroy'])->name('announcements.destroy');

    Route::get('settings', [Admin\SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [Admin\SettingController::class, 'update'])->name('settings.update');
});
