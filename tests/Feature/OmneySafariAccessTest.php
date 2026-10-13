<?php

namespace Tests\Feature;

use App\Http\Controllers\OmneySafariController;
use App\Models\User;
use Tests\TestCase;

class OmneySafariAccessTest extends TestCase
{
    public function test_apache_configuration_redirects_requests_to_https(): void
    {
        $configuration = file_get_contents(public_path('.htaccess'));

        $this->assertStringContainsString('RewriteCond %{HTTPS} !=on', $configuration);
        $this->assertStringContainsString('RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]', $configuration);
    }

    public function test_domain_homepage_displays_omney_safari(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertViewIs('omney-safari')
            ->assertSee('Sign in')
            ->assertSee('Register');
    }

    public function test_legacy_safari_url_redirects_to_the_main_domain_homepage(): void
    {
        $this->get('/omney-safari')
            ->assertRedirect('/');
    }

    public function test_guest_can_view_safari_page(): void
    {
        $view = (new OmneySafariController)->home();

        $this->assertSame('omney-safari', $view->name());
    }

    public function test_guest_can_view_booking_page(): void
    {
        $this->get('/omney-safari/bookings')
            ->assertOk()
            ->assertViewIs('omney-safari.bookings');
    }

    public function test_any_authenticated_user_can_view_safari_page(): void
    {
        $user = User::factory()->make([
            'email' => 'another@example.com',
            'subscription_type' => 'daily',
        ]);

        $this->actingAs($user);

        $view = (new OmneySafariController)->home();

        $this->assertSame('omney-safari', $view->name());
    }

    public function test_safari_navigation_contains_the_requested_menu_items(): void
    {
        $navigation = file_get_contents(resource_path('views/omney-safari.blade.php'));

        $this->assertStringContainsString("route('home')", $navigation);
        $this->assertStringContainsString("route('login')", $navigation);
        $this->assertStringContainsString("route('register')", $navigation);
        $this->assertStringContainsString("route('omney-safari.bookings')", $navigation);
        $this->assertStringContainsString("route('omney-safari.faq')", $navigation);
        $this->assertStringContainsString("route('omney-safari.scr')", $navigation);
        $this->assertStringContainsString("route('omney-safari.about')", $navigation);
    }

    public function test_guest_can_open_each_separate_safari_view(): void
    {
        $controller = new OmneySafariController;

        $this->assertSame('omney-safari.faq', $controller->faq()->name());
        $this->assertSame('omney-safari.scr', $controller->scr()->name());
        $this->assertSame('omney-safari.about', $controller->about()->name());
    }

    public function test_guest_can_open_scr_route(): void
    {
        $view = (new OmneySafariController)->scr();
        $content = file_get_contents(resource_path('views/omney-safari/scr.blade.php'));

        $this->assertSame('omney-safari.scr', $view->name());
        $this->assertStringContainsString('Social Corporate Responsibility', $content);
        $this->assertStringContainsString('OUR RESPONSIBILITY IN ACTION', $content);
        $this->assertSame('/omney-safari/scr', route('omney-safari.scr', absolute: false));
    }

    public function test_safari_homepage_has_professional_planning_and_mobile_navigation(): void
    {
        $homepage = file_get_contents(resource_path('views/omney-safari.blade.php'));

        $this->assertStringContainsString('From first idea to unforgettable journey', $homepage);
        $this->assertStringContainsString('class="planning-grid"', $homepage);
        $this->assertStringContainsString('class="mobile-menu"', $homepage);
        $this->assertStringContainsString('aria-label="Mobile navigation"', $homepage);
        $this->assertStringContainsString('Start planning', $homepage);
    }
}
