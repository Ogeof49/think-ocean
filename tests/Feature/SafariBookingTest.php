<?php

namespace Tests\Feature;

use App\Http\Requests\StoreSafariBookingRequest;
use App\Models\SafariBooking;
use App\Models\User;
use App\Services\SafariPesapalService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SafariBookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_safari_pages_have_separate_named_routes(): void
    {
        $this->assertSame('/omney-safari', route('omney-safari', absolute: false));
        $this->assertSame('/omney-safari/bookings', route('omney-safari.bookings', absolute: false));
        $this->assertSame('/omney-safari/faq', route('omney-safari.faq', absolute: false));
        $this->assertSame('/omney-safari/about-us', route('omney-safari.about', absolute: false));
    }

    public function test_booking_payment_callbacks_are_publicly_available(): void
    {
        $callback = Route::getRoutes()->getByName('omney-safari.booking.callback');
        $ipn = Route::getRoutes()->getByName('omney-safari.booking.ipn');

        $this->assertNotNull($callback);
        $this->assertNotNull($ipn);
        $this->assertContains('GET', $callback->methods());
        $this->assertContains('GET', $ipn->methods());
    }

    public function test_booking_request_accepts_supported_payment_options(): void
    {
        $rules = (new StoreSafariBookingRequest)->rules();

        $this->assertArrayHasKey('payment_method', $rules);
        $this->assertArrayHasKey('travel_date', $rules);
        $this->assertArrayHasKey('phone_number', $rules);
        $this->assertArrayHasKey('name', $rules);
        $this->assertArrayHasKey('email', $rules);
        $this->assertArrayHasKey('amount', $rules);
    }

    public function test_guest_can_submit_a_safari_booking(): void
    {
        Cache::flush();
        config()->set('pesapal.environment', 'live');
        config()->set('pesapal.consumer_key', 'live-key');
        config()->set('pesapal.consumer_secret', 'live-secret');

        Http::fake([
            'https://pay.pesapal.com/v3/api/Auth/RequestToken' => Http::response(['token' => 'access-token']),
            'https://pay.pesapal.com/v3/api/URLSetup/RegisterIPN' => Http::response(['ipn_id' => 'ipn-id']),
            'https://pay.pesapal.com/v3/api/Transactions/SubmitOrderRequest' => Http::response([
                'redirect_url' => 'https://pay.pesapal.com/checkout',
                'order_tracking_id' => 'tracking-id',
            ]),
        ]);

        $response = $this->post('/omney-safari/bookings', [
            'name' => 'Guest Traveller',
            'email' => 'guest@example.com',
            'package_name' => 'Murchison Express',
            'travel_date' => now()->addDays(2)->toDateString(),
            'travellers' => 2,
            'amount' => 475000,
            'phone_number' => '256700000000',
            'payment_method' => 'pesapal',
        ]);

        $response->assertRedirect('https://pay.pesapal.com/checkout');
        $this->assertDatabaseHas('users', ['email' => 'guest@example.com']);
        $this->assertDatabaseHas('safari_bookings', [
            'package_name' => 'Murchison Express',
            'pesapal_tracking_id' => 'tracking-id',
            'amount' => 475000,
        ]);
    }

    public function test_safari_checkout_uses_the_configured_live_pesapal_environment(): void
    {
        Cache::flush();
        config()->set('pesapal.environment', 'live');
        config()->set('pesapal.consumer_key', 'live-key');
        config()->set('pesapal.consumer_secret', 'live-secret');

        Http::fake([
            'https://pay.pesapal.com/v3/api/Auth/RequestToken' => Http::response(['token' => 'access-token']),
            'https://pay.pesapal.com/v3/api/URLSetup/RegisterIPN' => Http::response(['ipn_id' => 'ipn-id']),
            'https://pay.pesapal.com/v3/api/Transactions/SubmitOrderRequest' => Http::response([
                'redirect_url' => 'https://pay.pesapal.com/checkout',
                'order_tracking_id' => 'tracking-id',
            ]),
        ]);

        $booking = new SafariBooking([
            'reference' => 'SAFARI-TEST',
            'currency' => 'UGX',
            'amount' => 250000,
            'package_name' => 'Murchison Falls',
            'phone_number' => '256700000000',
        ]);
        $booking->setRelation('user', new User([
            'email' => 'traveller@example.com',
            'first_name' => 'Test',
            'last_name' => 'Traveller',
        ]));

        $result = app(SafariPesapalService::class)->submit($booking);

        $this->assertSame('tracking-id', $result['order_tracking_id']);
        Http::assertSent(fn ($request): bool => $request->url() === 'https://pay.pesapal.com/v3/api/Auth/RequestToken');
    }

    public function test_booking_payment_options_have_local_brand_images(): void
    {
        $bookingView = file_get_contents(resource_path('views/omney-safari/bookings.blade.php'));
        $airtelLogo = file_get_contents(public_path('images/payments/airtel-money.svg'));

        foreach (['mtn-momo.svg', 'airtel-money.svg', 'pesapal.svg'] as $logo) {
            $this->assertFileExists(public_path("images/payments/{$logo}"));
            $this->assertStringContainsString("images/payments/{$logo}", $bookingView);
        }

        $this->assertStringContainsString('#ed1c24', $airtelLogo);
        $this->assertStringContainsString('>airtel<', $airtelLogo);
        $this->assertStringContainsString('>money<', $airtelLogo);
        $this->assertStringNotContainsString('<rect', $airtelLogo);
    }

    public function test_safari_packages_include_bookable_day_by_day_itineraries(): void
    {
        $homeView = file_get_contents(resource_path('views/omney-safari.blade.php'));
        $bookingView = file_get_contents(resource_path('views/omney-safari/bookings.blade.php'));

        $this->assertSame(3, substr_count($homeView, 'View day-by-day itinerary'));
        $this->assertSame(3, substr_count($homeView, "route('omney-safari.bookings', ['package' =>"));
        $this->assertStringContainsString("request('package')", $bookingView);
    }

    public function test_booking_page_has_professional_checkout_structure(): void
    {
        $bookingView = file_get_contents(resource_path('views/omney-safari/bookings.blade.php'));

        $this->assertStringContainsString('class="booking-shell"', $bookingView);
        $this->assertStringContainsString('class="booking-progress"', $bookingView);
        $this->assertStringContainsString('What happens next?', $bookingView);
        $this->assertStringContainsString('Continue to secure payment', $bookingView);
        $this->assertStringContainsString('name="amount"', $bookingView);
        $this->assertStringContainsString('class="quick-amounts"', $bookingView);
        $this->assertStringContainsString('summary-card', $bookingView);
        $this->assertStringContainsString('does not collect your mobile-money PIN', $bookingView);
        $this->assertStringContainsString("route('login')", $bookingView);
        $this->assertStringContainsString('class="booking-history-grid"', $bookingView);
    }
}
