<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SlotAvailableNotification extends Notification
{
    protected $slotId;

    public function __construct($slotId)
    {
        $this->slotId = $slotId;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('A slot you were waitlisted for is now available.')
                    ->action('Book Now', url('/appointments/create?slot_id=' . $this->slotId))
                    ->line('Thank you for using our application!');
    }
}
