<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorPatientEmail extends Model
{
    protected $fillable = [
        'user_id',
        'doctor_id',
        'subject',
        'message',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
