<?php

namespace App\Providers;

use App\Events\AppointmentCancelled;
use App\Events\AppointmentConfirmed;
use App\Events\AppointmentDeleted;
use App\Listeners\StoreAppointmentCancelledActivity;
use App\Listeners\StoreAppointmentConfirmedActivity;
use App\Listeners\StoreAppointmentDeletedActivity;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

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
        Event::listen(AppointmentCancelled::class, StoreAppointmentCancelledActivity::class);
        Event::listen(AppointmentConfirmed::class, StoreAppointmentConfirmedActivity::class);
        Event::listen(AppointmentDeleted::class, StoreAppointmentDeletedActivity::class);
    }
}
