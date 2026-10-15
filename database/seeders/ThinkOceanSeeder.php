<?php

namespace Database\Seeders;

use App\Models\ThinkOceanPost;
use App\Models\ThinkOceanProject;
use App\Models\ThinkOceanSetting;
use Illuminate\Database\Seeder;

class ThinkOceanSeeder extends Seeder
{
    /**
     * Seed starter content matching the website design. Everything here can be edited or removed in the CMS.
     */
    public function run(): void
    {
        foreach (config('think_ocean.defaults') as $key => $value) {
            ThinkOceanSetting::query()->firstOrCreate(['key' => $key], ['value' => $value]);
        }

        $projects = [
            ['reef-restoration-initiative', 'Reef Restoration Initiative', 'Supporting coral reef recovery and biodiversity in coastal regions.', 'project-reef'],
            ['sustainable-fisheries-program', 'Sustainable Fisheries Program', 'Promoting responsible fishing and stronger coastal economies.', 'project-fisheries'],
            ['blue-carbon-project', 'Blue Carbon Project', 'Restoring mangroves and seagrass for climate and community resilience.', 'project-blue-carbon'],
            ['ocean-education-programme', 'Ocean Education Programme', 'Inspiring the next generation of ocean stewards.', 'project-education'],
        ];

        foreach ($projects as $order => [$slug, $title, $summary, $image]) {
            ThinkOceanProject::query()->firstOrCreate(['slug' => $slug], [
                'title' => $title,
                'summary' => $summary,
                'content' => $summary."\n\nThis profile can be expanded in the CMS as verified activities and outcomes are recorded.",
                'image_path' => "images/think-ocean/{$image}.jpg",
                'is_published' => true,
                'sort_order' => $order + 1,
            ]);
        }

        $posts = [
            ['new-partnership-to-restore-coastal-habitats', 'News', 'New partnership to restore coastal habitats', 'How working with local partners helps bring damaged coastal habitats back to health.', 'news-turtle', 6],
            ['how-blue-carbon-helps-fight-climate-change', 'Insights', 'How blue carbon helps fight climate change', 'Mangroves, seagrass and salt marshes store carbon and protect coastlines. Here is why they matter.', 'news-cleanup', 5],
            ['a-day-in-the-life-of-a-marine-biologist', 'Stories', 'A day in the life of a marine biologist', 'From early-morning dives to data analysis: what ocean science looks like up close.', 'news-fish', 4],
            ['world-ocean-day-join-us', 'Events', 'World Ocean Day – Join us!', 'Celebrate World Ocean Day with community activities that anyone can take part in.', 'news-boat', 3],
            ['community-led-mangrove-restoration-begins', 'News', 'Community-led mangrove restoration begins', 'Local communities are leading the way in restoring mangrove forests along the coast.', 'news-mangrove', 2],
            ['what-you-can-do-to-reduce-ocean-plastic', 'Insights', 'What you can do to reduce ocean plastic', 'Simple everyday changes that add up to less plastic reaching the ocean.', 'news-diver', 1],
        ];

        foreach ($posts as [$slug, $category, $title, $excerpt, $image, $daysAgo]) {
            ThinkOceanPost::query()->firstOrCreate(['slug' => $slug], [
                'category' => $category,
                'title' => $title,
                'excerpt' => $excerpt,
                'content' => $excerpt."\n\nUse the Think Ocean CMS to publish the full article.",
                'image_path' => "images/think-ocean/{$image}.jpg",
                'is_published' => true,
                'published_at' => now()->subDays($daysAgo),
            ]);
        }
    }
}
