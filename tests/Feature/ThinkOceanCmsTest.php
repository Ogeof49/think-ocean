<?php

namespace Tests\Feature;

use App\Models\ThinkOceanMessage;
use App\Models\ThinkOceanPartner;
use App\Models\ThinkOceanPost;
use App\Models\ThinkOceanProject;
use App\Models\ThinkOceanSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

    public function test_super_admin_can_set_contact_details_and_social_links(): void
    {
        $admin = User::factory()->create(['email' => 'ogeof49@gmail.com', 'is_super_admin' => true]);

        $this->actingAs($admin)->put(route('think-ocean.admin.settings.update'), [
            ...config('think_ocean.defaults'),
            'contact_phone' => '+44 20 1234 5678',
            'instagram_url' => 'https://instagram.com/thinkocean',
        ])->assertSessionHasNoErrors();

        $this->get(route('think-ocean.pages.show', 'contact'))
            ->assertSee('+44 20 1234 5678')
            ->assertSee('https://instagram.com/thinkocean', false);
    }

    public function test_super_admin_can_put_live_tv_on_air_and_take_it_off(): void
    {
        $admin = User::factory()->create(['email' => 'ogeof49@gmail.com', 'is_super_admin' => true]);

        $this->actingAs($admin)->put(route('think-ocean.admin.live.update'), ['live_title' => 'Think Ocean TV', 'live_enabled' => '1'])
            ->assertSessionHasErrors('live_stream_url');

        $this->actingAs($admin)->put(route('think-ocean.admin.live.update'), [
            'live_title' => 'Ocean Live',
            'live_stream_url' => 'https://youtu.be/abc123',
            'live_enabled' => '1',
        ])->assertSessionHasNoErrors();

        $this->get(route('think-ocean.live'))->assertSee('Ocean Live')->assertSee('youtube-nocookie.com/embed/abc123', false);

        $this->actingAs($admin)->put(route('think-ocean.admin.live.update'), [
            'live_title' => 'Ocean Live',
            'live_stream_url' => 'https://youtu.be/abc123',
        ])->assertSessionHasNoErrors();

        $this->assertSame('0', ThinkOceanSetting::query()->where('key', 'live_enabled')->value('value'));
        $this->get(route('think-ocean.live'))->assertSee('Off air')->assertDontSee('<iframe', false);
    }

    public function test_super_admin_can_publish_a_categorised_article_and_manage_messages(): void
    {
        $admin = User::factory()->create(['email' => 'ogeof49@gmail.com', 'is_super_admin' => true]);

        $this->actingAs($admin)->post(route('think-ocean.admin.posts.store'), [
            'title' => 'Ocean day event',
            'slug' => 'ocean-day-event',
            'category' => 'Events',
            'excerpt' => 'Join us on the beach.',
            'is_published' => '1',
            'published_at' => now()->subMinute()->format('Y-m-d\TH:i'),
        ])->assertSessionHasNoErrors();

        $this->assertSame('Events', ThinkOceanPost::query()->where('slug', 'ocean-day-event')->value('category'));

        $message = ThinkOceanMessage::query()->create(['name' => 'Ada', 'email' => 'ada@example.com', 'message' => 'Hello']);

        $this->actingAs($admin)->get(route('think-ocean.admin.index'))->assertSee('ada@example.com');
        $this->actingAs($admin)->patch(route('think-ocean.admin.messages.read', $message))->assertRedirect();
        $this->assertNotNull($message->fresh()->read_at);
        $this->actingAs($admin)->delete(route('think-ocean.admin.messages.destroy', $message))->assertRedirect();
        $this->assertModelMissing($message);
    }

    public function test_super_admin_can_manage_partner_logos(): void
    {
        Storage::fake('think_ocean');
        $admin = User::factory()->create(['email' => 'ogeof49@gmail.com', 'is_super_admin' => true]);

        $this->actingAs($admin)->post(route('think-ocean.admin.partners.store'), [
            'name' => 'Blue Reef Trust',
            'website_url' => 'https://bluereef.example.org',
            'logo' => UploadedFile::fake()->image('logo.png', 300, 120),
            'is_published' => '1',
        ])->assertSessionHasNoErrors();

        $partner = ThinkOceanPartner::query()->where('name', 'Blue Reef Trust')->firstOrFail();
        $firstLogo = $partner->logo_path;
        Storage::disk('think_ocean')->assertExists($firstLogo);

        $this->get(route('think-ocean'))
            ->assertSee('Our Partners')
            ->assertSee('https://bluereef.example.org', false)
            ->assertSee('uploads/think-ocean/'.$firstLogo, false);

        $this->actingAs($admin)->put(route('think-ocean.admin.partners.update', $partner), [
            'name' => 'Blue Reef Trust',
            'logo' => UploadedFile::fake()->image('new-logo.png'),
            'is_published' => '1',
        ])->assertSessionHasNoErrors();

        Storage::disk('think_ocean')->assertMissing($firstLogo);
        Storage::disk('think_ocean')->assertExists($partner->fresh()->logo_path);

        $this->actingAs($admin)->put(route('think-ocean.admin.partners.update', $partner), [
            'name' => 'Blue Reef Trust',
            'remove_logo' => '1',
        ])->assertSessionHasNoErrors();

        $this->assertNull($partner->fresh()->logo_path);
        $this->get(route('think-ocean.pages.show', 'about'))->assertDontSee('Blue Reef Trust');

        $this->actingAs($admin)->delete(route('think-ocean.admin.partners.destroy', $partner))->assertRedirect();
        $this->assertModelMissing($partner);
    }

    public function test_partner_logos_must_be_safe_images(): void
    {
        Storage::fake('think_ocean');
        $admin = User::factory()->create(['email' => 'ogeof49@gmail.com', 'is_super_admin' => true]);

        $this->actingAs($admin)->post(route('think-ocean.admin.partners.store'), [
            'name' => 'Svg Partner',
            'logo' => UploadedFile::fake()->createWithContent('logo.svg', '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>'),
        ])->assertSessionHasErrors('logo');

        $this->assertSame(0, ThinkOceanPartner::query()->count());
    }
}
