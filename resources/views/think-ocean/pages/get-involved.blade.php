@extends('think-ocean.layout')

@section('title', $title)
@section('description', 'Support, volunteer, partner with or spread the word about Think Ocean.')

@php
    $followUrl = collect([$settings['instagram_url'], $settings['facebook_url'], $settings['linkedin_url'], $settings['youtube_url']])->filter()->first();
@endphp

@push('styles')
    <style>
        .ways{display:grid;grid-template-columns:repeat(2,1fr);gap:28px;margin-top:40px}
        .way{display:flex;flex-direction:column;align-items:center;padding:40px 32px;text-align:center;background:#fff;border:1px solid var(--line);border-radius:var(--radius);box-shadow:var(--shadow)}
        .way > .icon{width:44px;height:44px;color:var(--deep)}
        .way h2{margin-top:16px;font-size:20px;font-weight:600}
        .way p{margin-top:8px;color:var(--muted);max-width:340px}
        .way .btn{margin-top:22px}
        .waves-banner{position:relative;display:flex;align-items:flex-end;justify-content:flex-end;min-height:300px;margin-top:56px;padding:40px;overflow:hidden;border-radius:var(--radius);color:#fff;background:var(--deep) url('{{ asset('images/think-ocean/get-involved-banner.jpg') }}') center/cover no-repeat;isolation:isolate}
        .waves-banner:before{content:'';position:absolute;inset:0;z-index:-1;background:linear-gradient(90deg,rgba(8,47,71,0) 20%,rgba(8,47,71,.7) 55%,rgba(8,47,71,.92) 100%)}
        .waves-banner div{max-width:400px;text-align:right}
        .waves-banner h2{color:#fff;font-size:26px;font-weight:600}
        .waves-banner p{margin-top:6px;color:#e2f3f7}
        .waves-banner .btn{margin-top:18px}
        @media (max-width:720px){.ways{grid-template-columns:1fr}.waves-banner{padding:28px}.waves-banner:before{background:rgba(8,47,71,.65)}.waves-banner div{text-align:left}}
    </style>
@endpush

@section('content')
    @include('think-ocean.partials.banner')

    <section class="section">
        <div class="wrap">
            <p class="lead">There are many ways to support Think Ocean — whether you’re an individual, business, partner or volunteer. Together, we can make a bigger impact.</p>

            <div class="ways">
                <article class="way">
                    @include('think-ocean.partials.icon', ['name' => 'heart', 'stroke' => 1.5])
                    <h2>Support Us</h2>
                    <p>Make a donation, fund a project or become a regular supporter.</p>
                    <a class="btn" href="{{ route('think-ocean.pages.show', 'donate') }}">Donate</a>
                </article>
                <article class="way">
                    @include('think-ocean.partials.icon', ['name' => 'users', 'stroke' => 1.5])
                    <h2>Volunteer</h2>
                    <p>Share your skills, time and passion for the ocean.</p>
                    <a class="btn" href="mailto:{{ $settings['volunteer_email'] }}?subject=Think%20Ocean%20Volunteer%20Enquiry">Get Involved</a>
                </article>
                <article class="way">
                    @include('think-ocean.partials.icon', ['name' => 'handshake', 'stroke' => 1.5])
                    <h2>Partner With Us</h2>
                    <p>We work with like-minded organisations, communities and innovators.</p>
                    <a class="btn" href="mailto:{{ $settings['partner_email'] }}?subject=Think%20Ocean%20Partnership%20Enquiry">Partner</a>
                </article>
                <article class="way">
                    @include('think-ocean.partials.icon', ['name' => 'megaphone', 'stroke' => 1.5])
                    <h2>Spread the Word</h2>
                    <p>Follow us, share our work and help raise awareness.</p>
                    @if($followUrl)
                        <a class="btn" href="{{ $followUrl }}" target="_blank" rel="noopener">Follow Us</a>
                    @else
                        <a class="btn" href="{{ route('think-ocean.pages.show', 'news') }}#newsletter">Follow Us</a>
                    @endif
                </article>
            </div>

            <div class="waves-banner">
                <div>
                    <h2>Small actions. Big waves.</h2>
                    <p>Together, we can create a healthier, more resilient ocean.</p>
                    <a class="btn" href="{{ route('think-ocean.pages.show', 'contact') }}">Get Involved</a>
                </div>
            </div>
        </div>
    </section>
@endsection
