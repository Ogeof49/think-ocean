@php
    $navigation = [
        ['label' => 'Home', 'url' => route('think-ocean'), 'active' => request()->routeIs('think-ocean')],
        ['label' => 'About', 'url' => route('think-ocean.pages.show', 'about'), 'active' => request()->is('think-ocean/pages/about')],
        ['label' => 'What We Do', 'url' => route('think-ocean.pages.show', 'what-we-do'), 'active' => request()->is('think-ocean/pages/what-we-do')],
        ['label' => 'Our Projects', 'url' => route('think-ocean.pages.show', 'projects'), 'active' => request()->is('think-ocean/pages/projects', 'think-ocean/projects/*')],
        ['label' => 'Get Involved', 'url' => route('think-ocean.pages.show', 'get-involved'), 'active' => request()->is('think-ocean/pages/get-involved')],
        ['label' => 'News & Insights', 'url' => route('think-ocean.pages.show', 'news'), 'active' => request()->is('think-ocean/pages/news', 'think-ocean/news/*')],
        ['label' => 'Live TV', 'url' => route('think-ocean.live'), 'active' => request()->routeIs('think-ocean.live'), 'onAir' => ($settings['live_enabled'] ?? '0') === '1'],
        ['label' => 'Contact', 'url' => route('think-ocean.pages.show', 'contact'), 'active' => request()->is('think-ocean/pages/contact')],
    ];
    $socials = array_filter([
        'facebook' => $settings['facebook_url'] ?? null,
        'linkedin' => $settings['linkedin_url'] ?? null,
        'instagram' => $settings['instagram_url'] ?? null,
        'youtube' => $settings['youtube_url'] ?? null,
    ]);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('description', 'Think Ocean — healthier oceans, brighter futures. Practical ocean conservation, education and community action.')">
    <meta property="og:title" content="@yield('title') | Think Ocean">
    <meta property="og:description" content="@yield('description', 'Practical ocean conservation, education and community action for people and the planet.')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/think-ocean/home-hero.jpg') }}">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="icon" type="image/jpeg" href="{{ asset('images/think-ocean/logo.jpg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/think-ocean/logo.jpg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <title>@yield('title') | Think Ocean</title>
    @stack('head')
    <style>
        :root{--deep:#0b3d5b;--deep-2:#082f47;--teal:#00a7a7;--teal-dark:#008c8c;--light:#e6f6f9;--sand:#f5f7fa;--text:#1f2937;--muted:#5b6b78;--line:#e3ebef;--radius:14px;--shadow:0 10px 30px rgba(11,61,91,.08)}
        *{box-sizing:border-box}
        html{scroll-behavior:smooth}
        body{margin:0;font-family:Inter,system-ui,-apple-system,"Segoe UI",sans-serif;color:var(--text);line-height:1.65;background:#fff;-webkit-font-smoothing:antialiased}
        h1,h2,h3,h4{font-family:Poppins,Inter,sans-serif;color:var(--deep);line-height:1.2;margin:0}
        p{margin:0}
        a{color:inherit;text-decoration:none}
        img{display:block;max-width:100%}
        .wrap{width:min(100% - 32px,1160px);margin-inline:auto}
        .narrow{width:min(100% - 32px,920px);margin-inline:auto}
        .icon{width:1em;height:1em;flex:none}
        .sr-only{position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0);white-space:nowrap}
        a:focus-visible,button:focus-visible,input:focus-visible,textarea:focus-visible,summary:focus-visible{outline:3px solid #ffc857;outline-offset:3px}

        /* Buttons */
        .btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;min-height:44px;padding:10px 24px;border-radius:999px;border:1.5px solid var(--teal);background:var(--teal);color:#fff;font:600 14px/1 Inter,sans-serif;cursor:pointer;transition:background .2s,border-color .2s,color .2s,transform .2s}
        .btn:hover{background:var(--teal-dark);border-color:var(--teal-dark);transform:translateY(-1px)}
        .btn-sm{min-height:36px;padding:8px 18px;font-size:13px}
        .btn-outline{background:transparent;border-color:#fff;color:#fff}
        .btn-outline:hover{background:#fff;border-color:#fff;color:var(--deep)}
        .btn-deep{background:var(--deep);border-color:var(--deep)}
        .btn-deep:hover{background:var(--deep-2);border-color:var(--deep-2)}
        .link-arrow{display:inline-flex;align-items:center;gap:6px;color:var(--teal);font-weight:600;font-size:14px}
        .link-arrow:hover{color:var(--teal-dark)}
        .link-arrow .icon{transition:transform .2s}
        .link-arrow:hover .icon{transform:translateX(3px)}

        /* Header */
        .site-header{position:sticky;top:0;z-index:30;background:rgba(255,255,255,.97);backdrop-filter:blur(10px);border-bottom:1px solid var(--line)}
        .nav{display:flex;align-items:center;justify-content:space-between;gap:24px;min-height:80px}
        .brand{display:inline-flex;align-items:center;gap:10px;color:var(--deep);font:700 15px/1 Poppins,sans-serif;letter-spacing:.06em}
        .brand img{width:60px;height:60px;object-fit:contain}
        .nav-links{display:flex;align-items:center;gap:22px;font-size:14px;font-weight:500}
        .nav-links a:not(.btn){position:relative;padding:6px 0;color:var(--text)}
        .nav-links a:not(.btn):hover,.nav-links a.active{color:var(--teal)}
        .nav-links a.active:after{content:'';position:absolute;left:0;right:0;bottom:-2px;height:2px;border-radius:2px;background:var(--teal)}
        .on-air-dot{display:inline-block;width:8px;height:8px;margin-right:6px;border-radius:50%;background:#e11d48;vertical-align:1px;animation:pulse 1.4s ease-in-out infinite}
        @keyframes pulse{50%{opacity:.25}}
        .mobile-nav{display:none}

        /* Page banner */
        .banner{position:relative;color:#fff;background:var(--deep) center/cover no-repeat;isolation:isolate}
        .banner:before{content:'';position:absolute;inset:0;z-index:-1;background:linear-gradient(90deg,rgba(8,47,71,.92) 0%,rgba(11,61,91,.72) 42%,rgba(11,61,91,.15) 80%)}
        .banner .wrap{padding:84px 0 88px}
        .banner h1{color:#fff;font-size:clamp(34px,4.6vw,52px);font-weight:600;letter-spacing:-.01em}
        .banner p{margin-top:12px;max-width:520px;font-size:18px;color:#e2f3f7}

        /* Shared sections */
        .section{padding:80px 0}
        .section-sm{padding:56px 0}
        .section-title{font-size:clamp(26px,3vw,34px);font-weight:600}
        .lead{font-size:17px;color:var(--muted);max-width:760px}
        .eyebrow{display:inline-block;margin-bottom:10px;color:var(--teal);font-size:12px;font-weight:600;letter-spacing:.14em;text-transform:uppercase}
        .bg-light{background:var(--light)}
        .bg-sand{background:var(--sand)}
        .icon-circle{display:grid;place-items:center;width:56px;height:56px;border-radius:50%;background:var(--deep);color:#fff;flex:none}
        .icon-circle .icon{width:26px;height:26px}
        .status{padding:14px 18px;border-radius:10px;background:#e7f7ef;color:#16653a;font-weight:500}
        .error{color:#b42318;font-size:13px;margin-top:6px}

        /* Cards */
        .card{display:flex;flex-direction:column;background:#fff;border:1px solid var(--line);border-radius:var(--radius);overflow:hidden;box-shadow:var(--shadow);transition:transform .25s,box-shadow .25s}
        .card:hover{transform:translateY(-3px);box-shadow:0 18px 40px rgba(11,61,91,.12)}
        .card-media{aspect-ratio:16/10;overflow:hidden;background:var(--light)}
        .card-media img{width:100%;height:100%;object-fit:cover;transition:transform .5s}
        .card:hover .card-media img{transform:scale(1.04)}
        .card-body{display:flex;flex-direction:column;gap:8px;flex:1;padding:22px 24px 24px}
        .card-body h3{font-size:18px;font-weight:600}
        .card-body p{color:var(--muted);font-size:14px}
        .card-body .link-arrow{margin-top:auto;padding-top:8px}
        .post-category{color:var(--teal);font-size:12px;font-weight:600;letter-spacing:.08em;text-transform:uppercase}
        .post-card time{color:var(--muted);font-size:13px}
        .empty{grid-column:1/-1;padding:32px;border:1px dashed #a9cbd6;border-radius:var(--radius);color:var(--muted);text-align:center;background:#fff}

        /* Call-to-action band */
        .cta-band{display:flex;align-items:center;gap:28px;padding:34px 40px;border-radius:var(--radius);background:var(--deep);color:#fff}
        .cta-band > .icon{width:54px;height:54px;color:#7fe0e0}
        .cta-band h2,.cta-band h3{color:#fff;font-size:22px;font-weight:600}
        .cta-band p{color:#cfe6ee;margin-top:4px;max-width:560px}
        .cta-band .btn{margin-left:auto}

        /* Footer */
        .site-footer{background:var(--deep);color:#cfe6ee;text-align:center;padding:56px 0 30px}
        .site-footer .brand img{width:96px;height:96px;border-radius:6px}
        .tagline{margin-top:10px;font-size:15px}
        .socials{display:flex;justify-content:center;gap:12px;margin-top:22px}
        .socials a{display:grid;place-items:center;width:38px;height:38px;border-radius:50%;background:rgba(255,255,255,.1);color:#fff;transition:background .2s}
        .socials a:hover{background:var(--teal)}
        .socials .icon{width:18px;height:18px}
        .footer-links{display:flex;flex-wrap:wrap;justify-content:center;gap:10px 26px;margin-top:30px;font-size:14px}
        .footer-links a:hover{color:#fff}
        .footer-bottom{display:flex;flex-wrap:wrap;justify-content:center;gap:8px 20px;margin-top:30px;padding-top:22px;border-top:1px solid rgba(255,255,255,.12);font-size:13px;color:#9fc3cf}
        .footer-bottom a:hover{color:#fff}

        @media (max-width:1060px){
            .nav-links{display:none}
            .mobile-nav{display:block}
            .mobile-nav summary{display:grid;place-items:center;width:44px;height:44px;border-radius:10px;color:var(--deep);cursor:pointer;list-style:none}
            .mobile-nav summary::-webkit-details-marker{display:none}
            .mobile-nav summary .icon{width:26px;height:26px}
            .mobile-nav nav{position:absolute;top:80px;left:0;right:0;display:grid;gap:4px;padding:16px;background:#fff;border-bottom:1px solid var(--line);box-shadow:0 18px 30px rgba(11,61,91,.12)}
            .mobile-nav nav a:not(.btn){padding:12px 8px;border-radius:8px;font-weight:500}
            .mobile-nav nav a.active{color:var(--teal);background:var(--light)}
            .mobile-nav nav .btn{margin-top:8px}
        }
        @media (max-width:720px){
            .section{padding:56px 0}
            .banner .wrap{padding:60px 0 64px}
            .banner p{font-size:16px}
            .cta-band{flex-direction:column;align-items:flex-start;padding:28px 24px}
            .cta-band .btn{margin-left:0}
        }
        @media (prefers-reduced-motion:reduce){
            html{scroll-behavior:auto}
            *,*:before,*:after{transition:none!important}
        }
    </style>
    @stack('styles')
</head>
<body>
    <header class="site-header">
        <div class="wrap nav">
            <a class="brand" href="{{ route('think-ocean') }}"><img src="{{ asset('images/think-ocean/logo.jpg') }}" alt="Think Ocean CIC — home" width="60" height="60"></a>
            <nav class="nav-links" aria-label="Main navigation">
                @foreach($navigation as $item)
                    <a href="{{ $item['url'] }}" @class(['active' => $item['active']]) @if($item['active']) aria-current="page" @endif>@if($item['onAir'] ?? false)<span class="on-air-dot" aria-label="On air"></span>@endif{{ $item['label'] }}</a>
                @endforeach
                <a class="btn btn-sm" href="{{ route('think-ocean.pages.show', 'donate') }}">Donate</a>
            </nav>
            <details class="mobile-nav">
                <summary aria-label="Open navigation">@include('think-ocean.partials.icon', ['name' => 'menu'])</summary>
                <nav aria-label="Mobile navigation">
                    @foreach($navigation as $item)
                        <a href="{{ $item['url'] }}" @class(['active' => $item['active']])>{{ $item['label'] }}</a>
                    @endforeach
                    <a class="btn" href="{{ route('think-ocean.pages.show', 'donate') }}">Donate</a>
                </nav>
            </details>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="wrap">
            <a class="brand" href="{{ route('think-ocean') }}"><img src="{{ asset('images/think-ocean/logo.jpg') }}" alt="Think Ocean CIC" width="96" height="96" loading="lazy"></a>
            <p class="tagline">A cleaner ocean. A healthier planet.</p>
            @if($socials)
                <div class="socials">
                    @foreach($socials as $network => $url)
                        <a href="{{ $url }}" target="_blank" rel="noopener" aria-label="Think Ocean on {{ ucfirst($network) }}">@include('think-ocean.partials.icon', ['name' => $network])</a>
                    @endforeach
                </div>
            @endif
            <nav class="footer-links" aria-label="Footer navigation">
                @foreach($navigation as $item)
                    <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                @endforeach
            </nav>
            <div class="footer-bottom">
                <span>© {{ date('Y') }} Think Ocean CIC. All rights reserved.</span>
                <a href="{{ route('think-ocean.privacy') }}">Privacy</a>
                <a href="{{ route('think-ocean.accessibility') }}">Accessibility</a>
                @can('manage-think-ocean')
                    <a href="{{ route('think-ocean.admin.index') }}">CMS</a>
                @else
                    <a href="{{ route('think-ocean.login') }}">Admin login</a>
                @endcan
            </div>
        </div>
    </footer>
    <script>document.querySelectorAll('.mobile-nav a').forEach(link => link.addEventListener('click', () => link.closest('details').open = false));</script>
    @stack('scripts')
</body>
</html>
