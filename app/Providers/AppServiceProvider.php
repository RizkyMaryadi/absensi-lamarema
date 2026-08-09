<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        //2. TAMBAHKAN KODE INI
        // Memaksa browser memuat semua file aset (CSS, JS, Gambar, Model Wajah) lewat HTTPS
        // URL::forceScheme('https');
    }
}