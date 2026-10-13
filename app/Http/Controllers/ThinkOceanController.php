<?php

namespace App\Http\Controllers;

use App\Models\ThinkOceanPost;
use App\Models\ThinkOceanProject;
use App\Models\ThinkOceanSetting;
use Illuminate\View\View;

class ThinkOceanController extends Controller
{
    public function index(): View
    {
        $settings = array_merge(
            config('think_ocean.defaults'),
            ThinkOceanSetting::query()->pluck('value', 'key')->all(),
        );

        return view('think-ocean', [
            'settings' => $settings,
            'projects' => ThinkOceanProject::query()->published()->orderBy('sort_order')->orderByDesc('id')->get(),
            'posts' => ThinkOceanPost::query()->published()->latest('published_at')->limit(3)->get(),
            'contactEmail' => config('think_ocean.email'),
        ]);
    }

    public function page(string $page): View
    {
        $pages = [
            'about' => ['title' => 'About Think Ocean', 'eyebrow' => 'Who we are'],
            'what-we-do' => ['title' => 'What We Do', 'eyebrow' => 'Our mission'],
            'projects' => ['title' => 'Our Projects', 'eyebrow' => 'Practical action'],
            'get-involved' => ['title' => 'Get Involved', 'eyebrow' => 'Join the movement'],
            'news' => ['title' => 'News & Insights', 'eyebrow' => 'From the ocean'],
            'contact' => ['title' => 'Contact Think Ocean', 'eyebrow' => 'Start a conversation'],
            'donate' => ['title' => 'Support Our Work', 'eyebrow' => 'Create lasting impact'],
        ];

        abort_unless(array_key_exists($page, $pages), 404);

        return view('think-ocean-page', [
            ...$pages[$page],
            'page' => $page,
            'settings' => array_merge(config('think_ocean.defaults'), ThinkOceanSetting::query()->pluck('value', 'key')->all()),
            'projects' => ThinkOceanProject::query()->published()->orderBy('sort_order')->orderByDesc('id')->get(),
            'posts' => ThinkOceanPost::query()->published()->latest('published_at')->get(),
            'contactEmail' => config('think_ocean.email'),
        ]);
    }

    public function privacy(): View
    {
        return view('think-ocean-legal', ['title' => 'Privacy Policy', 'contactEmail' => config('think_ocean.email')]);
    }

    public function project(ThinkOceanProject $project): View
    {
        abort_unless($project->is_published, 404);

        return view('think-ocean-detail', [
            'type' => 'Project',
            'title' => $project->title,
            'summary' => $project->summary,
            'content' => $project->content,
            'imagePath' => $project->image_path,
            'meta' => collect([$project->location, $project->impact_metric])->filter()->implode(' · '),
            'contactEmail' => config('think_ocean.email'),
        ]);
    }

    public function post(ThinkOceanPost $post): View
    {
        abort_unless($post->is_published && $post->published_at?->isPast(), 404);

        return view('think-ocean-detail', [
            'type' => 'News & insight',
            'title' => $post->title,
            'summary' => $post->excerpt,
            'content' => $post->content,
            'imagePath' => $post->image_path,
            'meta' => $post->published_at?->format('j F Y'),
            'contactEmail' => config('think_ocean.email'),
        ]);
    }

    public function accessibility(): View
    {
        return view('think-ocean-legal', ['title' => 'Accessibility Statement', 'contactEmail' => config('think_ocean.email')]);
    }
}
