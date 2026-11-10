<?php

namespace App\Providers;

use App\Support\Studio;
use Carbon\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        // Fuseau horaire et langue de Lubumbashi
        config(['app.timezone' => 'Africa/Lubumbashi']);
        date_default_timezone_set('Africa/Lubumbashi');
        app()->setLocale('fr');
        Carbon::setLocale('fr');

        // @fc($montant) -> "69 000 FC"
        Blade::directive('fc', fn ($expr) => "<?php echo \\App\\Support\\Studio::fc($expr); ?>");
        // @badge($statut) -> pastille colorée
        Blade::directive('badge', fn ($expr) => "<?php echo \\App\\Support\\Studio::badge($expr); ?>");

        View::composer('layouts.*', function ($view) {
            $view->with('cartCount', array_sum(session('cart', [])));
        });
    }
}
