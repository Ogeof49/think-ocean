<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SafariBooking extends Model
{
    /** @use HasFactory<\Database\Factories\SafariBookingFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reference',
        'package_name',
        'travel_date',
        'travellers',
        'amount',
        'currency',
        'phone_number',
        'payment_method',
        'payment_status',
        'booking_status',
        'pesapal_tracking_id',
        'pesapal_redirect_url',
        'payment_details',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'travel_date' => 'date',
            'amount' => 'decimal:2',
            'payment_details' => 'array',
            'approved_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
