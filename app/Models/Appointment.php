<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'user_id',
        'doctor_id',
        'service_id',
        'appointment_date',
        'appointment_time',
        'status',
        'notes'
    ];

        // العلاقات
        public function user()
        {
            return $this->belongsTo(User::class);
        }
    
        public function doctor()
        {
            return $this->belongsTo(Doctor::class);
        }
    
        public function service()
        {
            return $this->belongsTo(Service::class);
        }
        public function activities()
{
    return $this->hasMany(Activity::class);
}
}
