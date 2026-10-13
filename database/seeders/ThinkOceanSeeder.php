<?php

namespace Database\Seeders;

use App\Models\ThinkOceanPost;
use App\Models\ThinkOceanProject;
use App\Models\ThinkOceanSetting;
use Illuminate\Database\Seeder;

class ThinkOceanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (config('think_ocean.defaults') as $key => $value) {
            ThinkOceanSetting::query()->firstOrCreate(['key' => $key], ['value' => $value]);
        }

        ThinkOceanProject::query()->firstOrCreate(
            ['slug' => 'coastal-community-action'],
            [
                'title' => 'Coastal community action',
                'location' => 'United Kingdom and partner communities',
                'impact_metric' => 'Community-led conservation',
                'summary' => 'Practical local action that protects coastlines, improves awareness and brings people together around ocean health.',
                'content' => 'This profile can be expanded in the CMS as verified activities and outcomes are recorded.',
                'is_published' => true,
                'sort_order' => 1,
            ],
        );

        ThinkOceanPost::query()->firstOrCreate(
            ['slug' => 'why-ocean-health-matters'],
            [
                'title' => 'Why ocean health matters to everyone',
                'excerpt' => 'Healthy oceans support climate resilience, livelihoods, biodiversity and community wellbeing.',
                'content' => 'Use the Think Ocean CMS to publish the full article and future updates.',
                'is_published' => true,
                'published_at' => now(),
            ],
        );
    }
}
