<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateThinkOceanSuperAdminCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_creates_requested_super_admin(): void
    {
        $this->artisan('think-ocean:create-super-admin', [
            'email' => 'ogeof49@gmail.com',
            '--password' => 'A-strong-temporary-password-2026',
        ])->assertSuccessful();

        $user = User::query()->where('email', 'ogeof49@gmail.com')->firstOrFail();

        $this->assertTrue($user->is_super_admin);
        $this->assertTrue(Hash::check('A-strong-temporary-password-2026', $user->password));
    }
}
