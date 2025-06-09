<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        FilamentAsset::register([
            // Js::make('chart-datalabels-plugin', mix('js/filament/chart-plugins.js')),
            // Sesuaikan 'mix()' dengan 'public_path()' jika Anda menggunakan Vite
            Js::make('chart-datalabels-plugin', public_path('build/assets/chart-plugins.js')),
        ]);
    }
}
