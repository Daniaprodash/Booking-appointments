<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\AppointmentConfirmed;
use App\Models\Activity;

class StoreAppointmentConfirmedActivity
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AppointmentConfirmed $event): void
    {
        $appointment = $event->appointment;

        Activity::create([
            'user_id'        => $appointment->user_id,
            'doctor_id'      => $appointment->doctor_id,
            'service_id'     => $appointment->service_id,
            'appointment_id' => $appointment->id,
            'type'           => 'appointment_confirmed',
            'message'        => "تم تأكيد موعد المريض: " . $appointment->user->name,
        ]);
    }
}
