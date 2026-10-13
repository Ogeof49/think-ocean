<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SafariBooking>
 */
class SafariBookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'reference' => (string) Str::uuid(),
            'package_name' => 'Murchison Express',
            'travel_date' => now()->addMonth(),
            'travellers' => 2,
            'amount' => 3000000,
            'currency' => 'UGX',
            'phone_number' => '256700000000',
            'payment_method' => 'mtn_momo',
        ];
    }
}
