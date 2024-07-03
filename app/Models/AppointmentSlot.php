<?php

namespace App\Models;
use Faker\Generator as Faker;
use App\Models\AppointmentSlot;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_time',
        'end_time',
        'available',
    ];
}
