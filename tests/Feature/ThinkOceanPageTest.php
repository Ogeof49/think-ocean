<?php

namespace Tests\Feature;

use App\Models\ThinkOceanPost;
use App\Models\ThinkOceanProject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThinkOceanPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_ocean_page_is_public_and_contains_the_improved_sections(): void
    {
        $this->get(route('think-ocean'))
            ->assertOk()
            ->assertSee('Healthier Oceans. Brighter Futures.')
            ->assertSee('Conserve marine ecosystems')
            ->assertSee('Together for a sustainable ocean')
            ->assertSee('The ocean connects us all.')
            ->assertSee('id="support"', false)
            ->assertSee('thinkocean@gmail.com')
            ->assertSee('rel="canonical"', false);
    }

    public function test_navigation_links_have_section_targets(): void
    {
        $response = $this->get(route('think-ocean'));

        foreach (['home', 'about', 'what-we-do', 'projects', 'get-involved', 'news', 'contact'] as $section) {
            $response->assertSee('id="'.$section.'"', false)->assertSee('href="#'.$section.'"', false);
        }
    }

    public function test_only_published_projects_and_posts_are_visible(): void
    {
        ThinkOceanProject::query()->create(['title' => 'Visible project', 'slug' => 'visible-project', 'summary' => 'Public project', 'is_published' => true]);
        ThinkOceanProject::query()->create(['title' => 'Hidden project', 'slug' => 'hidden-project', 'summary' => 'Draft project', 'is_published' => false]);
        ThinkOceanPost::query()->create(['title' => 'Visible article', 'slug' => 'visible-article', 'excerpt' => 'Public article', 'is_published' => true, 'published_at' => now()]);
        ThinkOceanPost::query()->create(['title' => 'Hidden article', 'slug' => 'hidden-article', 'excerpt' => 'Draft article', 'is_published' => false]);

        $this->get(route('think-ocean'))
            ->assertSee('Visible project')
            ->assertDontSee('Hidden project')
            ->assertSee('Visible article')
            ->assertDontSee('Hidden article');
    }
}
