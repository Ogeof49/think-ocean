<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreThinkOceanPostRequest;
use App\Http\Requests\StoreThinkOceanProjectRequest;
use App\Http\Requests\StoreThinkOceanPartnerRequest;
use App\Http\Requests\UpdateThinkOceanLiveRequest;
use App\Http\Requests\UpdateThinkOceanSettingsRequest;
use App\Models\ThinkOceanMessage;
use App\Models\ThinkOceanPartner;
use App\Models\ThinkOceanPost;
use App\Models\ThinkOceanProject;
use App\Models\ThinkOceanSetting;
use App\Models\ThinkOceanSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ThinkOceanCmsController extends Controller
{
    public function index(): View
    {
        return view('think-ocean-admin.index', [
            'settings' => array_merge(config('think_ocean.defaults'), ThinkOceanSetting::query()->pluck('value', 'key')->all()),
            'projects' => ThinkOceanProject::query()->orderBy('sort_order')->orderByDesc('id')->get(),
            'posts' => ThinkOceanPost::query()->latest('published_at')->latest('id')->get(),
            'partners' => ThinkOceanPartner::query()->orderBy('sort_order')->orderBy('name')->get(),
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

    public function storePartner(StoreThinkOceanPartnerRequest $request): RedirectResponse
    {
        ThinkOceanPartner::query()->create($this->partnerData($request, new ThinkOceanPartner));

        return back()->with('status', 'Partner added.');
    }

    public function updatePartner(StoreThinkOceanPartnerRequest $request, ThinkOceanPartner $partner): RedirectResponse
    {
        $partner->update($this->partnerData($request, $partner));

        return back()->with('status', 'Partner updated.');
    }

    public function destroyPartner(ThinkOceanPartner $partner): RedirectResponse
    {
        if ($partner->logo_path) {
            Storage::disk('think_ocean')->delete($partner->logo_path);
        }

        $partner->delete();

        return back()->with('status', 'Partner deleted.');
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
    private function partnerData(StoreThinkOceanPartnerRequest $request, ThinkOceanPartner $partner): array
    {
        $data = [
            ...$request->safe()->only(['name', 'website_url']),
            'sort_order' => (int) $request->input('sort_order', 0),
            'is_published' => $request->boolean('is_published'),
        ];

        // A new upload or an explicit removal replaces the stored logo file.
        if ($request->hasFile('logo') || $request->boolean('remove_logo')) {
            if ($partner->logo_path) {
                Storage::disk('think_ocean')->delete($partner->logo_path);
            }

            $data['logo_path'] = $request->hasFile('logo') ? $request->file('logo')->store('partners', 'think_ocean') : null;
        }

        return $data;
    }

    /** @return array<string, mixed> */
    private function postData(StoreThinkOceanPostRequest $request): array
    {
        return array_merge($request->safe()->except(['is_published']), [
            'is_published' => $request->boolean('is_published'),
        ]);
    }
}
