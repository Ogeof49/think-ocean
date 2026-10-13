<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_page_has_the_branded_account_experience(): void
    {
        $this->get('/register')
            ->assertOk()
            ->assertSee('More than a safari. Your Uganda story.')
            ->assertSee('Create my account')
            ->assertSee('Track safari bookings and travel dates')
            ->assertSee('data-password-toggle="password"', escape: false)
            ->assertSee(route('login'));
    }

    public function test_guest_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Safari Traveller',
            'email' => 'traveller@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('omney-safari.bookings'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'traveller@example.com']);
    }

    public function test_user_can_sign_in_and_sign_out(): void
    {
        $user = User::factory()->create(['password' => 'password123']);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ])->assertRedirect(route('omney-safari.bookings'));

        $this->assertAuthenticatedAs($user);

        $this->post('/logout')->assertRedirect(route('home'));
        $this->assertGuest();
    }

    public function test_invalid_credentials_are_rejected(): void
    {
        User::factory()->create(['email' => 'traveller@example.com']);

        $response = $this->post('/login', [
            'email' => 'traveller@example.com',
            'password' => 'incorrect-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_think_ocean_has_a_separate_branded_login(): void
    {
        $this->get(route('think-ocean.login'))
            ->assertOk()
            ->assertSee('THINK OCEAN')
            ->assertSee('CMS sign in')
            ->assertSee(route('think-ocean.login.store'), false);
    }

    public function test_think_ocean_login_redirects_to_its_cms(): void
    {
        $user = User::factory()->create(['password' => 'password123']);

        $this->post(route('think-ocean.login.store'), [
            'email' => $user->email,
            'password' => 'password123',
        ])->assertRedirect(route('think-ocean.admin.index'));

        $this->assertAuthenticatedAs($user);
    }
}
