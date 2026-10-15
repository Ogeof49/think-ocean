<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreThinkOceanPostRequest;
use App\Http\Requests\StoreThinkOceanProjectRequest;
use App\Http\Requests\UpdateThinkOceanLiveRequest;
use App\Http\Requests\UpdateThinkOceanSettingsRequest;
use App\Models\ThinkOceanMessage;
use App\Models\ThinkOceanPost;
use App\Models\ThinkOceanProject;
use App\Models\ThinkOceanSetting;
use App\Models\ThinkOceanSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ThinkOceanCmsController extends Controller
{
    public function index(): View
    {
        return view('think-ocean-admin.index', [
            'settings' => array_merge(config('think_ocean.defaults'), ThinkOceanSetting::query()->pluck('value', 'key')->all()),
            'projects' => ThinkOceanProject::query()->orderBy('sort_order')->orderByDesc('id')->get(),
            'posts' => ThinkOceanPost::query()->latest('published_at')->latest('id')->get(),
            'messages' => ThinkOceanMessage::query()->latest()->limit(100)->get(),
            'subscribers' => ThinkOceanSubscriber::query()->latest()->get(),
            'contactEmail' => config('think_ocean.email'),
        ]);
    }

    public function markMessageRead(ThinkOceanMessage $message): RedirectResponse
    {
        $message->update(['read_at' => $message->read_at ? null : now()]);

        return back()->with('status', 'Message updated.');
    }

    public function destroyMessage(ThinkOceanMessage $message): RedirectResponse
    {
        $message->delete();

        return back()->with('status', 'Message deleted.');
    }

    public function destroySubscriber(ThinkOceanSubscriber $subscriber): RedirectResponse
    {
        $subscriber->delete();

        return back()->with('status', 'Subscriber removed.');
    }

    public function updateSettings(UpdateThinkOceanSettingsRequest $request): RedirectResponse
    {
        foreach ($request->validated() as $key => $value) {
            ThinkOceanSetting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return back()->with('status', 'Think Ocean page settings updated.');
    }

    public function updateLive(UpdateThinkOceanLiveRequest $request): RedirectResponse
    {
        $values = [...$request->safe()->except('live_enabled'), 'live_enabled' => $request->boolean('live_enabled') ? '1' : '0'];

        foreach ($values as $key => $value) {
            ThinkOceanSetting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return back()->with('status', $values['live_enabled'] === '1' ? 'Live TV is now on air.' : 'Live TV settings saved (off air).');
    }

    public function storeProject(StoreThinkOceanProjectRequest $request): RedirectResponse
    {
        ThinkOceanProject::query()->create($this->projectData($request));

        return back()->with('status', 'Project created.');
    }

    public function updateProject(StoreThinkOceanProjectRequest $request, ThinkOceanProject $project): RedirectResponse
    {
        $project->update($this->projectData($request));

        return back()->with('status', 'Project updated.');
    }

    public function destroyProject(ThinkOceanProject $project): RedirectResponse
    {
        $project->delete();

        return back()->with('status', 'Project deleted.');
    }

    public function storePost(StoreThinkOceanPostRequest $request): RedirectResponse
    {
        ThinkOceanPost::query()->create($this->postData($request));

        return back()->with('status', 'Article created.');
    }

    public function updatePost(StoreThinkOceanPostRequest $request, ThinkOceanPost $post): RedirectResponse
    {
        $post->update($this->postData($request));

        return back()->with('status', 'Article updated.');
    }

    public function destroyPost(ThinkOceanPost $post): RedirectResponse
    {
        $post->delete();

        return back()->with('status', 'Article deleted.');
    }

    /** @return array<string, mixed> */
    private function projectData(StoreThinkOceanProjectRequest $request): array
    {
        return array_merge($request->safe()->except(['is_published']), [
            'is_published' => $request->boolean('is_published'),
        ]);
    }

    /** @return array<string, mixed> */
    private function postData(StoreThinkOceanPostRequest $request): array
    {
        return array_merge($request->safe()->except(['is_published']), [
            'is_published' => $request->boolean('is_published'),
        ]);
    }
}
