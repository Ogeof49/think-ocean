<?php

namespace Tests\Feature;

use App\Models\ThinkOceanProject;
use App\Models\ThinkOceanSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThinkOceanCmsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('think-ocean.admin.index'))->assertRedirect(route('login'));
    }

    public function test_regular_user_cannot_access_cms(): void
    {
        $this->actingAs(User::factory()->create(['is_super_admin' => false]))
            ->get(route('think-ocean.admin.index'))
            ->assertForbidden();
    }

    public function test_super_admin_can_update_settings_and_publish_a_project(): void
    {
        $admin = User::factory()->create(['email' => 'ogeof49@gmail.com', 'is_super_admin' => true]);

        $this->actingAs($admin)->put(route('think-ocean.admin.settings.update'), [
            'hero_title' => 'A stronger ocean future',
            'hero_text' => 'Updated introduction for the public page.',
            'mission_title' => 'A shared mission',
            'mission_text' => 'Updated mission text.',
            'registration_number' => '',
            'registered_office' => '',
            'volunteer_email' => 'thinkocean@gmail.com',
            'partner_email' => 'thinkocean@gmail.com',
        ])->assertRedirect();

        $this->assertSame('A stronger ocean future', ThinkOceanSetting::query()->where('key', 'hero_title')->value('value'));

        $this->actingAs($admin)->post(route('think-ocean.admin.projects.store'), [
            'title' => 'Blue schools',
            'slug' => 'blue-schools',
            'summary' => 'Ocean education for young people.',
            'is_published' => '1',
            'sort_order' => 1,
        ])->assertRedirect();

        $this->assertTrue(ThinkOceanProject::query()->where('slug', 'blue-schools')->firstOrFail()->is_published);
    }
}
