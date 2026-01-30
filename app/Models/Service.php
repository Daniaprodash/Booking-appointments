<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'title',
        'description', 
    ];

    public function doctors()
{
    return $this->belongsToMany(Doctor::class);
}
}
