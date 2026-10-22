@extends('think-ocean.layout')

@section('title', $title)
@section('description', 'Think Ocean is a UK-based Community Interest Company protecting marine ecosystems and building more resilient coastal communities.')

@push('styles')
    <style>
        .who{display:grid;grid-template-columns:1.1fr .9fr;gap:56px;align-items:center}
        .who p{margin-top:16px;color:var(--muted);font-size:16px}
        .who img{width:100%;aspect-ratio:4/3;object-fit:cover;border-radius:var(--radius);box-shadow:var(--shadow)}
        .statements{display:grid;grid-template-columns:1fr 1fr;gap:28px;margin-top:64px}
        .statement{display:flex;gap:20px;align-items:flex-start;padding:28px;border:1px solid var(--line);border-radius:var(--radius);background:#fff}
        .statement h3{font-size:20px;font-weight:600}
        .statement p{margin-top:8px;color:var(--muted)}
        .values{display:grid;grid-template-columns:repeat(4,1fr);margin-top:32px}
        .value{padding:12px 20px;text-align:center}
        .value + .value{border-left:1px solid #c6e3ea}
        .value .icon{width:38px;height:38px;color:var(--deep);margin:0 auto 12px}
        .value h3{font-size:16px;font-weight:600}
        .value p{margin-top:4px;color:var(--muted);font-size:14px}
        .quote{position:relative;padding:110px 0;text-align:center;color:#fff;background:var(--deep) url('{{ asset('images/think-ocean/about-quote.jpg') }}') center/cover no-repeat;isolation:isolate}
        .quote:before{content:'';position:absolute;inset:0;z-index:-1;background:rgba(8,47,71,.55)}
        .quote blockquote{margin:0}
        .quote p{max-width:620px;margin:0 auto;font:500 clamp(24px,3vw,34px)/1.35 Poppins,sans-serif}
        .quote cite{display:block;margin-top:16px;font-style:normal;color:#cfe6ee}
        @media (max-width:860px){.who,.statements{grid-template-columns:1fr}.values{grid-template-columns:repeat(2,1fr);row-gap:24px}.value:nth-child(3){border-left:0}}
        @media (max-width:480px){.statement{flex-direction:column}}
    </style>
@endpush

@section('content')
    @include('think-ocean.partials.banner')

    <section class="section">
        <div class="wrap">
            <div class="who">
                <div>
                    <h2 class="section-title">Who We Are</h2>
                    <p>Think Ocean is a UK-based Community Interest Company (CIC) dedicated to protecting marine ecosystems and promoting sustainable solutions for a healthier ocean and more resilient coastal communities.</p>
                    <p>We bring together people, ideas and resources to drive positive change — from local projects to global partnerships. Our work is guided by science, collaboration and a deep commitment to the ocean and the people who depend on it.</p>
                </div>
                <img src="{{ asset('images/think-ocean/about-whale.jpg') }}" alt="A humpback whale’s tail breaking the surface of the ocean" loading="lazy">
            </div>

            <div class="statements">
                <article class="statement">
                    <span class="icon-circle">@include('think-ocean.partials.icon', ['name' => 'eye'])</span>
                    <div><h3>Our Vision</h3><p>A healthy ocean, supporting thriving communities and a sustainable future.</p></div>
                </article>
                <article class="statement">
                    <span class="icon-circle">@include('think-ocean.partials.icon', ['name' => 'target'])</span>
                    <div><h3>Our Mission</h3><p>To conserve marine ecosystems, advance sustainable solutions and empower people to take action for a resilient ocean.</p></div>
                </article>
            </div>
        </div>
    </section>

    <section class="section-sm bg-light">
        <div class="wrap">
            <h2 class="section-title">Our Values</h2>
            <div class="values">
                <div class="value">@include('think-ocean.partials.icon', ['name' => 'shield', 'stroke' => 1.5])<h3>Integrity</h3><p>We do what’s right.</p></div>
                <div class="value">@include('think-ocean.partials.icon', ['name' => 'users', 'stroke' => 1.5])<h3>Collaboration</h3><p>Stronger together.</p></div>
                <div class="value">@include('think-ocean.partials.icon', ['name' => 'bulb', 'stroke' => 1.5])<h3>Innovation</h3><p>Better solutions.</p></div>
                <div class="value">@include('think-ocean.partials.icon', ['name' => 'award', 'stroke' => 1.5])<h3>Impact</h3><p>Lasting change.</p></div>
            </div>
        </div>
    </section>

    @include('think-ocean.partials.partners')

    <section class="quote">
        <div class="wrap">
            <blockquote>
                <p>“The ocean is not just a place, it’s a lifeline.”</p>
                <cite>— Think Ocean</cite>
            </blockquote>
        </div>
    </section>
@endsection
