@extends('think-ocean.layout')

@section('title', 'Healthier Oceans. Brighter Futures.')
@section('description', $settings['hero_text'])

@push('head')
    <script type="application/ld+json">{!! json_encode(['@context' => 'https://schema.org', '@type' => 'Organization', 'name' => 'Think Ocean', 'url' => route('think-ocean'), 'email' => $contactEmail, 'description' => $settings['hero_text']], JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@push('styles')
    <style>
        .hero{position:relative;display:flex;align-items:center;min-height:min(640px,88vh);overflow:hidden;color:#fff;background:#082f47;isolation:isolate}
        .hero:after{content:'';position:absolute;top:0;right:0;bottom:0;width:64%;z-index:-2;background:url('{{ asset('images/think-ocean/home-hero.jpg') }}') center 45%/cover no-repeat;-webkit-mask-image:linear-gradient(90deg,transparent 0%,#000 38%);mask-image:linear-gradient(90deg,transparent 0%,#000 38%)}
        .hero .wrap{padding:96px 0 110px}
        .hero h1{max-width:620px;color:#fff;font-size:clamp(40px,5.4vw,64px);font-weight:600;letter-spacing:-.015em;line-height:1.1}
        .hero p{max-width:500px;margin-top:22px;font-size:18px;color:#e2f3f7}
        .hero-actions{display:flex;flex-wrap:wrap;gap:14px;margin-top:32px}
        .scroll-cue{position:absolute;left:50%;bottom:26px;transform:translateX(-50%);display:grid;place-items:center;width:40px;height:40px;color:#fff;animation:bob 2.4s ease-in-out infinite}
        .scroll-cue .icon{width:28px;height:28px}
        @keyframes bob{50%{transform:translate(-50%,6px)}}

        .pillars{display:grid;grid-template-columns:repeat(4,1fr);gap:20px}
        .pillar{padding:32px 22px;text-align:center;background:#fff;border:1px solid var(--line);border-radius:var(--radius);box-shadow:var(--shadow)}
        .pillar .icon{width:44px;height:44px;color:var(--teal);margin:0 auto 18px}
        .pillar h2{font-size:17px;font-weight:600}
        .pillar p{margin-top:10px;color:var(--muted);font-size:14px}

        .together{display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center}
        .together img{width:100%;aspect-ratio:4/3;object-fit:cover;border-radius:var(--radius);box-shadow:var(--shadow)}
        .together h2{font-size:clamp(28px,3.4vw,40px);font-weight:600}
        .together p{margin-top:18px;color:var(--muted);font-size:17px}
        .together .btn{margin-top:28px}
        .credentials{display:flex;flex-wrap:wrap;gap:12px;margin-top:24px}
        .credential{padding:12px 16px;border-radius:10px;background:var(--sand);font-size:13px;color:var(--muted)}
        .credential strong{display:block;color:var(--deep);font-size:14px}

        @media (max-width:900px){.pillars{grid-template-columns:repeat(2,1fr)}.together{grid-template-columns:1fr;gap:36px}}
        @media (max-width:900px){.hero:after{width:100%;-webkit-mask-image:none;mask-image:none}.hero:before{content:'';position:absolute;inset:0;z-index:-1;background:linear-gradient(90deg,rgba(8,47,71,.92),rgba(8,47,71,.55))}}
        @media (max-width:560px){.hero p{font-size:16px}.pillars{grid-template-columns:1fr}}
        @media (prefers-reduced-motion:reduce){.scroll-cue{animation:none}}
    </style>
@endpush

@section('content')
    <section class="hero" aria-labelledby="hero-title">
        <div class="wrap">
            <h1 id="hero-title">{{ $settings['hero_title'] }}</h1>
            <p>{{ $settings['hero_text'] }}</p>
            <div class="hero-actions">
                <a class="btn" href="{{ route('think-ocean.pages.show', 'projects') }}">Our Projects</a>
                <a class="btn btn-outline" href="{{ route('think-ocean.pages.show', 'get-involved') }}">Get Involved</a>
            </div>
        </div>
        <a class="scroll-cue" href="#our-work" aria-label="Scroll to our work">@include('think-ocean.partials.icon', ['name' => 'chevron'])</a>
    </section>

    <section class="section" id="our-work" aria-label="What we focus on">
        <div class="wrap pillars">
            <article class="pillar">@include('think-ocean.partials.icon', ['name' => 'fish', 'stroke' => 1.5])<h2>Conserve Marine Ecosystems</h2><p>Protecting ocean life and habitats.</p></article>
            <article class="pillar">@include('think-ocean.partials.icon', ['name' => 'leaf', 'stroke' => 1.5])<h2>Support Sustainable Solutions</h2><p>Working with communities and innovators.</p></article>
            <article class="pillar">@include('think-ocean.partials.icon', ['name' => 'bulb', 'stroke' => 1.5])<h2>Build Climate Resilience</h2><p>Helping people and nature adapt and thrive.</p></article>
            <article class="pillar">@include('think-ocean.partials.icon', ['name' => 'globe', 'stroke' => 1.5])<h2>Inspire Global Action</h2><p>Education, awareness and collaboration.</p></article>
        </div>
    </section>

    <section class="section bg-sand">
        <div class="wrap together">
            <img src="{{ asset('images/think-ocean-coast.jpg') }}" alt="Waves rolling onto a sandy beach at sunset" loading="lazy">
            <div>
                <h2>{{ $settings['mission_title'] }}</h2>
                <p>{{ $settings['mission_text'] }}</p>
                @if($settings['registration_number'] || $settings['registered_office'])
                    <div class="credentials">
                        @if($settings['registration_number'])<div class="credential"><small>CIC registration</small><strong>{{ $settings['registration_number'] }}</strong></div>@endif
                        @if($settings['registered_office'])<div class="credential"><small>Registered office</small><strong>{{ $settings['registered_office'] }}</strong></div>@endif
                    </div>
                @endif
                <a class="btn btn-deep" href="{{ route('think-ocean.pages.show', 'about') }}">Learn More About Us</a>
            </div>
        </div>
    </section>

    @include('think-ocean.partials.partners')

    <section @class(['section', 'pt-0' => $partners->isNotEmpty()])>
        <div class="wrap">
            <div class="cta-band">
                @include('think-ocean.partials.icon', ['name' => 'waves', 'stroke' => 1.5])
                <div>
                    <h2>The ocean connects us all.</h2>
                    <p>Let’s protect it — together.</p>
                </div>
                <a class="btn btn-outline" href="{{ route('think-ocean.pages.show', 'donate') }}">Support Our Work</a>
            </div>
        </div>
    </section>
@endsection
