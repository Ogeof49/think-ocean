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

        $this->get(route('think-ocean'))
            ->assertSee(route('think-ocean.projects.show', $project), false)
            ->assertSee(route('think-ocean.posts.show', $post), false);

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
}
