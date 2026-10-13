<?php

namespace Tests\Unit;

use Tests\TestCase;

class OmneySafariVideoBannerTest extends TestCase
{
    public function test_safari_hero_contains_an_accessible_wildlife_video_banner(): void
    {
        $view = file_get_contents(resource_path('views/omney-safari.blade.php'));

        $this->assertIsString($view);
        $this->assertStringContainsString('class="hero-video"', $view);
        $this->assertStringContainsString('autoplay muted loop playsinline', $view);
        $this->assertStringContainsString('type="video/mp4"', $view);
        $this->assertStringContainsString('@media(prefers-reduced-motion:reduce)', $view);
    }
}
