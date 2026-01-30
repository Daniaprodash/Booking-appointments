<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = [
        'name',
        'specialty',
        'phone',
        'email',
        'bio',
        'image'
    ];
    public function appointments()
{
    return $this->hasMany(Appointment::class);
}
// علاقة عكسية: الطبيب ينتمي لمستخدم
public function user()
{
    return $this->belongsTo(User::class);
}
public function services()
{
    return $this->belongsToMany(Service::class);
}

}
