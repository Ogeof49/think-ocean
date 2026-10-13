<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSafariBookingRequest;
use App\Models\SafariBooking;
use App\Models\User;
use App\Notifications\SafariBookingApprovedNotification;
use App\Services\SafariPesapalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class SafariBookingController extends Controller
{
    public function store(StoreSafariBookingRequest $request, SafariPesapalService $pesapal): RedirectResponse
    {
        $validated = $request->validated();
        $user = $request->user() ?? User::query()->firstOrCreate(
            ['email' => $validated['email']],
            [
                'name' => $validated['name'],
                'password' => Str::password(32),
            ],
        );

        $booking = SafariBooking::query()->create([
            ...collect($validated)->except(['name', 'email'])->all(),
            'user_id' => $user->id,
            'reference' => (string) Str::uuid(),
            'amount' => $validated['amount'],
            'currency' => 'UGX',
        ]);

        try {
            $checkout = $pesapal->submit($booking->setRelation('user', $user));
            $booking->update([
                'pesapal_tracking_id' => $checkout['order_tracking_id'],
                'pesapal_redirect_url' => $checkout['redirect_url'],
                'payment_details' => $checkout,
            ]);

            return redirect()->away($checkout['redirect_url']);
        } catch (\Throwable $exception) {
            Log::error('Safari booking payment initiation failed', [
                'booking' => $booking->id,
                'message' => $exception->getMessage(),
            ]);

            return to_route('omney-safari.bookings')->with('error', 'Your booking was saved, but Pesapal checkout could not start. Please try again.');
        }
    }

    public function callback(Request $request, SafariPesapalService $pesapal): RedirectResponse
    {
        $this->syncPayment($request, $pesapal);

        return to_route('omney-safari.bookings')->with('success', 'Payment received. Your booking status has been updated.');
    }

    public function ipn(Request $request, SafariPesapalService $pesapal): Response
    {
        $this->syncPayment($request, $pesapal);

        return response('OK');
    }

    private function syncPayment(Request $request, SafariPesapalService $pesapal): void
    {
        $trackingId = (string) $request->input('OrderTrackingId', $request->input('orderTrackingId'));

        if ($trackingId === '') {
            return;
        }

        $booking = SafariBooking::query()->where('pesapal_tracking_id', $trackingId)->first();

        if (! $booking) {
            return;
        }

        $status = $pesapal->status($trackingId);
        $isPaid = (int) ($status['status_code'] ?? 0) === 1
            || strtolower((string) ($status['payment_status_description'] ?? '')) === 'completed';

        $booking->update([
            'payment_status' => $isPaid ? 'completed' : strtolower((string) ($status['payment_status_description'] ?? 'pending')),
            'booking_status' => $isPaid ? 'approved' : 'pending',
            'approved_at' => $isPaid ? now() : null,
            'payment_details' => $status,
        ]);

        if ($isPaid && ! $booking->user->notifications()->where('data->booking_reference', $booking->reference)->exists()) {
            $booking->user->notify(new SafariBookingApprovedNotification($booking));
        }
    }
}
