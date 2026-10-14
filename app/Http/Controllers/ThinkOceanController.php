<?php

namespace App\Http\Controllers;

use App\Models\ThinkOceanMessage;
use App\Models\ThinkOceanPost;
use App\Models\ThinkOceanProject;
use App\Models\ThinkOceanSetting;
use App\Models\ThinkOceanSubscriber;
use App\Support\LiveStream;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ThinkOceanController extends Controller
{
    public function index(): View
    {
        return view('think-ocean.home', $this->shared());
    }

    public function page(Request $request, string $page): View
    {
        $pages = [
            'about' => ['title' => 'About Think Ocean', 'subtitle' => 'Our story, our mission, our impact.', 'image' => 'about-hero'],
            'what-we-do' => ['title' => 'What We Do', 'subtitle' => 'Tackling ocean challenges through action, innovation and partnership.', 'image' => 'what-we-do-hero'],
            'projects' => ['title' => 'Our Projects', 'subtitle' => 'Real solutions. Lasting impact.', 'image' => 'projects-hero'],
            'get-involved' => ['title' => 'Get Involved', 'subtitle' => 'Be part of the change.', 'image' => 'get-involved-hero'],
            'news' => ['title' => 'News & Insights', 'subtitle' => 'Stories, updates and ideas for a healthier ocean.', 'image' => 'news-hero'],
            'contact' => ['title' => 'Contact Us', 'subtitle' => 'We’d love to hear from you.', 'image' => 'contact-hero'],
            'donate' => ['title' => 'Support Our Work', 'subtitle' => 'Help us create lasting change for the ocean.', 'image' => 'get-involved-banner'],
        ];

        abort_unless(array_key_exists($page, $pages), 404);

        $data = [...$this->shared(), ...$pages[$page], 'page' => $page];

        if ($page === 'projects') {
            $data['projects'] = ThinkOceanProject::query()->published()->orderBy('sort_order')->orderByDesc('id')->get();
        }

        if ($page === 'news') {
            $category = in_array($request->query('category'), ThinkOceanPost::CATEGORIES, true) ? $request->query('category') : null;
            $search = trim((string) $request->query('q'));

            $data['category'] = $category;
            $data['search'] = $search;
            $data['posts'] = ThinkOceanPost::query()->published()
                ->when($category, fn ($query) => $query->where('category', $category))
                ->when($search !== '', fn ($query) => $query->where(fn ($query) => $query
                    ->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")))
                ->latest('published_at')
                ->paginate(9)
                ->withQueryString();
        }

        return view("think-ocean.pages.{$page}", $data);
    }

    public function live(): View
    {
        $shared = $this->shared();
        $stream = $shared['settings']['live_enabled'] === '1' ? LiveStream::fromUrl($shared['settings']['live_stream_url']) : null;

        return view('think-ocean.live', [...$shared, 'stream' => $stream]);
    }

    public function project(ThinkOceanProject $project): View
    {
        abort_unless($project->is_published, 404);

        return view('think-ocean.detail', [
            ...$this->shared(),
            'type' => 'Project',
            'title' => $project->title,
            'summary' => $project->summary,
            'content' => $project->content,
            'imagePath' => $project->image_path,
            'meta' => collect([$project->location, $project->impact_metric])->filter()->implode(' · '),
        ]);
    }

    public function post(ThinkOceanPost $post): View
    {
        abort_unless($post->is_published && $post->published_at?->isPast(), 404);

        return view('think-ocean.detail', [
            ...$this->shared(),
            'type' => $post->category,
            'title' => $post->title,
            'summary' => $post->excerpt,
            'content' => $post->content,
            'imagePath' => $post->image_path,
            'meta' => $post->published_at?->format('j F Y'),
        ]);
    }

    public function privacy(): View
    {
        return view('think-ocean.legal', [...$this->shared(), 'title' => 'Privacy Policy']);
    }

    public function accessibility(): View
    {
        return view('think-ocean.legal', [...$this->shared(), 'title' => 'Accessibility Statement']);
    }

    public function sendMessage(Request $request): RedirectResponse
    {
        // Bots fill every field, so a value in the hidden "website" field is silently discarded.
        if ($request->filled('website')) {
            return back()->with('status', 'Thank you — your message has been sent.');
        }

        ThinkOceanMessage::query()->create($request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]));

        return back()->with('status', 'Thank you — your message has been sent. We aim to reply within two business days.');
    }

    public function subscribe(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('subscribe', ['email' => ['required', 'email', 'max:255']]);

        ThinkOceanSubscriber::query()->firstOrCreate(['email' => strtolower($validated['email'])]);

        return back()->with('subscribed', 'Thanks for subscribing — you’re on the list.');
    }

    /** @return array<string, mixed> */
    private function shared(): array
    {
        return [
            'settings' => array_merge(config('think_ocean.defaults'), ThinkOceanSetting::query()->pluck('value', 'key')->filter(fn ($value) => $value !== null)->all()),
            'contactEmail' => config('think_ocean.email'),
        ];
    }
}
