<?php

namespace Tests\Feature;

use App\Models\ThinkOceanMessage;
use App\Models\ThinkOceanPost;
use App\Models\ThinkOceanProject;
use App\Models\ThinkOceanSetting;
use App\Models\ThinkOceanSubscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThinkOceanPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_ocean_page_is_public_and_contains_the_designed_sections(): void
    {
        $this->get(route('think-ocean'))
            ->assertOk()
            ->assertSee('Healthier Oceans. Brighter Futures.')
            ->assertSee('Conserve Marine Ecosystems')
            ->assertSee('Together for a sustainable ocean')
            ->assertSee('The ocean connects us all.')
            ->assertSee('rel="canonical"', false);
    }

    public function test_navigation_links_point_to_standalone_pages(): void
    {
        $response = $this->get(route('think-ocean'));

        foreach (['about', 'what-we-do', 'projects', 'get-involved', 'news', 'contact', 'donate'] as $page) {
            $response->assertSee(route('think-ocean.pages.show', $page), false);
        }
    }

    public function test_standalone_pages_are_public_and_unknown_pages_are_not_found(): void
    {
        foreach (['about', 'what-we-do', 'projects', 'get-involved', 'news', 'contact', 'donate'] as $page) {
            $this->get(route('think-ocean.pages.show', $page))->assertOk();
        }

        $this->get('/think-ocean/pages/not-a-page')->assertNotFound();
    }

    public function test_only_published_projects_and_posts_are_visible(): void
    {
        ThinkOceanProject::query()->create(['title' => 'Visible project', 'slug' => 'visible-project', 'summary' => 'Public project', 'is_published' => true]);
        ThinkOceanProject::query()->create(['title' => 'Hidden project', 'slug' => 'hidden-project', 'summary' => 'Draft project', 'is_published' => false]);
        ThinkOceanPost::query()->create(['title' => 'Visible article', 'slug' => 'visible-article', 'excerpt' => 'Public article', 'is_published' => true, 'published_at' => now()]);
        ThinkOceanPost::query()->create(['title' => 'Hidden article', 'slug' => 'hidden-article', 'excerpt' => 'Draft article', 'is_published' => false]);

        $this->get(route('think-ocean.pages.show', 'projects'))
            ->assertSee('Visible project')
            ->assertDontSee('Hidden project');
        $this->get(route('think-ocean.pages.show', 'news'))
            ->assertSee('Visible article')
            ->assertDontSee('Hidden article');
    }

    public function test_published_project_and_article_cards_link_to_public_detail_pages(): void
    {
        $project = ThinkOceanProject::query()->create([
            'title' => 'Coastal restoration',
            'slug' => 'coastal-restoration',
            'summary' => 'Restoring a community coastline.',
            'content' => 'The full project story.',
            'is_published' => true,
        ]);
        $post = ThinkOceanPost::query()->create([
            'title' => 'Ocean action update',
            'slug' => 'ocean-action-update',
            'excerpt' => 'An update from the field.',
            'content' => 'The full article.',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->get(route('think-ocean.pages.show', 'projects'))->assertSee(route('think-ocean.projects.show', $project), false);
        $this->get(route('think-ocean.pages.show', 'news'))->assertSee(route('think-ocean.posts.show', $post), false);

        $this->get(route('think-ocean.projects.show', $project))
            ->assertOk()
            ->assertSee('The full project story.');
        $this->get(route('think-ocean.posts.show', $post))
            ->assertOk()
            ->assertSee('The full article.');
    }

    public function test_unpublished_project_and_article_detail_pages_are_not_public(): void
    {
        $project = ThinkOceanProject::query()->create([
            'title' => 'Draft project',
            'slug' => 'draft-project',
            'summary' => 'Not public.',
            'is_published' => false,
        ]);
        $post = ThinkOceanPost::query()->create([
            'title' => 'Draft article',
            'slug' => 'draft-article',
            'excerpt' => 'Not public.',
            'is_published' => false,
        ]);

        $this->get(route('think-ocean.projects.show', $project))->assertNotFound();
        $this->get(route('think-ocean.posts.show', $post))->assertNotFound();
    }

    public function test_news_can_be_filtered_by_category_and_searched(): void
    {
        ThinkOceanPost::query()->create(['title' => 'Reef event day', 'slug' => 'reef-event-day', 'category' => 'Events', 'excerpt' => 'Join us.', 'is_published' => true, 'published_at' => now()]);
        ThinkOceanPost::query()->create(['title' => 'Plastic insight', 'slug' => 'plastic-insight', 'category' => 'Insights', 'excerpt' => 'Less plastic.', 'is_published' => true, 'published_at' => now()]);

        $this->get(route('think-ocean.pages.show', ['news', 'category' => 'Events']))
            ->assertSee('Reef event day')
            ->assertDontSee('Plastic insight');
        $this->get(route('think-ocean.pages.show', ['news', 'q' => 'plastic']))
            ->assertSee('Plastic insight')
            ->assertDontSee('Reef event day');
    }

    public function test_contact_form_stores_the_message(): void
    {
        $this->from(route('think-ocean.pages.show', 'contact'))
            ->post(route('think-ocean.contact.store'), ['name' => 'Ada', 'email' => 'ada@example.com', 'message' => 'Hello ocean.'])
            ->assertRedirect(route('think-ocean.pages.show', 'contact'))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('think_ocean_messages', ['email' => 'ada@example.com', 'message' => 'Hello ocean.']);
    }

    public function test_contact_form_validates_and_ignores_bots(): void
    {
        $this->post(route('think-ocean.contact.store'), ['name' => '', 'email' => 'not-an-email', 'message' => ''])
            ->assertSessionHasErrors(['name', 'email', 'message']);

        $this->post(route('think-ocean.contact.store'), ['name' => 'Bot', 'email' => 'bot@example.com', 'message' => 'Spam', 'website' => 'http://spam.test']);

        $this->assertSame(0, ThinkOceanMessage::query()->count());
    }

    public function test_live_tv_page_plays_the_think_ocean_stream_by_default(): void
    {
        $this->get(route('think-ocean'))->assertSee(route('think-ocean.live'), false);

        $this->get(route('think-ocean.live'))
            ->assertOk()
            ->assertSee('Live now')
            ->assertSee('data-hls="https://rst5.saiuzwebnetwork.it:3635/hybrid/play.m3u8"', false)
            ->assertSee('hls.min.js', false)
            ->assertSee('Live stream temporarily unavailable')
            ->assertSee('Retry live stream')
            ->assertSee('fragLoadingTimeOut: 15000', false);
    }

    public function test_live_tv_page_shows_off_air_screen_when_switched_off(): void
    {
        ThinkOceanSetting::query()->create(['key' => 'live_enabled', 'value' => '0']);

        $this->get(route('think-ocean.live'))
            ->assertOk()
            ->assertSee('Think Ocean TV')
            ->assertSee('Off air')
            ->assertSee('We’re off air right now', false)
            ->assertDontSee('<iframe', false);
    }

    public function test_live_tv_page_embeds_the_stream_when_on_air(): void
    {
        ThinkOceanSetting::query()->create(['key' => 'live_enabled', 'value' => '1']);
        ThinkOceanSetting::query()->create(['key' => 'live_stream_url', 'value' => 'https://www.youtube.com/live/abc123']);

        $this->get(route('think-ocean.live'))
            ->assertOk()
            ->assertSee('Live now')
            ->assertSee('https://www.youtube-nocookie.com/embed/abc123?', false);
    }

    public function test_newsletter_subscription_is_stored_once(): void
    {
        $this->post(route('think-ocean.subscribe'), ['email' => 'Reader@Example.com'])->assertSessionHas('subscribed');
        $this->post(route('think-ocean.subscribe'), ['email' => 'reader@example.com'])->assertSessionHas('subscribed');

        $this->assertSame(['reader@example.com'], ThinkOceanSubscriber::query()->pluck('email')->all());
    }
}
