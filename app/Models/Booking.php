<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SlotAvailableNotification;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'appointment_date',
        'phone',
        'status',
        'description',
        'duration',
        'slot_id',
        'user_id',
        'waitlist'
    ];

    // protected $dates = [
    //     'appointment_date',
    // ];

    // Accessor for the calculated end time
    public function getEndTimeAttribute()
    {
        return Carbon::parse($this->appointment_date)->addMinutes($this->duration);
    }

    public function getWaitlist()
    {
        return json_decode($this->waitlist, true) ?? [];
    }

    // Function to set waitlist as a JSON string
    public function setWaitlist(array $waitlist)
    {
        $this->waitlist = json_encode($waitlist);
    }

    public function addToWaitlist($userId)
    {
        $waitlist = $this->getWaitlist();
        if (!in_array($userId, $waitlist)) {
            $waitlist[] = $userId;
            $this->setWaitlist($waitlist);
            $this->save();
        }
    }

    public function removeFromWaitlist($userId)
    {
        $waitlist = $this->getWaitlist();
        if (($key = array_search($userId, $waitlist)) !== false) {
            unset($waitlist[$key]);
            $this->setWaitlist(array_values($waitlist));
            $this->save();
        }
    }

    public function notifyWaitlist()
    {
        $waitlist = $this->getWaitlist();
        foreach ($waitlist as $userId) {
            $user = User::find($userId);
            if ($user) {
                Notification::send($user, new SlotAvailableNotification($this));
            }
        }
    }
}
