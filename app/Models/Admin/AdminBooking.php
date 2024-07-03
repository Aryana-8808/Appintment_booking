<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'appointment_date',
        'phone',
        'status',
        'description',
        'duration', // Add duration to fillable
    ];

    public function getEndTimeAttribute()
    {
        return Carbon::parse($this->appointment_date)->addMinutes($this->duration);
    }
}
