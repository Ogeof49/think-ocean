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

    public function privacy(): View
    {
        return view('think-ocean-legal', ['title' => 'Privacy Policy', 'contactEmail' => config('think_ocean.email')]);
    }

    public function accessibility(): View
    {
        return view('think-ocean-legal', ['title' => 'Accessibility Statement', 'contactEmail' => config('think_ocean.email')]);
    }
}
