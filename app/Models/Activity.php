<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'user_id',
        'doctor_id',
        'service_id',
        'appointment_id',
        'type',
        'message',    
    ];

    public function appointment()
{
    return $this->belongsTo(Appointment::class);
}

    // المريض
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // الطبيب
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

}
