<?php

namespace App\Services;

use App\Models\SafariBooking;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class SafariPesapalService
{
    public function submit(SafariBooking $booking): array
    {
        $token = $this->token();
        $notificationId = $this->notificationId($token);

        $response = $this->client($token)->post($this->baseUrl().'/Transactions/SubmitOrderRequest', [
            'id' => $booking->reference,
            'currency' => $booking->currency,
            'amount' => (float) $booking->amount,
            'description' => $booking->package_name.' safari booking',
            'callback_url' => route('omney-safari.booking.callback'),
            'notification_id' => $notificationId,
            'billing_address' => [
                'email_address' => $booking->user->email,
                'phone_number' => $booking->phone_number,
                'first_name' => $booking->user->first_name ?? $booking->user->name,
                'last_name' => $booking->user->last_name ?? '',
            ],
        ])->throw()->json();

        if (empty($response['redirect_url']) || empty($response['order_tracking_id'])) {
            throw new RuntimeException('Pesapal did not return a checkout URL.');
        }

        return $response;
    }

    public function status(string $trackingId): array
    {
        return $this->client($this->token())
            ->get($this->baseUrl().'/Transactions/GetTransactionStatus', ['orderTrackingId' => $trackingId])
            ->throw()
            ->json();
    }

    private function token(): string
    {
        return Cache::remember('safari_pesapal_access_token', now()->addMinutes(4), function (): string {
            $response = Http::acceptJson()->post($this->baseUrl().'/Auth/RequestToken', [
                'consumer_key' => config('pesapal.consumer_key'),
                'consumer_secret' => config('pesapal.consumer_secret'),
            ])->throw()->json();

            return $response['token'] ?? throw new RuntimeException('Unable to authenticate with Pesapal.');
        });
    }

    private function notificationId(string $token): string
    {
        return Cache::remember('safari_pesapal_ipn_id', now()->addDay(), function () use ($token): string {
            $response = $this->client($token)->post($this->baseUrl().'/URLSetup/RegisterIPN', [
                'url' => route('omney-safari.booking.ipn'),
                'ipn_notification_type' => 'GET',
            ])->throw()->json();

            return $response['ipn_id'] ?? throw new RuntimeException('Unable to register the Pesapal notification URL.');
        });
    }

    private function client(string $token): PendingRequest
    {
        return Http::acceptJson()->withToken($token)->timeout(30);
    }

    private function baseUrl(): string
    {
        return config('pesapal.environment') === 'live'
            ? 'https://pay.pesapal.com/v3/api'
            : 'https://cybqa.pesapal.com/pesapalv3/api';
    }
}
