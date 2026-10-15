<?php

namespace Tests\Unit;

use App\Support\LiveStream;
use PHPUnit\Framework\TestCase;

class LiveStreamTest extends TestCase
{
    public function test_youtube_links_become_privacy_friendly_embeds(): void
    {
        foreach ([
            'https://www.youtube.com/watch?v=abc123XYZ_-',
            'https://youtu.be/abc123XYZ_-',
            'https://www.youtube.com/live/abc123XYZ_-?si=share',
            'https://www.youtube.com/embed/abc123XYZ_-',
        ] as $url) {
            $stream = LiveStream::fromUrl($url);

            $this->assertSame('iframe', $stream['type'], $url);
            $this->assertStringStartsWith('https://www.youtube-nocookie.com/embed/abc123XYZ_-?', $stream['src'], $url);
        }
    }

    public function test_youtube_channel_links_play_the_current_live_stream(): void
    {
        $this->assertStringStartsWith(
            'https://www.youtube-nocookie.com/embed/live_stream?channel=UCabc123',
            LiveStream::fromUrl('https://www.youtube.com/channel/UCabc123')['src'],
        );
    }

    public function test_vimeo_hls_video_and_other_embed_links(): void
    {
        $this->assertSame('https://vimeo.com/event/42/embed?autoplay=1&muted=1', LiveStream::fromUrl('https://vimeo.com/event/42')['src']);
        $this->assertSame('https://player.vimeo.com/video/99?autoplay=1&muted=1', LiveStream::fromUrl('https://vimeo.com/99')['src']);
        $this->assertSame(['type' => 'hls', 'src' => 'https://cdn.example.com/live/index.m3u8'], LiveStream::fromUrl('https://cdn.example.com/live/index.m3u8'));
        $this->assertSame('video', LiveStream::fromUrl('https://cdn.example.com/clip.mp4')['type']);
        $this->assertSame(['type' => 'iframe', 'src' => 'https://player.example.tv/embed/7'], LiveStream::fromUrl('https://player.example.tv/embed/7'));
    }

    public function test_empty_or_invalid_links_return_nothing(): void
    {
        $this->assertNull(LiveStream::fromUrl(null));
        $this->assertNull(LiveStream::fromUrl(''));
        $this->assertNull(LiveStream::fromUrl('not a url'));
    }
}
