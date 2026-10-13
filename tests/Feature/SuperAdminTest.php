<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SuperAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_password_is_hashed_and_flag_is_boolean(): void
    {
        $user = User::query()->create([
            'name' => 'Super Admin',
            'email' => 'ogeof49@gmail.com',
            'password' => 'admin@1234',
            'is_super_admin' => true,
        ]);

        $this->assertTrue($user->isSuperAdmin());
        $this->assertNotSame('admin@1234', $user->password);
        $this->assertTrue(Hash::check('admin@1234', $user->password));
    }

    public function test_super_admin_bypasses_all_gate_checks(): void
    {
        Gate::define('manage-site', fn (User $user): bool => false);
        $superAdmin = User::factory()->create(['is_super_admin' => true]);
        $regularUser = User::factory()->create(['is_super_admin' => false]);

        $this->assertTrue(Gate::forUser($superAdmin)->allows('manage-site'));
        $this->assertFalse(Gate::forUser($regularUser)->allows('manage-site'));
    }
}
