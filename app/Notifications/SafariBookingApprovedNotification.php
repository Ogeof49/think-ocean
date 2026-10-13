<?php

namespace App\Notifications;

use App\Models\SafariBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SafariBookingApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(public SafariBooking $booking) {}

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Safari booking approved',
            'message' => "Your {$this->booking->package_name} booking has been approved.",
            'booking_reference' => $this->booking->reference,
            'url' => route('omney-safari.bookings'),
        ];
    }
}
