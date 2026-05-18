<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate; // <--- Baris ini sudah ditambahkan agar Gate tidak eror lagi

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
        // Gate untuk Owner
        Gate::define('owner', function ($user) {
            return $user->role === 'owner';
        });

        // Gate untuk Pegawai
        Gate::define('pegawai', function ($user) {
            return $user->role === 'pegawai';
        });
    }
}