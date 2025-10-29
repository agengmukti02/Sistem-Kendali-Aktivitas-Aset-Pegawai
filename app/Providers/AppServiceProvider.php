<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;

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
        // Listen for login events to redirect users based on their roles
        Event::listen(Login::class, function (Login $event) {
            $user = $event->user;
            
            if ($user->hasRole('admin')) {
                session(['intended_redirect' => route('filament.admin.pages.dashboard-atasan')]);
            } elseif ($user->hasRole('operator')) {
                session(['intended_redirect' => route('filament.admin.resources.activities.index')]);
            } elseif ($user->hasRole('atasan')) {
                session(['intended_redirect' => route('filament.admin.pages.dashboard-atasan')]);
            } elseif ($user->hasRole('pegawai')) {
                session(['intended_redirect' => route('filament.admin.pages.employee-submission')]);
            } else {
                session(['intended_redirect' => route('filament.admin.pages.dashboard-atasan')]);
            }
        });
    }
}
