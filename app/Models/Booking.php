<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
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

    protected $dates = [
        'appointment_date',
    ];

    // Accessor for the calculated end time
    public function getEndTimeAttribute()
    {
        return Carbon::parse($this->appointment_date)->addMinutes($this->duration);
    }
}
