@extends('think-ocean.layout')

@section('title', $settings['live_title'])
@section('description', $settings['live_description'] ?: 'Watch Think Ocean TV live.')

@push('styles')
    <style>
        .tv{padding:56px 0 72px;background:radial-gradient(ellipse at top,#114a6b 0%,var(--deep-2) 60%);color:#fff}
        .tv-head{display:flex;flex-wrap:wrap;align-items:flex-end;justify-content:space-between;gap:16px;margin-bottom:24px}
        .tv-head h1{color:#fff;font-size:clamp(30px,4vw,44px);font-weight:600}
        .tv-head p{margin-top:8px;max-width:640px;color:#cfe6ee}
        .on-air{display:inline-flex;align-items:center;gap:8px;padding:6px 14px;border-radius:999px;font-size:12px;font-weight:700;letter-spacing:.12em;text-transform:uppercase}
        .on-air.live{background:#e11d48;color:#fff}
        .on-air.live:before{content:'';width:8px;height:8px;border-radius:50%;background:#fff;animation:pulse 1.4s ease-in-out infinite}
        .on-air.off{background:rgba(255,255,255,.12);color:#cfe6ee}
        .screen{position:relative;aspect-ratio:16/9;overflow:hidden;border-radius:var(--radius);background:#000;box-shadow:0 30px 70px rgba(0,0,0,.45)}
        .screen iframe,.screen video{position:absolute;inset:0;width:100%;height:100%;border:0;object-fit:contain;background:#000}
        .live-badge{position:absolute;top:14px;left:14px;z-index:2;padding:5px 10px;border-radius:4px;background:#e11d48;color:#fff;font-size:12px;font-weight:700;letter-spacing:.08em;pointer-events:none}
        .stream-status{margin-top:12px;text-align:center;font-size:13px;color:#9fc3cf}
        .off-screen{position:absolute;inset:0;display:grid;place-items:center;padding:24px;text-align:center;background:linear-gradient(rgba(8,47,71,.78),rgba(8,47,71,.9)),url('{{ asset('images/think-ocean/about-quote.jpg') }}') center/cover}
        .off-screen .icon{width:56px;height:56px;margin:0 auto 14px;color:#7fe0e0}
        .off-screen h2{color:#fff;font-size:clamp(22px,3vw,30px);font-weight:600}
        .off-screen p{margin-top:8px;max-width:460px;color:#cfe6ee}
        .off-screen .btn{margin-top:22px}
        .tv-info{display:grid;grid-template-columns:1.2fr .8fr;gap:28px}
        .info-card{padding:30px;border:1px solid var(--line);border-radius:var(--radius);background:#fff;box-shadow:var(--shadow)}
        .info-card h2{font-size:20px;font-weight:600}
        .info-card p{margin-top:10px;color:var(--muted);white-space:pre-line}
        .info-card .btn{margin-top:20px}
        @media (max-width:820px){.tv-info{grid-template-columns:1fr}}
        @media (max-width:640px){.screen.is-off{aspect-ratio:auto}.screen.is-off .off-screen{position:relative;padding:40px 20px}.off-screen .icon{width:40px;height:40px}.off-screen p{font-size:14px}}
        @media (prefers-reduced-motion:reduce){.on-air.live:before{animation:none}}
    </style>
@endpush

@section('content')
    <section class="tv" aria-labelledby="tv-title">
        <div class="wrap">
            <div class="tv-head">
                <div>
                    <h1 id="tv-title">{{ $settings['live_title'] }}</h1>
                    @if($settings['live_description'])<p>{{ $settings['live_description'] }}</p>@endif
                </div>
                @if($stream)
                    <span class="on-air live">Live now</span>
                @else
                    <span class="on-air off">Off air</span>
                @endif
            </div>

            <div @class(['screen', 'is-off' => ! $stream])>
                @if($stream && $stream['type'] === 'iframe')
                    <iframe src="{{ $stream['src'] }}" title="{{ $settings['live_title'] }} live stream" allow="autoplay; encrypted-media; picture-in-picture; fullscreen" allowfullscreen referrerpolicy="strict-origin-when-cross-origin"></iframe>
                @elseif($stream)
                    <span class="live-badge" aria-hidden="true">● LIVE</span>
                    <video id="live-player" controls autoplay muted playsinline preload="auto" @if($stream['type'] === 'video') src="{{ $stream['src'] }}" @endif data-hls="{{ $stream['type'] === 'hls' ? $stream['src'] : '' }}"></video>
                @else
                    <div class="off-screen">
                        <div>
                            @include('think-ocean.partials.icon', ['name' => 'waves', 'stroke' => 1.5])
                            <h2>We’re off air right now</h2>
                            <p>{{ $settings['live_schedule'] ? 'Check the schedule below for our next broadcast.' : 'Subscribe to our updates and we’ll let you know when we next go live.' }}</p>
                            <a class="btn" href="{{ route('think-ocean.pages.show', 'news') }}#newsletter">Get notified</a>
                        </div>
                    </div>
                @endif
            </div>
            @if($stream && $stream['type'] !== 'iframe')
                <p class="stream-status" id="stream-status" role="status" aria-live="polite">Connecting to live stream…</p>
            @endif
        </div>
    </section>

    <section class="section">
        <div class="wrap tv-info">
            <article class="info-card">
                <h2>About Think Ocean TV</h2>
                <p>{{ $settings['live_description'] ?: 'Ocean stories, conversations and live coverage of our projects and events.' }}</p>
                @if($settings['youtube_url'])
                    <a class="btn btn-deep" href="{{ $settings['youtube_url'] }}" target="_blank" rel="noopener">Watch more on YouTube</a>
                @else
                    <a class="btn btn-deep" href="{{ route('think-ocean.pages.show', 'news') }}">Read our latest stories</a>
                @endif
            </article>
            <article class="info-card">
                <h2>Broadcast schedule</h2>
                <p>{{ $settings['live_schedule'] ?: 'Upcoming broadcasts will be announced here and in our newsletter.' }}</p>
            </article>
        </div>
    </section>
@endsection

@if($stream && $stream['type'] !== 'iframe')
    @push('styles')
        @if($stream['type'] === 'hls')
            <script src="https://cdnjs.cloudflare.com/ajax/libs/hls.js/1.5.13/hls.min.js" defer></script>
        @endif
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const video = document.getElementById('live-player');
                const status = document.getElementById('stream-status');
                const source = video.dataset.hls;
                const liveText = @json('LIVE — '.$settings['live_title']);
                const setStatus = message => status.textContent = message;
                const play = () => video.play().then(() => setStatus(liveText)).catch(() => setStatus('LIVE — press play to start'));

                video.addEventListener('playing', () => setStatus(liveText));
                video.addEventListener('waiting', () => setStatus('Buffering…'));

                if (! source) {
                    video.addEventListener('loadedmetadata', play);
                    video.addEventListener('error', () => setStatus('Unable to connect to the live stream.'));
                    return;
                }

                // Safari, iPhone, iPad and some smart TVs play HLS natively.
                if (video.canPlayType('application/vnd.apple.mpegurl')) {
                    video.src = source;
                    video.addEventListener('loadedmetadata', play);
                    video.addEventListener('error', () => setStatus('Unable to connect to the live stream. Retrying…'));
                    video.addEventListener('error', () => setTimeout(() => { video.src = source; video.load(); }, 5000));
                    return;
                }

                if (! window.Hls || ! Hls.isSupported()) {
                    setStatus('Your browser does not support live streaming. Please try another browser.');
                    return;
                }

                // Chrome, Edge, Firefox and others use hls.js, recovering from dropped connections.
                const start = () => {
                    const hls = new Hls({ enableWorker: true, lowLatencyMode: true, backBufferLength: 30 });
                    hls.loadSource(source);
                    hls.attachMedia(video);
                    hls.on(Hls.Events.MANIFEST_PARSED, play);
                    hls.on(Hls.Events.ERROR, (event, data) => {
                        if (! data.fatal) return;
                        setStatus('Stream connection problem. Reconnecting…');
                        if (data.type === Hls.ErrorTypes.NETWORK_ERROR) {
                            setTimeout(() => hls.startLoad(), 3000);
                        } else if (data.type === Hls.ErrorTypes.MEDIA_ERROR) {
                            hls.recoverMediaError();
                        } else {
                            hls.destroy();
                            setTimeout(start, 3000);
                        }
                    });
                };
                start();
            });
        </script>
    @endpush
@endif
